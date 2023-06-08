<?php

namespace common\components;

use yii\base\Component;
use yii\httpclient\Client;
use yii\helpers\Url;

class Auth0 extends Component
{
    private $apiEndpoint = "https://bawes.us.auth0.com";

    private $client_id = "zBLi5rqikntjIFqS4iJY7RQx6445yf5w";

    private $connection = "Username-Password-Authentication";

    public $domain;
    public $clientId;
    public $clientSecret;
    public $cookieSecret;

    private $_client;

    /**
     * @inheritdoc
     */
    public function init() {

        // Fields required by default
        $requiredAttributes = [];

        // Process Validation
        foreach ($requiredAttributes as $attribute) {
            if ($this->$attribute === null) {
                throw new InvalidConfigException(strtr('"{class}::{attribute}" cannot be empty.', [
                    '{class}' => static::className(),
                    '{attribute}' => '$' . $attribute
                ]));
            }
        }

        parent::init();

        if($this->clientId && $this->clientSecret && $this->cookieSecret && $this->domain) {
            $this->_client = new \Auth0\SDK\Auth0([
                'domain' => $this->domain,
                'clientId' => $this->clientId,
                'clientSecret' => $this->clientSecret,
                'cookieSecret' => $this->cookieSecret,
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'cookie_secret' => $this->cookieSecret,
                'redirect_uri' => Url::to(['site/callback-auth0'], true)
            ]);
        }
    }


    public function login($callbackUrl) {
        return $this->_client->login($callbackUrl);
    }

    public function exchange($callbackUrl) {
        return $this->_client->exchange($callbackUrl);
    }

    public function getCredentials() {
        if (method_exists($this->_client, 'getCredentials')) {
            return $this->_client->getCredentials();
        }

        return $this->_client->getUser();
    }

    public function logout() {
        if (method_exists($this->_client, 'clear')) {
            return $this->_client->clear();
        }

        if (method_exists($this->_client, 'logout')) {
            return $this->_client->logout();
        }
    }
    
    /**
     * return user info
     * @param $accessToken
     * @return mixed
     */
    public function getUserInfo($accessToken) {

        $url = $this->apiEndpoint."/userinfo";

        $client = new Client();
        $response = $client->createRequest()
            ->setUrl($url)
            ->addHeaders([
                'authorization' => 'Bearer '.$accessToken,
                'content-type' => 'application/json',
            ])
            ->send();

        return $response;
    }

    /**
     * @param $userData
     * @return \yii\httpclient\Response
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function createUser($userData) {
        $url = $this->apiEndpoint."/dbconnections/signup";

        $client = new Client();

        $response = $client->createRequest()
            ->setUrl($url)
            ->setMethod('POST')
            ->setData(array_merge($userData,['client_id'=>$this->client_id,'connection'=>$this->connection]))
            ->send();

        return $response;
    }
}
