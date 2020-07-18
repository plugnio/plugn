<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use common\models\Order;
use common\models\OrderItem;
use common\models\OrderItemExtraOption;
use common\models\Restaurant;
use common\models\Payment;
use common\components\TapPayments;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

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

            if ($restaurant_model->isOpen()) {


                $order = new Order();

                $order->restaurant_uuid = $restaurant_model->restaurant_uuid;

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

                    if( Yii::$app->request->getBodyParam("deliver_location_latitude"))
                      $order->latitude = Yii::$app->request->getBodyParam("deliver_location_latitude"); //optional
                    if( Yii::$app->request->getBodyParam("deliver_location_longitude"))
                      $order->longitude = Yii::$app->request->getBodyParam("deliver_location_longitude"); //optional

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
                            if (array_key_exists("customer_instructions", $item) && $item["customer_instructions"] != null)
                                $orderItem->customer_instruction = $item["customer_instructions"];

                            if ($orderItem->save()) {

//                                There seems to be an issue with your payment, please try again.
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
                    if ($order->order_mode == Order::ORDER_MODE_DELIVERY && $order->subtotal < $order->restaurantDelivery->min_charge) {
                        $response = [
                            'operation' => 'error',
                            'message' => 'Minimum order amount ' . Yii::$app->formatter->asCurrency($order->total_price, '', [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => 10])
                        ];
                    }


                    //if payment method not cash redirect customer to payment gateway
                    if ($response == null && $order->payment_method_id != 3) {

                        // Create new payment record
                        $payment = new Payment;
                        $payment->restaurant_uuid = $restaurant_model->restaurant_uuid;
                        $payment->payment_mode = $order->payment_method_id == 1 ? TapPayments::GATEWAY_KNET : TapPayments::GATEWAY_VISA_MASTERCARD;
                        $payment->customer_id = $order->customer->customer_id; //customer id
                        $payment->order_uuid = $order->order_uuid;
                        $payment->payment_amount_charged = $order->total_price;
                        $payment->payment_current_status = "Redirected to payment gateway";

                        if ($payment->save()) {

                            //Update payment_uuid in order
                            $order->payment_uuid = $payment->payment_uuid;
                            $order->save(false);

                            Yii::info("[". $restaurant_model->name . ": Payment Attempt Started] " . $order->customer_name . ' start attempting making a payment ' . Yii::$app->formatter->asCurrency($order->total_price, '', [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => 10]), __METHOD__);


                            // Redirect to payment gateway
                            Yii::$app->tapPayments->setApiKeys($order->restaurant->live_api_key, $order->restaurant->test_api_key);

                            $response = Yii::$app->tapPayments->createCharge(
                                    "Order placed from: " . $order->customer_name, // Description
                                    $order->restaurant->name, //Statement Desc.
                                    $payment->payment_uuid, // Reference
                                    $order->total_price,
                                    $order->customer_name,
                                    $order->customer_email,
                                    $order->customer_phone_number,
                                    $order->restaurant->platform_fee,
                                    Url::to(['order/callback'], true),
                                    $order->payment_method_id == 1 ? TapPayments::GATEWAY_KNET : TapPayments::GATEWAY_VISA_MASTERCARD
                                  );

                            $responseContent = json_decode($response->content);

                            try {

                              // Validate that theres no error from TAP gateway
                              if (isset($responseContent->errors)) {
                                  $errorMessage = "Error: " . $responseContent->errors[0]->code . " - " . $responseContent->errors[0]->description;
                                  \Yii::error($errorMessage, __METHOD__); // Log error faced by user

                                  return [
                                      'operation' => 'error',
                                      'message' => $errorMessage
                                  ];
                              }

                              if($responseContent->id) {

                              $chargeId = $responseContent->id;
                              $redirectUrl = $responseContent->transaction->url;

                               $payment->payment_gateway_transaction_id = $chargeId;

                               if(!$payment->save(false)){

                                 \Yii::error($payment->errors, __METHOD__); // Log error faced by user

                                 return [
                                     'operation' => 'error',
                                     'message' => $payment->getErrors()
                                 ];

                               }

                             } else {
                               \Yii::error('[Payment Issue > Charge id is missing ]' . $responseContent , __METHOD__); // Log error faced by user
                             }


                              return [
                                  'operation' => 'redirecting',
                                  'redirectUrl' => $redirectUrl,
                              ];

                            } catch (\Exception $e) {

                              if($payment)
                                 Yii::error('[TAP Payment Issue > ]' . json_encode($payment->getErrors()) , __METHOD__);

                                Yii::error('[TAP Payment Issue > Charge id is missing]' . json_encode($responseContent) , __METHOD__);

                               $response = [
                                   'operation' => 'error',
                                   'message' => $responseContent
                               ];

                          }

                        } else {

                            $response = [
                                'operation' => 'error',
                                'message' => $payment->getErrors()
                            ];
                        }
                    } else {


                       //pay by Cash
                        if ($response == null) {

                            //Change order status to pending
                            $order->changeOrderStatusToPending();
                            $order->sendPaymentConfirmationEmail();

                            Yii::info("[" . $order->restaurant->name . ": " . $order->customer_name . " has placed an order for " . Yii::$app->formatter->asCurrency($order->total_price, '', [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => 10]) . '] ' . 'Paid with ' . $order->payment_method_name, __METHOD__);


//                            //Update product inventory
//                            foreach ($order->getOrderItems()->all() as $orderItem) {
//                                $orderItem->item->decreaseStockQty($orderItem->qty);
//                            }

                            $response = [
                                'operation' => 'success',
                                'order_uuid' => $order->order_uuid,
                                'estimated_time_of_arrival' => $order->estimated_time_of_arrival,
                                'message' => 'Order created successfully',
                            ];
                        }
                    }
                }



                if (array_key_exists('operation', $response) && $response['operation'] == 'error') {
                    $order->delete();
                }
            } else {
                $response = [
                    'operation' => 'error',
                    'message' => 'Store is closed',
                ];
            }
        } else {
            $response = [
                'operation' => 'error',
                'message' => 'Store Uuid is invalid'
            ];
        }

        return $response;
    }

    /**
     * Process callback from TAP payment gateway
     * @param string $tap_id
     * @return mixed
     */
    public function actionCallback($tap_id) {

        try {
            $paymentRecord = Payment::updatePaymentStatusFromTap($tap_id);
            $paymentRecord->received_callback = true;
            $paymentRecord->save(false);

            // Redirect back to app
            if ($paymentRecord->payment_current_status != 'CAPTURED') {  //Failed Payment
                return $this->redirect($paymentRecord->restaurant->restaurant_domain . '/payment-failed/' . $paymentRecord->order_uuid);
            }

            // Redirect back to app
            $paymentRecord->order->changeOrderStatusToPending();
            return $this->redirect($paymentRecord->restaurant->restaurant_domain . '/payment-success/' . $paymentRecord->order_uuid . '/' . $paymentRecord->payment_uuid);
        } catch (\Exception $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    /**
     * Get Order detail
     * @param type $id
     * @param type $restaurant_uuid
     * @return type
     */
    public function actionOrderDetails($id, $restaurant_uuid) {
        $model = Order::find()->where(['order_uuid' => $id, 'restaurant_uuid' => $restaurant_uuid])->with('restaurant', 'orderItems', 'restaurantBranch', 'payment')->asArray()->one();


        if (!$model) {
            return [
                'operation' => 'error',
                'message' => 'Invalid order uuid'
            ];
        }

        return [
            'operation' => 'success',
            'body' => $model
        ];
    }

    /**
     * CheckPendingOrders of type boolean and we want to return
     * True if there are pending  orders , false if these isn't any
     * @param type $restaurantUuid
     * @return boolean
     */
    public function actionCheckPendingOrders($restaurant_uuid) {
        return Order::find()->where(['restaurant_uuid' => $restaurant_uuid, 'order_status' => Order::STATUS_PENDING])
                        ->exists();
    }

}
