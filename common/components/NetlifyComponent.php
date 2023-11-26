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
     * @param type $store
     * @param type $subdomain
     * @return type
     */
    public function createSite($store, $store_branch = "main") {

        $createSiteEndpoint = $this->apiEndpoint . "/sites";

       // $domain = str_replace("https://", "", $store->restaurant_domain);

        $url = parse_url($store->restaurant_domain);

        $siteParams = [
            "name" => $store->restaurant_uuid,
            "custom_domain" => $url['host'],
            "build_image" => "focal",
            "repo" => [
                "provider" => "github",
                "id" => 70150125,
                "force_ssl" => true,
                "installation_id" => "11420049",
                "repo" => "plugnio/plugn-store",
                "private" => true,
                "branch" => $store_branch,
                "cmd" => "export STORE=".$store->restaurant_uuid." && npm run build",
                "dir" => "dist"
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
     * @param integer $page
     * @return type
     */
    public function listSiteData($page, $query = '') {

        $deploySiteEndpoint = $this->apiEndpoint . "/sites?per_page=2&page" . $page . '&name=' . $query;

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

    /**
     * delete site from netlify
     * @param $site_id
     * @return \yii\httpclient\Response
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function deleteSite($site_id) {

        $apiEndpoint = $this->apiEndpoint . "/sites/" . $site_id;

        $client = new Client();

        $response = $client->createRequest()
            ->setMethod('DELETE')
            ->setUrl($apiEndpoint)
            ->addHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'User-Agent' => 'request',
            ])
            ->send();

        return $response;
    }

    /**
     * update site from netlify
     * @param $site_id
     * @param $params
     * @return \yii\httpclient\Response
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function updateSite($site_id, $params) {

        $apiEndpoint = $this->apiEndpoint . "/sites/" . $site_id;

        $client = new Client();

        $response = $client->createRequest()
            ->setMethod('PATCH')
            ->setData($params)
            ->setUrl($apiEndpoint)
            ->addHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'User-Agent' => 'request',
            ])
            ->send();

        return $response;
    }

    /**
     * upgrade site to newer version
     * @param $store
     * @return \yii\httpclient\Response
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     */
    public function upgradeSite($store) {

        if(!$store) {
            return false;
        }

        $params = [
            "build_image" => "focal",
            "repo" => [
                "provider" => "github",
                "id" => 70150125,
                "force_ssl" => true,
                "installation_id" => "11420049",
                "repo" => "plugnio/plugn-store",
                "private" => true,
                "branch" => "main",
                "cmd" => "export STORE=".$store->restaurant_uuid." && npm run build",
                "dir" => "dist"
            ],
        ];

        return $this->updateSite($store->site_id, $params);
    }
}
