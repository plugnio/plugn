<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use common\models\Order;
use common\models\OrderItem;
use common\models\OrderItemExtraOption;

class OrderController extends Controller {

    public function behaviors() {
        $behaviors = parent::behaviors();

        // remove authentication filter for cors to work
        unset($behaviors['authenticator']);

        // Allow XHR Requests from our different subdomains and dev machines
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => Yii::$app->params['allowedOrigins'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => null,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => [
                    'X-Pagination-Current-Page',
                    'X-Pagination-Page-Count',
                    'X-Pagination-Per-Page',
                    'X-Pagination-Total-Count'
                ],
            ],
        ];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        $actions = parent::actions();
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
            // optional:
            'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }

    /**
     * Place an order
     */
    public function actionPlaceAnOrder($id) {

        $response = [];

        $order = new Order();

        //TODO if restaurant_uuid not exist
        $order->restaurant_uuid = $id;

        //Save Customer Info
        $order->customer_name = Yii::$app->request->getBodyParam("customer_name");
        $order->customer_phone_number = Yii::$app->request->getBodyParam("phone_number");
        $order->customer_email = Yii::$app->request->getBodyParam("email"); //optional
        //payment method
        $order->payment_method_id = Yii::$app->request->getBodyParam("payment_method_id");

        //save Customer address
        $order->area_id = Yii::$app->request->getBodyParam("area_id");
        $order->unit_type = Yii::$app->request->getBodyParam("unit_type");
        $order->block = Yii::$app->request->getBodyParam("block");
        $order->street = Yii::$app->request->getBodyParam("street");
        $order->avenue = Yii::$app->request->getBodyParam("avenue"); //optional
        $order->house_number = Yii::$app->request->getBodyParam("house_number");
        $order->special_directions = Yii::$app->request->getBodyParam("special_directions"); //optional
        $order->order_mode = Yii::$app->request->getBodyParam("order_mode");


        if ($order->save()) {

            $items = Yii::$app->request->getBodyParam("items");


            if ($items) {
                //Save items to the above order
                $orderItem = new OrderItem;

                $orderItem->order_uuid = $order->order_uuid;
                $orderItem->item_uuid = $items["item_uuid"];
                $orderItem->qty = (int) $items["qty"];

                //optional field
                if (array_key_exists("instructions", $items) && $items["instructions"] != null)
                    $orderItem->instructions = $items["instructions"];

                if (array_key_exists('extra_options', $items)) {
                    $extra_options = $items['extra_options'];
                }


                if ($orderItem->save()) {

                    if (isset($extra_options)) {
                        $orderItemExtraOption = new OrderItemExtraOption;
                        $orderItemExtraOption->order_item_id = $orderItem->order_item_id;
                        $orderItemExtraOption->extra_option_id = $extra_options["extra_option_id"];

                        if (!$orderItemExtraOption->save()) {
                            $response = [
                                'operation' => 'error',
                                'message' => $orderItemExtraOption->getErrors()
                            ];
                        }
                    }
                } else {

                    $response = [
                        'operation' => 'error',
                        'message' => $orderItem->getErrors()
                    ];
                }
            } else {
                Order::deleteAll(['order_uuid' => $order]);
                return [
                    'operation' => 'error',
                    'message' => 'Item Uuid is invalid.'
                ];
            }
        } else {
            return [
                'operation' => 'error',
                'message' => $order->getErrors()
            ];
        }


        if ($response == null) {

            $order->delivery_fee = $order->restaurantDelivery->delivery_fee;
            $order->total_items_price = $order->calculateOrderItemsTotalPrice();
            $order->total_price = $order->calculateOrderTotalPrice();

            
            $order->estimated_time_of_arrival = time('h:i',strtotime(date('H:i').'+1hour'));

                        
                        
            if (!$order->save()) {
                $response = [
                    'operation' => 'error',
                    'message' => $order->getErrors()
                ];
            } else {

                $response = [
                    'operation' => 'success',
                    'order_uuid' => $order->order_uuid,
                    'estimated_time_of_arrival' => $order->estimated_time_of_arrival,
                    'message' => 'Order created successfully'
                ];
            }
        }

        return $response;
    }

}
