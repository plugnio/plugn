<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\base\InvalidConfigException;

/**
 * ArmadaDelivery class for requesting a driver to deliver an order
 *
 * @author Saoud Al-Turki <saoud@pogi.io>
 * @link http://www.pogi.io
 */
class ArmadaDelivery extends Component {
    
    
    const USE_TEST_KEY = 1;
    const USE_LIVE_KEY = 2;

     /**
     * @var string Which key to use, test or live?
     */
    public $keyToUse;
    
    /**
     * @var string Variable for live api key to be stored in
     */
    public $liveApiKey;
    
    /**
     * @var string Variable for test api key to be stored in
     */
     public $testApiKey;

    /**
     * @var string secret api key to use will be stored here
     */
    public $secretApiKey;


    public $liveApiEndpoint = "https://api.tap.company/v2";
    
    public $testApiEndpoint = "https://api-simulation-env.herokuapp.com/v0";

 
    private $apiEndpoint;

     
    /**
     * @inheritdoc
     */
    public function init() {
        // Fields required by default
        $requiredAttributes = ['keyToUse','testApiKey', 'liveApiKey'];

        // Process Validation
        foreach ($requiredAttributes as $attribute) {
            if ($this->$attribute === null) {
                throw new InvalidConfigException(strtr('"{class}::{attribute}" cannot be empty.', [
                    '{class}' => static::className(),
                    '{attribute}' => '$' . $attribute
                ]));
            }
        }

        // Set the API key we're going to use
        if ($this->keyToUse == self::USE_LIVE_KEY) {
            $this->secretApiKey = $this->liveApiKey;
            $this->apiEndpoint = $this->liveApiEndpoint;
        } else {
            $this->secretApiKey = $this->testApiKey;
            $this->apiEndpoint = $this->testApiEndpoint;
        }

        parent::init();
    }


    /**
     * Create a delivery request
     */
    public function createDelivery() {
        $deliveryEndpoint = $this->apiEndpoint . "/deliveries";

        $deliveryParams = [
            "platformName" => "pos",
            "platformData" => [
                    "orderId" => "1",
                    "name" => "saoud",
                    "phone" => "51113111",
                    "area" => "Sharq",
                    "block" => "1",
                    "street" => "1",
                    "buildingNumber" => "1",
                    "amount" => "1",
                    "paymentType" => "paid",
                    "threeDSecure" => true,
                    "save_card" => false,
            ],
        ];

        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($deliveryEndpoint)
                ->setData($deliveryParams)
                ->addHeaders([
                    'authorization' => 'Key ' . $this->secretApiKey,
                    'content-type' => 'application/json',
                ])
                ->send();

        return $response;
    }

}
