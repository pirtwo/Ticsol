<?php

namespace App\Ticsol\Components\Controllers\API;

use App\Http\Controllers\Controller;
use App\Ticsol\Components\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{

    const REFRESH_TOKEN = 'refresh_token';
    const COOKIE_LIFE_TIME = 640000;

    /**
     * method: POST.
     * @param Request
     */
    public function login(Request $request)
    {
        try {            
            $user = User::where('user_name', $request->json('body.username'))->first();
            if ($user != null) {
                return $this->proxy('password', [
                    'username' => $request->json('body.username'),
                    'password' => $request->json('body.password'),
                ]);
            } else {
                throw new \Exception('Invalid username.');
            }
        } catch (Exception $e) {
            $this->handelError($e);
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
            $this->handelError($e);
        }
    }

    /**
     * method: POST.
     * @param Request
     */
    public function logout(Request $request)
    {
        try {
            $token = $request->user()->token();
            DB::table('oauth_refresh_tokens')
                ->where('access_token_id', $token->id)
                ->update(['revoked' => true]);
            $token->revoke();
            $request->cookie->forget(self::REFRESH_TOKEN);
            return Response(['success' => true, 'msg' => 'You logout successfuly.']);
        } catch (Exception $e) {
            $this->handelError($e);
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

            // get respond
            $res = app()->handle($req);

            if ($res->status() === 200) {

                $body = json_decode($res->getContent());

                // attach the cookie with refresh_token to response
                return Response([
                    'access_token' => $body->access_token,
                    'expires_in' => $body->expires_in,
                ])->cookie(self::REFRESH_TOKEN, $body->refresh_token, self::COOKIE_LIFE_TIME, null, null, false, true);

            } else {
                throw new \Exception('Invalid Credentials.', 0, null);
            }

        } catch (Exception $e) {
            $this->handelError($e);
        }
    }

    /**
     * Handele the exceptions that occurs in class.
     * @param \Exception $e
     * @return Response
     */
    public function handelError(Exception $e)
    {
        return Response(['error' => true, 'msg' => $e->getMessage()]);
    }

}
