<?php

namespace App\Ticsol\Components\Controllers\API;

use App\Http\Controllers\Controller;
use App\Ticsol\Components\QuickBooks\Classes\QBsAPI;
use App\Ticsol\Components\QuickBooks\Classes\QBsAuth;
use Illuminate\Http\Request;

class QuickBooksController extends Controller
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

    /**
     * method: GET
     * 
     * return the QBs company informations.
     */
    public function companyInfo(Request $req)
    {
        $client = $req->user()->client;

        $api = $this->createQBsAPI($client);

        return \json_encode($api->getCompanyInfo());
    }

    /**
     * method: GET
     * 
     * return the QBs employees list.
     */
    public function employeeList(Request $req)
    {
        $client = $req->user()->client;

        $api = $this->createQBsAPI($client);

        return \json_encode($api->getEmployees());
    }

    /**
     * method: GET
     * 
     * return the QBs cutomers list.
     */
    public function customerList(Request $req)
    {
        $client = $req->user()->client;

        $api = $this->createQBsAPI($client);

        return \json_encode($api->getCustomers());
    }

    /**
     * method: GET
     * 
     * return the QBs class list.
     */
    public function classList(Request $req)
    {
        $client = $req->user()->client;

        $api = $this->createQBsAPI($client);

        return \json_encode($api->getClasses());
    }

    /**
     * method: GET
     * 
     * return QBs departments(locations) list.
     */
    public function departmentList(Request $req)
    {
        $client = $req->user()->client;

        $api = $this->createQBsAPI($client);

        return \json_encode($api->getDepartments());
    }  
    
    // ===== helpers =====

    /**
     * creates instance of QBsAPI class.
     * 
     * @param \App\Ticsol\components\Models\Client $client  
     * @return \App\Ticsol\components\QuickBooks\Classes\QBsAPI
     */
    protected function createQBsAPI($client)
    {
        $auth = $this->createAuth($client);

        return new QBsAPI($auth);
    }

    /**
     * creates instance of QBsAuth class.
     * 
     * @param \App\Ticsol\components\Models\Client $client 
     * @return \App\Ticsol\components\QuickBooks\Classes\QBsAuth 
     */
    protected function createAuth($client)
    {
        if ($client->hasQBsToken()) {
            $token = $client->getQBsToken();
            $auth = new QBsAuth($this->config, $token, $token["realmid"]);

            if ($auth->isTokenExpired()) {
                if ($auth->isRefreshTokenExpired()) {
                    throw new \Exception("refresh token is expired, plaease login again to QBs service.");
                } else {
                    $auth->updateAccessToken();
                    $token = $auth->accessTokenToArray();
                    $client->saveQBsToken($token);
                }
            }
        } else {
            throw new Exception("Error, not authenticated to QBs.", 1);
        }

        return $auth;
    }
}
