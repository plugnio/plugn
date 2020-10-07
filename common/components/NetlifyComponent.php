<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\base\InvalidConfigException;
use common\models\PaymentMethod;

/**
 * Netlify  REST API class 
 *
 * @author Saoud Al-Turki <saoud@plugn.io>
 * @link http://www.plugn.io
 */
class NetlifyComponent extends Component {

    private $apiEndpoint = 'https://api.netlify.com/api/v1';
    
    public $token;

    /**
     * @inheritdoc
     */
    public function init() {
        // Fields required by default
        $requiredAttributes = ['token'];

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
    }


    /**
     * creates a new site.
     * @param type $name the name of the site (mysite.netlify.app)
     * @param type $custom_domain the custom domain of the site (www.example.com)
     * @param type $subdomain
     * @return type
     */
    public function createSite($name, $custom_domain, $subdomain) {

        $createSiteEndpoint = $this->apiEndpoint . "/sites";

        $siteParams = [
            "name" => $name,
            "custom_domain" => $custom_domain,
            "subdomain" => $subdomain,
        ];

        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($createSiteEndpoint)
                ->setFormat(Client::FORMAT_JSON)
                ->setData($siteParams)
                ->addHeaders([
                    'Authorization' => 'Bearer ' . $this->token,
                    'User-Agent' => 'request',
                ])
                ->send();

        return $response;
    }

}
