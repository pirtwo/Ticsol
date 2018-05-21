<?php

namespace App\Ticsol\Components\Controllers\API;

use App\Http\Controllers\Controller;
use App\Ticsol\Components\Exceptions\AuthException;
use App\Ticsol\Components\Models\Invitation;
use App\Ticsol\Components\Models\PasswordReset;
use App\Ticsol\Components\Models\Role;
use App\Ticsol\Components\Models\User;
use App\Ticsol\Components\Requests\LoginRequest;
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
    public function login(LoginRequest $request)
    {
        try {

            $user = User::where('user_name', $request->json('username'))->first();
            if ($user != null) {
                return $this->proxy('password', [
                    'username' => $request->json('username'),
                    'password' => $request->json('password'),
                ]);
            } else {
                throw new AuthException('Invalid username or password.');
            }

        } catch (AuthException $e) {
            return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 400);
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
            return Response(['success' => true, 'msg' => 'You loggedout successfuly.']);
        } catch (Exception $e) {
            $this->handelError($e);
        }
    }

    /**
     * Method: POST.
     * @param Request
     */
    public function resetToken(Request $request)
    {
        try {
            $user = User::where('user_name', $request->json('username'))
                ->where('user_email', $request->json('email'))
                ->first();
            if ($user != null) {
                // create or update token for user
                // send email to user email address
            } else {
                throw new AuthException('Invalid username or email address.');
            }
        } catch (AuthException $e) {
            return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Method: POST
     * @param Request
     */
    public function resetPassword(Request $request)
    {
        try {
            $token = PasswordReset::where('passreset_token', $request->json('token'))
                ->first();
            if ($token != null) {
                $token->user()->update(['user_password', password_hash($request->json('password'))]);
                $token->forceDelete();
                //return success message
            } else {
                throw new AuthException('Invalid password reset token.');
            }
        } catch (Exception $e) {
            return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 400);
        }
    }

    /**
     * Method: POST
     * @param Request
     */
    public function register(Request $request)
    {
        try {
            $token = Invitation::where('invitation_email', $request->json('email'))
                ->where('invitation_token', $request->json('token'))
                ->first();
            if ($token != null) {

                DB::transaction(function () {
                    $user = User::create([
                        'client_id' => $token->user->client_id,
                        'user_name' => $request->json('username'),
                        'user_email' => $request->json('email'),
                        'user_password' => bcrypt($request->json('password')),
                        'user_isowner' => false,                        
                    ]);
                    $role = Role::where('role_name', 'Employee')->firstOrFail();
                    $user->roles->attach($role->role_id);
                });
                // return success message

            } else {
                throw new AuthException('Invalid invitation token.');
            }
        } catch (AuthException $e) {
            return response()->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 400);
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
                throw new \Exception('Invalid Credentials.', 0, null);
            }

        } catch (Exception $e) {
            $this->handelError($e);
        }
    }
}
