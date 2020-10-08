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
    // public function getDeployKey() {
    //
    //     $createSiteEndpoint = $this->apiEndpoint . "/deploy_keys";
    //
    //
    //     $client = new Client();
    //     $response = $client->createRequest()
    //             ->setMethod('POST')
    //             ->setUrl($createSiteEndpoint)
    //             ->addHeaders([
    //                 'Authorization' => 'Bearer ' . $this->token,
    //                 'User-Agent' => 'request',
    //             ])
    //             ->send();
    //
    //     return $response;
    // }

    /**
     * creates a new site.
     * @param type $name the name of the site (mysite.netlify.app)
     * @param type $custom_domain the custom domain of the site (www.example.com)
     * @param type $subdomain
     * @return type
     */
    public function createSite($custom_domain) {

        $createSiteEndpoint = $this->apiEndpoint . "/sites";

        $siteParams = [
            "custom_domain" => $custom_domain,
            "repo" => [
              "provider" => "github",
              "id" => 70150125,
              "installation_id" => "11420049",
              "repo" => "plugnio/plugn-ionic",
              "private" => true,
              "branch" => "master",
              "cmd" => "npm run build",
              "dir" => "www"
            ],
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


    /**
     * deploys a new site.
     * @param type $name the name of the site (mysite.netlify.app)
     * @param type $custom_domain the custom domain of the site (www.example.com)
     * @param type $subdomain
     * @return type
     */
    public function deploySite($site_id) {

        $deploySiteEndpoint = $this->apiEndpoint . "/sites/" .$site_id . '/deploys' ;


        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($deploySiteEndpoint)
                ->addHeaders([
                    'Authorization' => 'Bearer ' . $this->token,
                    'User-Agent' => 'request',
                ])
                ->send();

        return $response;
    }

}
