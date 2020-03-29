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

                    //if payment method not cash redirect customer to payment gateway
                    if ($order->payment_method_id != 3) {

                        // Create new payment record
                        $payment = new Payment;
                        $payment->payment_mode = $order->payment_method_id == 1 ? TapPayments::GATEWAY_KNET : TapPayments::GATEWAY_VISA_MASTERCARD;
                        $payment->customer_id = $order->customer->customer_id; //customer id
                        $payment->order_uuid = $order->order_uuid;
                        $payment->payment_amount_charged = $order->total_price;
                        $payment->payment_current_status = "Redirected to payment gateway";
                        $payment->save();

//                  Yii::info("[Payment Attempt Started] " . Yii::$app->user->identity->investor_name . ' start attempting making a payment ' . Yii::$app->formatter->asCurrency($amountToInvest, '', [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => 10]), __METHOD__);
                        // Redirect to payment gateway
                        $response = Yii::$app->tapPayments->createCharge(
                                "Order placed from: " . $order->customer_name, // Description
                                $order->restaurant->name, //Statement Desc.
                                $payment->payment_uuid, // Reference
                                $order->total_price,
                                $order->customer_name,
                                $order->customer_email,
                                $order->customer_phone_number, 
                                Url::to(['order/callback'], true), 
                                $order->payment_method_id == 1 ? TapPayments::GATEWAY_KNET : TapPayments::GATEWAY_VISA_MASTERCARD
                        );

                        $responseContent = json_decode($response->content);

                        // Validate that theres no error from TAP gateway
                        if (isset($responseContent->errors)) {
                            $errorMessage = "Error: " . $responseContent->errors[0]->code . " - " . $responseContent->errors[0]->description;
                            \Yii::error($errorMessage, __METHOD__); // Log error faced by user
//                \Yii::$app->getSession()->setFlash('error', $errorMessage);

                            return [
                                'operation' => 'error',
                                'message' => $errorMessage
                            ];
                        }

                        $chargeId = $responseContent->id;
                        $redirectUrl = $responseContent->transaction->url;

                        $payment->payment_gateway_transaction_id = $chargeId;
                        $payment->save(false);

                        return [
                            'operation' => 'redirecting',
                            'redirectUrl' => $redirectUrl,
                        ];
                    } else {
                        $response = [
                            'operation' => 'success',
                            'order_uuid' => $order->order_uuid,
                            'estimated_time_of_arrival' => $order->estimated_time_of_arrival,
                            'message' => 'Order created successfully',
                        ];
                    }
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
     * Process callback from TAP payment gateway
     * @param string $tap_id
     * @return mixed
     */
    public function actionCallback($tap_id) {
             
        try {
            $paymentRecord = Payment::updatePaymentStatusFromTap($tap_id);

            $paymentRecord->received_callback = true;
            $paymentRecord->save();

            if ($paymentRecord->payment_current_status != 'CAPTURED') {  //Failed Payment
                Yii::$app->session->setFlash('error', "There seems to be an issue with your payment, please try again.");
                
            // Redirect back to app
                return    $this->redirect('http://localhost:8100/payment-failed/' . $paymentRecord->payment_uuid  );
                // Redirect back to project page with message
//                return [
//                    'operation' => 'error',
//                    'message' => 'There seems to be an issue with your payment, please try again.',
//                ];
            }

            // Redirect back to app
            return    $this->redirect('http://localhost:8100/payment-success/' . $paymentRecord->payment_uuid );
//            return [
//                'operation' => 'success',
//                'order_uuid' => $order->order_uuid,
//                'estimated_time_of_arrival' => $order->estimated_time_of_arrival,
//                'message' => 'Order created successfully',
//            ];

//            return $this->redirect(['investment/view', 'payid' => $paymentRecord->payment_uuid]);
        } catch (\Exception $e) {
                   return    $this->redirect('http://www.yiiframework.com');
            Yii::info($e->getMessage(), __METHOD__);
            throw new NotFoundHttpException($e->getMessage());
        }
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
