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

    public $liveApiEndpoint = "https://api-services.mashkor.com/v1/b/ig/order/create";

    public $testApiEndpoint = "https://ppd-api-services.mashkor.com/v1/b/ig/order/create";


    private $liveAuthKey = "1JLmpNYOrt3MMELh1DoHUE4HemmNDecB";
    private $testAuthKey = "plrWk7iC3Vh4299JcZbdMmVYUKGJCsGk";

    private $liveApiKey = "FAA3A28663DBE";
    private $testApiKey = "637199C367D1D";

    private $apiEndpoint;

    public $apiKey;

    public $authKey;


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
            $this->authKey = $this->liveAuthKey;
            $this->apiKey = $this->liveApiKey;
        } else {
            $this->apiEndpoint = $this->testApiEndpoint;
            $this->authKey = $this->testAuthKey;
            $this->apiKey = $this->testApiKey;

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
    public function createOrder($model,$mashkorBranchId) {

      $phone =  str_replace(' ', '', $model->customer_phone_number);
      $phone =  str_replace('+'.$model->customer_phone_country_code, '', $phone);


        $mashkorParams = [
              "branch_id" => $mashkorBranchId,
              "customer_name" => $model->customer_name,
              "payment_type" => $model->payment_method_id == 3 && $model->total_price > 0 ? self::PAYMENT_TYPE_COD : self::PAYMENT_TYPE_CARD,
              "mobile_number" =>  $phone,
              "amount_to_collect" => \Yii::$app->formatter->asDecimal($model->total_price, 3) ,
              "vendor_order_id" => $model->order_uuid,
              "drop_off" => [
                  "latitude" => null,
                  "longitude" => null,
                  "area" => $model->area_name,
                  "block" => $model->block,
                  "street" => $model->street,
                  "building" => $model->house_number,
                  "landmark" => $model->avenue ? 'Avenue: ' . $model->avenue : null,
                  "specific_driver_instructions" => $model->special_directions,
              ]
        ];


                  if($model->unit_type == 'Office'){

                    $mashkorParams['drop_off']['floor'] = $model->floor;
                    $mashkorParams['drop_off']['room_number'] = $model->office;

                  } else if( $model->unit_type == 'Apartment' ) {

                      $mashkorParams['drop_off']['floor'] = $model->floor;
                      $mashkorParams['drop_off']['room_number'] = $model->apartment;

                  }

        $client = new Client();
        $response = $client->createRequest()
                ->setMethod('POST')
                ->setUrl($this->apiEndpoint)
                ->setFormat(Client::FORMAT_JSON)
                ->setData($mashkorParams)
                ->addHeaders([
                    'x-api-key' => $this->apiKey,
                    'Authorization' => 'Bearer ' . $this->authKey,
                    'content-type' => 'application/json',
                ])
                ->send();

        return $response;
    }

}
