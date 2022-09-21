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
    public function createSite($custom_domain, $store_branch) {

        $createSiteEndpoint = $this->apiEndpoint . "/sites";

        $siteParams = [
            "name" => $store_branch . '-plugn',
            "custom_domain" => $custom_domain,
            "repo" => [
                "provider" => "github",
                "id" => 70150125,
                "force_ssl" => true,
                "installation_id" => "11420049",
                "repo" => "plugnio/plugn-ionic",
                "private" => true,
                "branch" => $store_branch,
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
     *returns the specified site.
     * @param type $site_id
     * @return type
     */
    public function getSiteData($site_id) {

        $deploySiteEndpoint = $this->apiEndpoint . "/sites/" . $site_id ;

        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('GET')
                ->setUrl($deploySiteEndpoint)
                ->addHeaders([
                    'Authorization' => 'Bearer ' . $this->token,
                    'User-Agent' => 'request',
                ])
                ->send();

        return $response;
    }

    /**
     *  Provision SSL for a site
     * @param type $site_id
     * @return type
     */
    public function provisionSSL($site_id) {

        $deploySiteEndpoint = $this->apiEndpoint . "/sites/" . $site_id . '/ssl';

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
