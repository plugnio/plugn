<?php

namespace common\components;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\httpclient\Client;
use yii\base\InvalidConfigException;
use common\models\PaymentMethod;


/**
 * DiggipacksWarehouseComponent class for requesting a driver to deliver an order
 *
 * @author Saoud Al-Turki <saoud@plugn.io>
 * @link http://www.plugn.io
 */
class DiggipacksWarehouseComponent extends Component {



     /**
     * @var string Which key to use, test or live?
     */
    public $keyToUse;

    public $apiEndpoint = "https://api.diggipacks.com/API/createOrder";



    /**
     * @inheritdoc
     */
    public function init() {


        parent::init();
    }

    /**
     * Create a order request
     */
    public function createOrder($model, $orderItems, $customerId = '163180339531') {

      $phone =  str_replace(' ', '', $model->customer_phone_number);
      $phone =  str_replace('+', '00', $phone);

        $itemsArray = [];
        if($orderItems){
          foreach ($orderItems as $key => $orderItem) {
            $itemsArray[$key]['sku'] = $orderItem->item->sku;
            $itemsArray[$key]['piece'] = $orderItem->qty;
          }
        }


        $params = [
          "customerId" => "163180339531",
          "verify" => "false",
          "param" => [
              "BookingMode" => $model->payment_method_id == 3 ? 'COD' : 'CC',
              "codValue" => $model->total_price,
              "reference_id" => $model->order_uuid, //ORDER UUID
              "service" => 3,
              "productType" => "parcel",
              "destination" => $model->area_id ? $model->area->city->city_name : $model->city,
              "receiver_name" => $model->customer_name,
              "receiver_address" => $model->area->city->city_name . ' ' . $model->area_name . ' ' . $model->street. ' ' .  $model->block .' '. $model->avenue .' '. $model->house_number . ' ' . $model->country_name, //TODO need to adjust
              "receiver_phone" => $phone,
              "receiver_email" => $model->customer_email ? $model->customer_email : '',
              "skudetails" => $itemsArray
          ]
        ];


      $client = new Client();
      $response = $client->createRequest()
              ->setMethod('POST')
              ->setUrl($this->apiEndpoint)
              ->setData($params)
              ->addHeaders([
                  'content-type' => 'application/json',
              ])
              ->send();

        return $response;
    }

}
