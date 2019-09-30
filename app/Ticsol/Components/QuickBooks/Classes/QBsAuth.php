<?php

namespace App\Ticsol\Components\QuickBooks\Classes;

use QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2AccessToken;
use QuickBooksOnline\API\DataService\DataService;

/**
 * QuickBooks authentication class, manages quickbooks oauth2 protocol
 */
class QBsAuth
{
    private $config = null;
    private $dataService = null;
    private $accessToken = null;

    /**
     * @param Array $config
     * @param Array $accessToken
     * @param String $realmID
     */
    public function __construct($config, $token = null, $realmID = "")
    {
        $this->config = $config;

        if (\is_array($token)) {
            $this->accessToken = $this->arrayToOAuth2AccessToken($token, $realmID);
        } else {
            $this->accessToken = $token;
        }

        $this->dataService = DataService::Configure(array(
            'auth_mode' => $this->config["auth_mode"],
            'ClientID' => $this->config["ClientID"],
            'ClientSecret' => $this->config["ClientSecret"],
            'RedirectURI' => $this->config["RedirectURI"],
            'scope' => $this->config["scope"],
            'baseUrl' => $this->config["baseUrl"],
        ));

        if ($this->accessToken) {
            $this->dataService->updateOAuth2Token($this->accessToken);
        }
    }

    /**
     * Exchange auth code with access token, returns access token as an array
     *
     * @param String $code
     * @param String $realmID
     *
     * @return Array
     */
    public function exchangeAuthCodeForToken($code, $realmID)
    {
        $OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
        $this->accessToken = $OAuth2LoginHelper->exchangeAuthorizationCodeForToken($code, $realmID);
        $this->dataService->updateOAuth2Token($this->accessToken);

        return $this->accessTokenToArray();
    }

    /**
     * Update the access token with refresh token
     */
    public function updateAccessToken()
    {
        $this->dataService::Configure(array(
            'refreshTokenKey' => $this->accessToken->getRefreshToken(),
            'QBORealmID' => $this->accessToken->getRealmID(),
        ));

        $OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
        $this->accessToken = $OAuth2LoginHelper->refreshToken();
        $this->dataService->updateOAuth2Token($this->accessToken);
    }

    /**
     * Check if access token is expired
     *
     * @return Boolean
     */
    public function isTokenExpired()
    {
        $now = $this->dateTimeNow();
        $now->modify("+5 minutes");

        $tokenExpires = new DateTime($this->accessToken->getAccessTokenExpiresAt());

        return $now > $tokenExpires;
    }

    /**
     * Check if refresh token is expired
     */
    public function isRefreshTokenExpired()
    {
        $now = $this->dateTimeNow();
        $now->modify("+5 minutes");

        $refreshExpires = new DateTime($this->accessToken->getRefreshTokenExpiresAt());

        return $now > $refreshExpires;
    }

    /**
     * Return instance of QuickBooks DataService SDK class
     *
     * @return \QuickBooksOnline\API\DataService\DataService
     */
    public function getDataService()
    {
        return $this->dataService;
    }

    /**
     * Convert access token to array
     *
     * @return Array
     */
    public function accessTokenToArray()
    {
        return [
            "realmid" => $this->accessToken->getRealmID(),
            "access_token" => $this->accessToken->getAccessToken(),
            "refresh_token" => $this->accessToken->getRefreshToken(),
            "expires_in" => $this->accessToken->getAccessTokenValidationPeriodInSeconds(),
            "x_refresh_token_expires_in" => $this->accessToken->getRefreshTokenValidationPeriodInSeconds(),
        ];
    }

    /**
     * Create instance of OAuth2AccessToken from array
     *
     * @param Array $accessToken
     * @param String $realmID
     *
     * @return \QuickBooksOnline\API\Core\OAuth\OAuth2\OAuth2AccessToken
     */
    private function arrayToOAuth2AccessToken($token, $realmID = "")
    {
        $oauth2Token = new OAuth2AccessToken(
            $this->config["ClientID"],
            $this->config["ClientSecret"],
            $token["access_token"],
            $token["refresh_token"],
            $token["expires_in"],
            $token["x_refresh_token_expires_in"]
        );

        $oauth2Token->setRealmID($realmID);

        return $oauth2Token;
    }

    /**
     * Return date time now
     */
    private function dateTimeNow()
    {
        return new DateTime("now");
    }

}
