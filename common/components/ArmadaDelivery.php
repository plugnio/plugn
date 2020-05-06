<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\base\InvalidConfigException;
use common\models\PaymentMethod;


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
    
    public $liveApiEndpoint = "https://api.tap.company/v2";
    
    public $testApiEndpoint = "https://api-simulation-env.herokuapp.com/v0";

    private $apiEndpoint;

     
    /**
     * @inheritdoc
     */
    public function init() {
        // Fields required by default
        $requiredAttributes = ['keyToUse'];

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
            $this->apiEndpoint = $this->liveApiEndpoint;
        } else {
            $this->apiEndpoint = $this->testApiEndpoint;
        }

        parent::init();
    }


    /**
     * Create a delivery request
     */
    public function createDelivery($model) {
        $deliveryEndpoint = $this->apiEndpoint . "/deliveries";

        $deliveryParams = [
            "platformName" => "pos",
            "platformData" => [
                    "orderId" => $model->order_uuid,
                    "name" => $model->customer_name,
                    "phone" =>  $model->customer_phone_number,
                    "area" => $model->area_name,
                    "block" => $model->block,
                    "street" => $model->street,
                    "buildingNumber" => $model->house_number,
                    "amount" => $model->total_price,
                    "paymentType" => $model->payment_method_id == 3 ? 'cash on delivery' : 'paid',
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
                    'authorization' => 'Key ' . $model->restaurant->armada_api_key,
                    'content-type' => 'application/json',
                ])
                ->send();

        return $response;
    }

}
