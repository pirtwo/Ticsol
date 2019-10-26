<?php

namespace App\Ticsol\Components\Controllers\API;

use App\Http\Controllers\Controller;
use App\Ticsol\Components\QuickBooks\Classes\QBsAuth;
use Illuminate\Http\Request;

class QBsAuthController extends Controller
{
    protected $config;

    public function __construct()
    {
        $this->config = array(
            'auth_mode' => env("QUICKBOOKS_AUTH_MODE"),
            'ClientID' => env("QUICKBOOKS_CLIENT_ID"),
            'ClientSecret' => env("QUICKBOOKS_CLIENT_SECRET"),
            'RedirectURI' => env("QUICKBOOKS_REDIRECT_URI"),
            'scope' => env("QUICKBOOKS_SCOPE"),
            'baseUrl' => env("QUICKBOOKS_BASE_URL"),
        );
    }

    public function token(Request $req, $code, $realmid = null)
    {
        $client = $req->user()->client;
        $qbsAuth = new QBsAuth($this->config);

        $token = $qbsAuth->exchangeAuthCodeForToken($code, $realmid);

        $client->saveQBsToken($token);

        return \response()->json(["success" => true], 200);
    }

    public function refresh(Request $req)
    {
        $client = $req->user()->client;

        $auth = new QBsAuth($this->config, $token, $token["realmid"]);

        $auth->updateAccessToken();
        $token = $auth->accessTokenToArray();
        $client->saveQBsToken($token);

        return $token;
    }

    public function hasValidToken(Request $req)
    {
        $client = $req->user()->client;

        if (!$client->hasQBsToken()) {
            return \response()->json([
                "success" => true,
                "isTokenValid" => false,
            ], 200);
        }

        $token = $client->getQBsToken();

        $auth = new QBsAuth($this->config, $token, $token["realmid"]);

        return \response()->json([
            "success" => true,
            "isTokenValid" => !($auth->isTokenExpired() && $auth->isRefreshTokenExpired()),
        ], 200);
    }
}
