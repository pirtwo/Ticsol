<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use App\Ticsol\Models\User;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AuthController extends Controller
{

    const REFRESH_TOKEN = 'refresh_token';
    private $app;
    private $auth;
    private $cookie;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function login(Request $request)
    {
        try {
            $user = User::where('user_name', $request->input('username'))->first();
            if ($user != null) {
                return $this->proxy('password', [
                    'username' => $request->input('username'),
                    'password' => $request->input('password'),
                ]);
            } else {
                throw new \Exception('');
            }
        } catch (\Exception $e) {
            return Response(['error' => true, 'msg' => 'Internal server error.']);
        }
    }

    public function refresh()
    {

    }

    public function logout()
    {

    }

    /**
     * Proxy a request for oauth.
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
                ])->cookie(self::REFRESH_TOKEN, $body->refresh_token, 840000, null, null, false, true);

            } else {
                return Response(['error' => true, 'msg' => 'Invalid Credentials.']);
            }

        } catch (\Exception $e) {
            return Response(['error' => true, 'msg' => 'Internal server error.']);
        }
    }
}
