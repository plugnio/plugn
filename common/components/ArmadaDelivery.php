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
 * https://armadadelivery.github.io
 * https://documenter.getpostman.com/view/3558382/SWLZgAYt#overview
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

    public $liveApiEndpoint = "https://api.armadadelivery.com/v0/deliveries";

    public $testApiEndpoint = "https://api-simulation-env.herokuapp.com/v0/deliveries";

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
    public function createDelivery($model, $armadaApiKey) {

      $phone =  str_replace(' ', '', $model->customer_phone_number);
      $phone =  str_replace('+', '00', $phone);

        $deliveryParams = [
            "platformName" => "plugn",
            "platformData" => [
                    "orderId" => $model->order_uuid,
                    "name" => $model->customer_name,
                    "phone" =>  $phone,
                    "area" => $model->area_name,
                    "block" => $model->block,
                    "street" => $model->street,
                    "buildingNumber" => $model->house_number,
                    "amount" => $model->total,
                    "instructions" => $model->special_directions,
                    "paymentType" => $model->payment_method_id == 3 ? 'cash on delivery' : 'paid',
                    "threeDSecure" => true,
                    "save_card" => false,
            ],
        ];

        return $this->_callAPI('POST', '', $armadaApiKey, $deliveryParams);
    }

    /**
     * @param $model
     * @param $armadaApiKey
     * @return \yii\httpclient\Response
     */
    public function fetchDelivery($model, $armadaApiKey)
    {
        return $this->_callAPI(
            'GET',
            '/'.$model->armada_delivery_code,
            $armadaApiKey,
            []);
    }

    public function cancelDelivery($model, $armadaApiKey)
    {
        return $this->_callAPI(
            'POST',
            '/'.$model->armada_delivery_code.'/cancel',
            $armadaApiKey,
            []);
    }

    private function _callAPI($method, $urlParams, $apiKey, $params) {

        $client = new Client();

        $response = $client->createRequest()
            ->setMethod($method)
            ->setUrl($this->apiEndpoint . $urlParams)
            ->setData($params)
            ->addHeaders([
                'authorization' => 'Key ' . $apiKey,
                'content-type' => 'application/json',
            ])
            ->send();

        return $response;
    }
}
