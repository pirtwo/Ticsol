<?php

namespace App\Ticsol\Components\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2AccessToken;
use QuickBooksOnline\API\DataService\DataService;
use App\Ticsol\Components\QuickBooks\Classes\QBsAuth;

class QuickBooksController extends Controller
{
    private $config;

    public function __construct()
    {
        $this->config = array(
            'auth_mode'     => env("QUICKBOOKS_AUTH_MODE"),
            'ClientID'      => env("QUICKBOOKS_CLIENT_ID"),
            'ClientSecret'  => env("QUICKBOOKS_CLIENT_SECRET"),
            'RedirectURI'   => env("QUICKBOOKS_REDIRECT_URI"),
            'scope'         => env("QUICKBOOKS_SCOPE"),
            'baseUrl'       => env("QUICKBOOKS_BASE_URL"),
        );
    }

    public function token(Request $req, $code, $realmid = null)
    {
        $client = $req->user()->client;
        $qbAuth = new QBsAuth($this->config);

        $token = $qbAuth->exchangeAuthCodeForToken($code, $realmid);

        // save token for client
        $settings = $client->meta;
        $settings["quickbooks"] = $token;
        $client->meta = $settings;
        $client->save();

        return \response()->json(["success" => true], 200);
    }    

    public function getCompanyInfo(Request $req)
    {
        $client = $req->user()->client;
        $token = $this->clientAccessToken($client);
        $qbAuth = new QBsAuth($this->config, $token, $token["realmid"]);

        $dataService = $qbAuth->getDataService();
        $companyInfo = $dataService->getCompanyInfo();

        return \json_encode($companyInfo);
    }

    private function clientAccessToken($client)
    {
        return $client->meta["quickbooks"];
    }
}
