<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\base\InvalidConfigException;
use common\models\PaymentMethod;


/**
 * MashkorDelivery class for requesting a driver to deliver an order
 *
 * @author Saoud Al-Turki <saoud@plugn.io>
 * @link http://www.plugn.io
 */
class MashkorDelivery extends Component {


    const USE_TEST_KEY = 1;
    const USE_LIVE_KEY = 2;

    //Values for `payment_type`
    const PAYMENT_TYPE_COD = 1;
    const PAYMENT_TYPE_CARD = 2;

    //Values for `order_status`
    const ORDER_STATUS_NEW = 0;
    const ORDER_STATUS_CONFIRMED = 1;
    const ORDER_STATUS_ASSIGNED = 2;
    const ORDER_STATUS_PICKUP_STARTED = 3;
    const ORDER_STATUS_PICKED_UP = 4;
    const ORDER_STATUS_IN_DELIVERY = 5;
    const ORDER_STATUS_ARRIVED_DESTINATION = 6;
    const ORDER_STATUS_DELIVERED = 10;
    const ORDER_STATUS_CANCELED = 11;


     /**
     * @var string Which key to use, test or live?
     */
    public $keyToUse;

    public $liveApiEndpoint = "https://ppd-api-services.mashkor.com/v1/b/ig/order/create";

    public $testApiEndpoint = "https://ppd-api-services.mashkor.com/v1/b/ig/order/create";

    private $apiEndpoint;

    public $mashkorApiKey;

    public $tokenId;


    /**
     * @inheritdoc
     */
    public function init() {
        // Fields required by default
        $requiredAttributes = ['keyToUse' , 'mashkorApiKey' , 'tokenId'];

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
     * Get order status
     */
    public function getOrderStatus($order_status) {
      switch ($order_status) {
          case self::ORDER_STATUS_NEW:
              return "New";
              break;
          case self::ORDER_STATUS_CONFIRMED:
              return "Confirmed";
              break;
          case self::ORDER_STATUS_ASSIGNED:
              return "Assigned";
              break;
          case self::ORDER_STATUS_PICKUP_STARTED:
              return "Pickup Started";
              break;
          case self::ORDER_STATUS_PICKED_UP:
              return "Picked Up";
              break;
          case self::ORDER_STATUS_IN_DELIVERY:
              return "In Delivery";
              break;
          case self::ORDER_STATUS_ARRIVED_DESTINATION:
              return "Arrived Destination";
              break;
          case self::ORDER_STATUS_DELIVERED:
              return "Delivered";
              break;
          case self::ORDER_STATUS_CANCELED:
              return "Canceled";
              break;
      }
    }

    /**
     * Get payment type
     */
    public function getPaymentType($model) {
      switch ($model->payment_type) {
          case self::PAYMENT_TYPE_COD:
              return "COD";
              break;
          case self::PAYMENT_TYPE_CARD:
              return "Card";
              break;
      }
    }

    /**
     * Create Order
     */
    public function createOrder($model) {

        $mashkorParams = [
              "branch_id" => $model->restaurant->mashkor_branch_id,
              "customer_name" => $model->customer_name,
              "payment_type" => $model->payment_method_id == 3 ? self::PAYMENT_TYPE_COD : self::PAYMENT_TYPE_CARD,
              "mobile_number" =>  $model->customer_phone_number,
              "amount_to_collect" => $model->total_price,
              "vendor_order_id" => $model->order_uuid,
              "drop_off" => [
                  "customer_name" => $model->restaurant->name,
                  "mobile_number" => $model->restaurant->phone_number,
                  "latitude" => null,
                  "longitude" => null,
                  "area" => $model->area_name,
                  "block" => $model->block,
                  "street" => $model->street,
              ]
        ];

        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($this->apiEndpoint)
                ->setFormat(Client::FORMAT_JSON)
                ->setData($mashkorParams)
                ->addHeaders([
                    'x-api-key' => $this->mashkorApiKey,
                    'Authorization' => 'Bearer ' . $this->tokenId,
                    'content-type' => 'application/json',
                ])
                ->send();

        return $response;
    }

}
