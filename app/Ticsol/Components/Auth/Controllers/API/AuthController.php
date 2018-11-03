<?php

namespace App\Ticsol\Components\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Cookie\CookieJar;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Ticsol\Components\Models\User;
use App\Ticsol\Components\Requests\LoginRequest;
use App\Ticsol\Components\Exceptions\AuthException;

class AuthController extends Controller
{

    const REFRESH_TOKEN = 'refresh_token';
    const COOKIE_LIFE_TIME = 640000;

    /**
     * method: POST.
     * @param Request
     */
    public function login(LoginRequest $request)
    {
        try {

            $user = User::where('name', $request->input('username'))->first();
            if ($user != null) {
                return $this->proxy('password', [
                    'username' => $request->input('username'),
                    'password' => $request->input('password'),
                ]);
            } else {
                throw new \Illuminate\Auth\AuthenticationException('Invalid username or password.');
            }

        } catch (\Illuminate\Auth\AuthenticationException $e) {
            return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 401);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error ocured while proccessing your request.'], 500);
        }
    }

    /**
     * method: POST.
     * @param Request
     */
    public function refresh(Request $request)
    {
        try {
            $refreshToken = $request->cookie(self::REFRESH_TOKEN);
            return $this->proxy(self::REFRESH_TOKEN, [self::REFRESH_TOKEN => $refreshToken]);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error ocured while proccessing your request.'], 500);
        }
    }

    /**
     * method: POST.
     * @param Request
     */
    public function logout(Request $request, CookieJar $cookie)
    {
        try {
            $token = $request->user()->token();
            DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $token->id)
                ->update(['revoked' => true]);
            $token->revoke();
            $cookie->queue($cookie->forget(self::REFRESH_TOKEN));
            return response()->json(['code' => '', 'message' => 'successful logout.'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error ocured while proccessing your request.'], 500);
        }
    }

    /**
     * Proxy a request for oauth server.
     *
     * @param string $grantType
     * @param array $data
     */
    public function proxy($grantType, array $data = [])
    {
        $data = array_merge($data, [
            'grant_type' => $grantType,
            'client_id' => env('Password_Client_ID'),
            'client_secret' => env('Password_Client_Secret'),
        ]);

        try {
            // create request to oauth
            $req = Request::create('/oauth/token', 'POST', $data);

            // process the request and get response
            $res = app()->handle($req);

            if ($res->status() === 200) {
                $body = json_decode($res->getContent());

                // attach the cookie with refresh_token to response
                return Response([
                    'access_token' => $body->access_token,
                    'expires_in' => $body->expires_in,
                ])->cookie(self::REFRESH_TOKEN, $body->refresh_token, self::COOKIE_LIFE_TIME, null, null, false, true);

            } else {
                throw new \Illuminate\Auth\AuthenticationException('Invalid Credentials.');
            }
            
        } catch (\League\OAuth2\Server\Exception\OAuthServerException $e) {
            return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 401);        
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 401);
        } catch (Exception $e) {
            return response()->json(['message' => 'An error ocured while proccessing your request.'], 500);
        }
    }
}
