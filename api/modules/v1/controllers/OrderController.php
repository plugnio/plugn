<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use common\models\Order;
use common\models\OrderItem;
use common\models\OrderItemExtraOption;
use common\models\Restaurant;

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

        $restaurant_model = Restaurant::findOne($id);


        if ($restaurant_model) {

            if ($restaurant_model->restaurant_status == Restaurant::RESTAURANT_STATUS_OPEN) {


                $order = new Order();

                $order->restaurant_uuid = $id;

                //Save Customer Info
                $order->customer_name = Yii::$app->request->getBodyParam("customer_name");
                $order->customer_phone_number = strval(Yii::$app->request->getBodyParam("phone_number"));
                $order->customer_email = Yii::$app->request->getBodyParam("email"); //optional
                //payment method
                $order->payment_method_id = Yii::$app->request->getBodyParam("payment_method_id");

                //save Customer address
                $order->order_mode = Yii::$app->request->getBodyParam("order_mode");

                //if the order mode = 1 => Delivery
                if ($order->order_mode == Order::ORDER_MODE_DELIVERY) {
                    $order->area_id = Yii::$app->request->getBodyParam("area_id");
                    $order->unit_type = Yii::$app->request->getBodyParam("unit_type");
                    $order->block = Yii::$app->request->getBodyParam("block");
                    $order->street = Yii::$app->request->getBodyParam("street");
                    $order->avenue = Yii::$app->request->getBodyParam("avenue"); //optional
                    $order->house_number = Yii::$app->request->getBodyParam("house_number");
                    $order->special_directions = Yii::$app->request->getBodyParam("special_directions"); //optional
                } else if ($order->order_mode == Order::ORDER_MODE_PICK_UP) {
                    $order->restaurant_branch_id = Yii::$app->request->getBodyParam("restaurant_branch_id");
                }


                $response = [];

                if ($order->save()) {

                    $items = Yii::$app->request->getBodyParam("items");


                    if ($items) {

                        foreach ($items as $item) {

                            //Save items to the above order
                            $orderItem = new OrderItem;

                            $orderItem->order_uuid = $order->order_uuid;
                            $orderItem->item_uuid = $item["item_uuid"];
                            $orderItem->qty = (int) $item["qty"];


                            //optional field
                            if (array_key_exists("customer_instruction", $item) && $item["customer_instruction"] != null)
                                $orderItem->customer_instruction = $item["customer_instruction"];

                            if ($orderItem->save()) {

                                if (array_key_exists('extraOptions', $item)) {


                                    $extraOptionsArray = $item['extraOptions'];


                                    if (isset($extraOptionsArray) && count($extraOptionsArray) > 0) {

                                        foreach ($extraOptionsArray as $key => $extraOption) {

                                            $orderItemExtraOption = new OrderItemExtraOption;
                                            $orderItemExtraOption->order_item_id = $orderItem->order_item_id;
                                            $orderItemExtraOption->extra_option_id = $extraOption['extra_option_id'];

                                            if (!$orderItemExtraOption->save()) {

                                                $response = [
                                                    'operation' => 'error',
                                                    'message' => $orderItemExtraOption->errors,
                                                ];
                                            }
                                        }
                                    }
                                }
                            } else {

                                $response = [
                                    'operation' => 'error',
                                    'message' => $orderItem->getErrors()
                                ];
                            }
                        }
                    } else {
                        $response = [
                            'operation' => 'error',
                            'message' => 'Item Uuid is invalid.'
                        ];
                    }
                } else {
                    $response = [
                        'operation' => 'error',
                        'message' => $order->getErrors(),
                    ];
                }

                if ($response == null) {

                    $order->updateOrderTotalPrice();

                    //Send to customer: Email for order confirmation 
                    if ($order->customer_email) {
                        \Yii::$app->mailer->compose([
                                    'html' => 'order-confirmation-html',
                                    'text' => 'order-confirmation-text'
                                        ], [
                                    'order' => $order
                                ])
                                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                                ->setTo($order->customer_email)
                                ->setSubject('Your order from ' . $order->restaurant->name)
                                ->send();
                    }

//                    foreach ($order->restaurant->getAgents() as $agent) {
//
//                        if ($agent->email_notification) {
//                            //Send to All Agents who managed the restaurant: Send email when a new order is received for them to process
//                            \Yii::$app->mailer->compose([
//                                        'html' => 'received-order-html',
//                                        'text' => 'received-order-text'
//                                            ], [
//                                        'order' => $order
//                                    ])
//                                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
//                                    ->setTo($agent->agent_email)
//                                    ->setSubject('Order received frpm' . $order->customer_name)
//                                    ->send();
//                        }
//                    }


                    if ($order->restaurant->agent->email_notification) {
                        //Send to owner: Send email when a new order is received for them to process
                        \Yii::$app->mailer->compose([
                                    'html' => 'received-order-html',
                                    'text' => 'received-order-text'
                                        ], [
                                    'order' => $order
                                ])
                                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                                ->setTo($order->restaurant->agent->agent_email)
                                ->setSubject('Order received from' . $order->customer_name)
                                ->send();
                    }
                    
                    
                    $response = [
                        'operation' => 'success',
                        'order_uuid' => $order->order_uuid,
                        'estimated_time_of_arrival' => $order->estimated_time_of_arrival,
                        'message' => 'Order created successfully',
                    ];
                }


                if ($response['operation'] == 'error') {
                    Order::deleteAll(['order_uuid' => $order->order_uuid]);
                }
            } else if ($restaurant_model->restaurant_status == Restaurant::RESTAURANT_STATUS_CLOSE) {
                $response = [
                    'operation' => 'error',
                    'message' => 'Restaurant is close',
                ];
            }
        } else {
            $response = [
                'operation' => 'error',
                'message' => 'Restaurant Uuid is invalid'
            ];
        }


        return $response;
    }

    /**
     * Get order status
     */
    public function actionOrderLookUp($id) {
        $model = Order::findOne($id);

        if (!$model) {
            return [
                'operation' => 'error',
                'message' => 'Please insert a valid Order Code'
            ];
        }

        return [
            'operation' => 'success',
            'body' => $model
        ];
    }

}
