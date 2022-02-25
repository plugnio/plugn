<?php

namespace api\modules\v2\controllers;

use agent\models\PaymentMethod;
use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use common\models\Voucher;
use common\models\Bank;
use api\models\Order;
use common\models\OrderItem;
use common\models\CustomerBankDiscount;
use common\models\OrderItemExtraOption;
use api\models\Restaurant;
use common\models\BankDiscount;
use api\models\Payment;
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

            $order = new Order();

            //as we will calculate after items get saved
            $order->total_price = 0;
            $order->subtotal = 0;

            $order->restaurant_uuid = $restaurant_model->restaurant_uuid;

            //Save Customer Info
            $order->customer_name = Yii::$app->request->getBodyParam("customer_name");
            $order->customer_phone_number = str_replace(' ','',strval(Yii::$app->request->getBodyParam("phone_number")));
            $order->customer_phone_country_code = Yii::$app->request->getBodyParam("country_code") ? Yii::$app->request->getBodyParam("country_code") : 965;
            $order->customer_email = Yii::$app->request->getBodyParam("email"); //optional

            if($order->restaurant_uuid == 'rest_fe5b6a72-18a7-11ec-973b-069e9504599a'){
              if(Yii::$app->request->getBodyParam("civil_id"))
                $order->civil_id = Yii::$app->request->getBodyParam("civil_id");
              if(Yii::$app->request->getBodyParam("section"))
                $order->section = Yii::$app->request->getBodyParam("section");
              if(Yii::$app->request->getBodyParam("class"))
                $order->class = Yii::$app->request->getBodyParam("class");
            }

            //payment method
            $order->payment_method_id = Yii::$app->request->getBodyParam("payment_method_id");

            /**
             *
            $paymentMethod = PaymentMethod::find()
            ->andWhere (['payment_method_id' => $order->payment_method_id])
            ->one();

            $order->payment_method_name = $paymentMethod->payment_method_name;
            $order->payment_method_name_ar = $paymentMethod->payment_method_name_ar;

             */
            //save Customer address
            $order->order_mode = Yii::$app->request->getBodyParam("order_mode");

            $order->currency_code = Yii::$app->currency->getCode();
                ///Yii::$app->request->getBodyParam ("currency_code");

            //Preorder
            // if( Yii::$app->request->getBodyParam("is_order_scheduled") !== null)
            $order->is_order_scheduled = Yii::$app->request->getBodyParam("is_order_scheduled") ? Yii::$app->request->getBodyParam("is_order_scheduled") : 0;

            //Apply promo code
            if (Yii::$app->request->getBodyParam("voucher_id")) {
                $order->voucher_id = Yii::$app->request->getBodyParam("voucher_id");
            }

            //if the order mode = 1 => Delivery
            if ($order->order_mode == Order::ORDER_MODE_DELIVERY) {

              if(Yii::$app->request->getBodyParam("area_id") && Yii::$app->request->getBodyParam("area_delivery_zone") ){
                $order->delivery_zone_id = Yii::$app->request->getBodyParam("delivery_zone_id");
                $order->area_id = Yii::$app->request->getBodyParam("area_id");
                $order->unit_type = Yii::$app->request->getBodyParam("unit_type");
                $order->block = Yii::$app->request->getBodyParam("block");
                $order->street = Yii::$app->request->getBodyParam("street");
                $order->avenue = Yii::$app->request->getBodyParam("avenue"); //optional
                $order->house_number = Yii::$app->request->getBodyParam("house_number");

                if( Yii::$app->request->getBodyParam("floor") != null && ($order->unit_type == 'Apartment' || $order->unit_type == 'Office' ) )
                  $order->floor = Yii::$app->request->getBodyParam("floor");

                if( Yii::$app->request->getBodyParam("apartment") != null && $order->unit_type == 'Apartment' )
                  $order->apartment = Yii::$app->request->getBodyParam("apartment");

                if( Yii::$app->request->getBodyParam("office") != null && $order->unit_type == 'Office' )
                  $order->office = Yii::$app->request->getBodyParam("office");
              }

            else if( Yii::$app->request->getBodyParam("country_id") && !Yii::$app->request->getBodyParam("area_id") && !Yii::$app->request->getBodyParam("area_delivery_zone") ){

                $order->delivery_zone_id = Yii::$app->request->getBodyParam("delivery_zone_id");
                $order->shipping_country_id = Yii::$app->request->getBodyParam("country_id");
                $order->address_1 = Yii::$app->request->getBodyParam('address_1');
                $order->address_2 = Yii::$app->request->getBodyParam('address_2');
                $order->postalcode = Yii::$app->request->getBodyParam('postal_code');
                $order->city = Yii::$app->request->getBodyParam("city");
              }

                $order->special_directions = Yii::$app->request->getBodyParam("special_directions"); //optional

                if (Yii::$app->request->getBodyParam("deliver_location_latitude"))
                    $order->latitude = Yii::$app->request->getBodyParam("deliver_location_latitude"); //optional
                if (Yii::$app->request->getBodyParam("deliver_location_longitude"))
                    $order->longitude = Yii::$app->request->getBodyParam("deliver_location_longitude"); //optional

                //Preorder
                if ($order->is_order_scheduled != null && $order->is_order_scheduled == true && $restaurant_model->schedule_order) {
                    $order->scheduled_time_start_from = date("Y-m-d H:i:s", strtotime(Yii::$app->request->getBodyParam("scheduled_time_start_from")));
                    $order->scheduled_time_to = date("Y-m-d H:i:s", strtotime(Yii::$app->request->getBodyParam("scheduled_time_to")));
                }
            } else if ($order->order_mode == Order::ORDER_MODE_PICK_UP) {
                $order->pickup_location_id = Yii::$app->request->getBodyParam("business_location_id");
            }

            $response = [];

            if ($order->save()) {

                if($order->restaurant->enable_gift_message) {

                  //save gift message
                  $order->sender_name = Yii::$app->request->getBodyParam("sender_name");
                  $order->recipient_name = Yii::$app->request->getBodyParam("recipient_name");
                  $order->recipient_phone_number = Yii::$app->request->getBodyParam("recipient_phone_number");
                  $order->gift_message = Yii::$app->request->getBodyParam("gift_message");

                }

                $items = Yii::$app->request->getBodyParam("items");

                if ($items) {

                    foreach ($items as $item) {

                        //Save items to the above order
                        $orderItem = new OrderItem;

                        $orderItem->order_uuid = $order->order_uuid;
                        $orderItem->restaurant_uuid = $order->restaurant_uuid;
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
                                        $orderItemExtraOption->qty = (int) $item["qty"];

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

            if (!$order->is_order_scheduled && !$restaurant_model->isOpen()) {
                $response = [
                    'operation' => 'error',
                    'message' => $restaurant_model->name . ' is currently closed and is not accepting orders at this time',
                ];
            }

            if ($response == null) {

                $order->updateOrderTotalPrice();

                /**
                 * payment method should be free checkout if total is zero
                 */
                if($order->total_price == 0) {

                    $freeCheckout = $order->restaurant->getPaymentMethods()
                        //->joinWith('payment')
                        ->andWhere (['payment_method_code' => PaymentMethod::CODE_FREE_CHECKOUT])
                        ->one();

                    if(!$freeCheckout) {
                        $response = [
                            'operation' => 'error',
                            'message' => "Free checkout not enabled on this store!"
                        ];
                    }
                    else if($order->payment_method_id != $freeCheckout->payment_method_id)
                    {
                        $response = [
                            'operation' => 'error',
                            'message' => "Invalid payment method for free order!"
                        ];
                    }
                }

                if ($order->order_mode == Order::ORDER_MODE_DELIVERY && $order->subtotal < $order->deliveryZone->min_charge) {
                    $response = [
                        'operation' => 'error',
                        'message' => 'Minimum order amount ' . Yii::$app->formatter->asCurrency($order->deliveryZone->min_charge, $order->currency->code, [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => $order->currency->decimal_place])
                    ];
                }

                //if payment method not cash redirect customer to payment gateway
                if ($response == null &&  !in_array ($order->payment_method_id, [3, 7])) {

                    // Create new payment record
                    $payment = new Payment;
                    $payment->restaurant_uuid = $restaurant_model->restaurant_uuid;

                    $payment->customer_id = $order->customer->customer_id; //customer id
                    $payment->order_uuid = $order->order_uuid;
                    $payment->payment_amount_charged = $order->total_price;
                    $payment->payment_current_status = "Redirected to payment gateway";

                    if($restaurant_model->is_tap_enable){

                      $payment->payment_mode = $order->paymentMethod->source_id;
                      $payment->payment_gateway_name = 'tap';

                      if ($payment->payment_mode == TapPayments::GATEWAY_VISA_MASTERCARD && Yii::$app->request->getBodyParam("payment_token") && Yii::$app->request->getBodyParam("bank_name")) {

                          Yii::$app->tapPayments->setApiKeys($order->restaurant->live_api_key, $order->restaurant->test_api_key);

                          $response = Yii::$app->tapPayments->retrieveToken(Yii::$app->request->getBodyParam("payment_token"));

                          $responseContent = json_decode($response->content);

                          try {

                              // Validate that theres no error from TAP gateway
                              if (isset($responseContent->status) && $responseContent->status == "fail") {
                                  $errorMessage = "Error: Invalid Token ID";
                                  \Yii::error($errorMessage, __METHOD__); // Log error faced by user

                                  return [
                                      'operation' => 'error',
                                      'message' => 'Invalid Token ID'
                                  ];
                              } else if (isset($responseContent->id) && $responseContent->id) {

                                  $bank_name = Yii::$app->request->getBodyParam("bank_name");

                                  $bank_discount_model = BankDiscount::find()
                                          ->innerJoin('bank', 'bank.bank_id = bank_discount.bank_id')
                                          ->andWhere(['bank.bank_name' => $bank_name])
                                          ->andWhere(['restaurant_uuid' => $order->restaurant_uuid])
                                          ->andWhere(['<=' ,'minimum_order_amount' , $order->total_price])
                                          ->one();

                                  if ($bank_discount_model) {
                                      if ($bank_discount_model->isValid($order->customer_phone_number)) {
                                          $customerBankDiscount = new CustomerBankDiscount();
                                          $customerBankDiscount->customer_id = $order->customer_id;
                                          $customerBankDiscount->bank_discount_id = $bank_discount_model->bank_discount_id;
                                          $customerBankDiscount->save();
                                      }

                                      $order->bank_discount_id = $bank_discount_model->bank_discount_id;
                                  }

                                  $payment->payment_token = Yii::$app->request->getBodyParam("payment_token");
                              }
                          } catch (\Exception $e) {
                              Yii::error('[TAP Payment Issue > Invalid Token ID]' . json_encode($responseContent), __METHOD__);

                              $response = [
                                  'operation' => 'error',
                                  'message' => 'Invalid Token id'
                              ];
                          }
                      }

                      if ($payment->save()) {

                          //Update payment_uuid in order
                          $order->payment_uuid = $payment->payment_uuid;
                          $order->save(false);

                          $order->updateOrderTotalPrice();

                          Yii::info("[" . $restaurant_model->name . ": Payment Attempt Started] " . $order->customer_name . ' start attempting making a payment ' . Yii::$app->formatter->asCurrency($order->total_price, $order->currency->code, [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => $order->currency->decimal_place]), __METHOD__);

                          // Redirect to payment gateway
                          Yii::$app->tapPayments->setApiKeys($order->restaurant->live_api_key, $order->restaurant->test_api_key);

                          $response = Yii::$app->tapPayments->createCharge(
                                  $order->currency->code,
                                  "Order placed from: " . $order->customer_name, // Description
                                  $order->restaurant->name, //Statement Desc.
                                  $payment->payment_uuid, // Reference
                                  $order->total_price,
                                   $order->customer_name,
                                   $order->customer_email,
                                   $order->customer_phone_country_code,
                                   $order->customer_phone_number,
                                   $order->restaurant->platform_fee,
                                   Url::to(['order/callback'], true),
                                   Url::to(['order/payment-webhook'], true),
                                  $order->paymentMethod->source_id == TapPayments::GATEWAY_VISA_MASTERCARD && $payment->payment_token ? $payment->payment_token : $order->paymentMethod->source_id,
                                  $order->restaurant->warehouse_fee,
                                  $order->restaurant->warehouse_delivery_charges,
                                  $order->area_id ? $order->area->country->country_name : ''
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

                              if ($responseContent->id) {

                                  $chargeId = $responseContent->id;
                                  $redirectUrl = $responseContent->transaction->url;

                                  $payment->payment_gateway_transaction_id = $chargeId;

                                  if (!$payment->save(false)) {

                                      \Yii::error($payment->errors, __METHOD__); // Log error faced by user

                                      return [
                                          'operation' => 'error',
                                          'message' => $payment->getErrors()
                                      ];
                                  }
                              } else {
                                  \Yii::error('[Payment Issue > Charge id is missing ]' . $responseContent, __METHOD__); // Log error faced by user
                              }


                              return [
                                  'operation' => 'redirecting',
                                  'redirectUrl' => $redirectUrl,
                              ];
                          } catch (\Exception $e) {
                            Yii::error('[Error when converting to BHD Currency]222' , __METHOD__);

                              if ($payment)
                                  Yii::error('[TAP Payment Issue > ]' . json_encode($payment->getErrors()), __METHOD__);

                              Yii::error('[TAP Payment Issue > Charge id is missing]' . json_encode($responseContent), __METHOD__);

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

                    }
                    else if ($restaurant_model->is_myfatoorah_enable){

                        $payment->payment_gateway_name = 'myfatoorah';
                        $payment->payment_mode = $order->paymentMethod->payment_method_name;

                        if ($payment->save()) {


                            //Update payment_uuid in order
                            $order->payment_uuid = $payment->payment_uuid;
                            $order->save(false);
                            $order->updateOrderTotalPrice();

                            Yii::info("[" . $restaurant_model->name . ": Payment Attempt Started] " . $order->customer_name . ' start attempting making a payment ' . Yii::$app->formatter->asCurrency($order->total_price, $order->currency->code, [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => $order->currency->decimal_place]), __METHOD__);

                            Yii::$app->myFatoorahPayment->setApiKeys($order->currency->code);
                            $initiatePayment = Yii::$app->myFatoorahPayment->initiatePayment($order->total_price, $order->currency->code);
                            $initiatePaymentResponse = json_decode($initiatePayment->content);

                            if (!$initiatePaymentResponse->IsSuccess) {
                                $errorMessage = "Error: " . $responseContent->Message . " - " . isset($responseContent->ValidationErrors) ?  json_encode($responseContent->ValidationErrors) :  $responseContent->Message;
                                \Yii::error($errorMessage, __METHOD__); // Log error faced by user


                                return [
                                    'operation' => 'error',
                                    'message' =>  $errorMessage
                                ];
                            }

                            $paymentMethodId = null;
                            foreach ($initiatePaymentResponse->Data->PaymentMethods as $key => $paymentMethod) {

                              if($order->paymentMethod->payment_method_code == $paymentMethod->PaymentMethodCode)
                                $paymentMethodId = $paymentMethod->PaymentMethodId;

                            }

                            if($paymentMethodId == null){
                              return [
                                  'operation' => 'error',
                                  'message' =>  'This payment method is not supported'
                              ];
                            }

                            Yii::$app->myFatoorahPayment->setApiKeys($order->currency->code);

                            $response = Yii::$app->myFatoorahPayment->createCharge(
                                    $order->currency->code,
                                    $order->total_price,
                                    $order->customer_name,
                                    $order->customer_email,
                                    $order->customer_phone_country_code,
                                    $order->customer_phone_number,
                                    Url::to(['order/my-fatoorah-callback'], true),
                                    $order->order_uuid,
                                    $restaurant_model->supplierCode,
                                    $order->restaurant->platform_fee,
                                    $paymentMethodId,
                                    $order->paymentMethod->payment_method_code,
                                    $order->restaurant->warehouse_fee,
                                    $order->restaurant->warehouse_delivery_charges,
                                    $order->area_id ? $order->area->country->country_name : ''
                            );

                              $responseContent = json_decode($response->content);

                                if (!$responseContent->IsSuccess) {
                                    $errorMessage = "Error: " . $responseContent->Message . " - " . isset($responseContent->ValidationErrors) ?  json_encode($responseContent->ValidationErrors[0]->Error) :  $responseContent->Message;
                                    \Yii::error($errorMessage, __METHOD__); // Log error faced by user


                                    return [
                                        'operation' => 'error',
                                        'message' =>  $errorMessage
                                    ];
                                }


                                if ($responseContent->Data) {

                                    $invoiceId = $responseContent->Data->InvoiceId;
                                    $redirectUrl = $responseContent->Data->PaymentURL;

                                    $payment->payment_gateway_invoice_id = $invoiceId;

                                    if (!$payment->save(false)) {

                                        \Yii::error($payment->errors, __METHOD__); // Log error faced by user

                                        return [
                                            'operation' => 'error',
                                            'message' => $payment->getErrors()
                                        ];
                                    }
                                } else {

                                if ($payment)
                                    Yii::error('[MyFatoorah Payment Issue > InvoiceId]' . json_encode($payment->getErrors()), __METHOD__);

                                  Yii::error('[MyFatoorah Payment Issue]' . json_encode($responseContent), __METHOD__);

                                  $response = [
                                      'operation' => 'error',
                                      'message' => $responseContent
                                  ];
                                }


                                return [
                                    'operation' => 'redirecting',
                                    'redirectUrl' => $redirectUrl,
                                    'orderUuid' => $order->order_uuid
                                ];

                        } else {

                            $response = [
                                'operation' => 'error',
                                'message' => $payment->getErrors()
                            ];
                        }

                    }

                  else {
                    $response = [
                        'operation' => 'error',
                        'message' => 'Sorry we are not able to process your request Please try again later'
                    ];
                  }

                } else {

                    //pay by Cash or Free checkout
                    if ($response == null) {

                        //Change order status to pending
                        $order->changeOrderStatusToPending();
                        $order->sendPaymentConfirmationEmail();

                        Yii::info("[" . $order->restaurant->name . ": " . $order->customer_name . " has placed an order for " . Yii::$app->formatter->asCurrency($order->total_price, $order->currency->code, [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => $order->currency->decimal_place]) . '] ' . 'Paid with ' . $order->payment_method_name, __METHOD__);

                        $response = [
                            'operation' => 'success',
                            'order_uuid' => $order->order_uuid,
                            // 'estimated_time_of_arrival' => $order->estimated_time_of_arrival,
                            'message' => 'Order created successfully',
                        ];
                    }
                }
            }

            if (array_key_exists('operation', $response) && $response['operation'] == 'error') {

                //either object of payment result or not stock validation error

              if(!is_array($response['message']) || !isset($response['message']['qty']))
                 \Yii::error(json_encode($response['message']), __METHOD__); // Log error faced by user

              //$order->delete();
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
     * Process callback from My Fatoorah payment gateway
     * @param string $paymentId
     * @return mixed
     */
    public function actionMyFatoorahCallback($paymentId) {

            Yii::$app->myFatoorahPayment->setApiKeys('KWD');
            $response = Yii::$app->myFatoorahPayment->retrieveCharge($paymentId, 'PaymentId');

            $responseContent = json_decode($response->content);

            if(!$responseContent->IsSuccess){
              Yii::$app->myFatoorahPayment->setApiKeys('SAR');
              $response = Yii::$app->myFatoorahPayment->retrieveCharge($paymentId, 'PaymentId');
              $responseContent = json_decode($response->content);
            }


            if($responseContent->IsSuccess){

              $paymentRecord = Payment::updatePaymentStatusFromMyFatoorah($responseContent->Data->InvoiceId);

              $paymentRecord->payment_gateway_transaction_id = $responseContent->Data->InvoiceTransactions[0]->TransactionId; //TransactionId
              $paymentRecord->payment_gateway_payment_id = $responseContent->Data->InvoiceTransactions[0]->PaymentId; //payment_gateway_transaction_id = PaymentId
              $paymentRecord->payment_gateway_order_id = $responseContent->Data->InvoiceTransactions[0]->ReferenceId;
              $paymentRecord->save(false);

              // Redirect back to app
              if ($paymentRecord->payment_current_status != 'Paid' && $paymentRecord->payment_current_status != 'Succss' && $paymentRecord->payment_current_status != 'SUCCSS' && $paymentRecord->payment_current_status != 'SUCCESS') {  //Failed Payment
                  return $this->redirect($paymentRecord->restaurant->restaurant_domain . '/payment-failed/' . $paymentRecord->order_uuid);
              }

              // Redirect back to app
              return $this->redirect($paymentRecord->restaurant->restaurant_domain . '/payment-success/' . $paymentRecord->order_uuid . '/' . $paymentRecord->payment_uuid);

            } else {
              $errorMessage = "Error: " . $responseContent->Message . " - " . isset($responseContent->ValidationErrors) ?  json_encode($responseContent->ValidationErrors) :  $responseContent->Message;
              \Yii::error('[Payment Issue]' .$errorMessage, __METHOD__); // Log error faced by user
              throw new NotFoundHttpException(json_encode($errorMessage));
            }

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
            // $paymentRecord->order->changeOrderStatusToPending();
            return $this->redirect($paymentRecord->restaurant->restaurant_domain . '/payment-success/' . $paymentRecord->order_uuid . '/' . $paymentRecord->payment_uuid);
        } catch (\Exception $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    /**
     * Process callback from TAP payment gateway
     * @param string $tap_id
     * @return mixed
     */
    public function actionPaymentWebhook() {


      $charge_id = Yii::$app->request->getBodyParam("id");
      $status = Yii::$app->request->getBodyParam("status");
      $destinations = Yii::$app->request->getBodyParam("destinations");
      $response = Yii::$app->request->getBodyParam("response");
      $source = Yii::$app->request->getBodyParam("source");
      $reference = Yii::$app->request->getBodyParam("reference");


      $response_message  = null;

      if(isset($response))
        $response_message = $response['message'];


      $paymentRecord = Payment::updatePaymentStatus($charge_id, $status, $destinations, $source, $response_message);
      $paymentRecord->received_callback = true;
      $paymentRecord->save(false);

      if($paymentRecord){
        return [
            'operation' => 'success',
            'message' => 'Payment status has been updated successfully'
        ];
      }

    }

    /**
     * Get Order detail
     * WIll delete this fun after we merge with all stores
     * @param type $id
     * @param type $restaurant_uuid
     * @return type
     */
    public function actionOrderDetails($id, $restaurant_uuid) {

        $model = Order::find()
            ->andWhere(['order_uuid' => $id, 'restaurant_uuid' => $restaurant_uuid])
            ->one();

        if (!$model) {
            return [
                'operation' => 'error',
                'message' => 'Invalid order uuid'
            ];
        }

          unset($model['armada_qr_code_link']);
          unset($model['armada_delivery_code']);
          unset($model['mashkor_order_number']);
          unset($model['mashkor_tracking_link']);
          unset($model['mashkor_driver_name']);
          unset($model['mashkor_driver_phone']);
          unset($model['mashkor_order_status']);
          unset($model['armada_tracking_link']);
          unset($model['reminder_sent']);
          unset($model['sms_sent']);
          unset($model['items_has_been_restocked']);
          unset($model['subtotal_before_refund']);
          unset($model['total_price_before_refund']);


        if(isset($model['payment'])){
          unset($model['payment']['payment_uuid']);
          unset($model['payment']['payment_net_amount']);
          unset($model['payment']['restaurant_uuid']);
          unset($model['payment']['payment_gateway_fee']);
          unset($model['payment']['plugn_fee']);
          unset($model['payment']['partner_fee']);
        }

        return [
            'operation' => 'success',
            'body' => $model
        ];
    }

    /**
     * Get Order detail
     * @param type $id
     * @param type $restaurant_uuid
     * @return type
     */
    // public function actionGetOrderDetails($id, $restaurant_uuid) {
    //     $model = Order::find()->where(['order_uuid' => $id, 'restaurant_uuid' => $restaurant_uuid])->one();
    //
    //
    //     if (!$model) {
    //         return [
    //             'operation' => 'error',
    //             'message' => 'Invalid order uuid'
    //         ];
    //     }
    //
    //     return [
    //         'operation' => 'success',
    //         'body' => $model
    //     ];
    // }

    /**
     * Whether is valid promo code or no
     */
    public function actionApplyBankDiscount() {
        $restaurant_uuid = Yii::$app->request->get("restaurant_uuid");
        $phone_number = Yii::$app->request->get("phone_number");
        $bank_name = Yii::$app->request->get("bank_name");

        $bank_discount_model = BankDiscount::find()
                ->innerJoin('bank', 'bank.bank_id = bank_discount.bank_id')
                ->andWhere(['bank.bank_name' => $bank_name])
                ->andWhere(['restaurant_uuid' => $restaurant_uuid])
                ->andWhere(['bank_discount_status' => BankDiscount::BANK_DISCOUNT_STATUS_ACTIVE])
                ->one();

        if ($bank_discount_model && $bank_discount_model->isValid($phone_number)) {
            return [
                'operation' => 'success',
                'bank_discount' => $bank_discount_model
            ];
        }

        return [
            'operation' => 'error',
            'message' => 'Bank Discount is invalid or expired'
        ];
    }

    /**
     * Whether is valid promo code or no
     */
    public function actionApplyPromoCode() {

        $restaurant_uuid = Yii::$app->request->get("restaurant_uuid");
        $phone_number = Yii::$app->request->get("phone_number");
        $code = Yii::$app->request->get("code");

        $voucher = Voucher::find()->where([
            'restaurant_uuid' => $restaurant_uuid,
            'code' => $code,
            'voucher_status' => Voucher::VOUCHER_STATUS_ACTIVE
        ])->one();

        if ($voucher && $voucher->isValid($phone_number)) {
            return [
                'operation' => 'success',
                'voucher' => $voucher
            ];
        }

        return [
            'operation' => 'error',
            'message' => 'Voucher code is invalid or expired'
        ];
    }

    /**
     * CheckPendingOrders of type boolean and we want to return
     * True if there are pending  orders , false if these isn't any
     * @param type $restaurantUuid
     * @return boolean
     */
    public function actionCheckPendingOrders($restaurant_uuid) {
        return Order::find()
                        ->andWhere([
                            'restaurant_uuid' => $restaurant_uuid,
                            'order_status' => Order::STATUS_PENDING
                        ])->exists();
    }

    /**
     * Update order status
     */
    public function actionUpdateMashkorOrderStatus() {

        $mashkor_order_number = Yii::$app->request->getBodyParam("order_number");
        $mashkor_secret_token = Yii::$app->request->getBodyParam("webhook_token");

        if(!$mashkor_order_number){
          return [
              'operation' => 'error',
              'message' => 'Invalid order number',
          ];
        }

        if ($mashkor_secret_token === '2125bf59e5af2b8c8b5e8b3b19f13e1221') {

          $order_model = Order::find()
                        ->where(['mashkor_order_number' => $mashkor_order_number])
                        ->andWhere(['not', ['mashkor_order_number' => null]])
                        ->andWhere(['not', ['mashkor_tracking_link' => null]])
                        ->andWhere(['not', ['mashkor_driver_name' => null]])
                        ->one();

          if(  $order_model ) {

            $order_model->mashkor_driver_name = Yii::$app->request->getBodyParam("driver_name");
            $order_model->mashkor_driver_phone = Yii::$app->request->getBodyParam("driver_phone");
            $order_model->mashkor_tracking_link = Yii::$app->request->getBodyParam("tracking_link");
            $order_model->mashkor_order_status = Yii::$app->request->getBodyParam("order_status");

            if( $order_model->mashkor_order_status == Order::MASHKOR_ORDER_STATUS_IN_DELIVERY ) // In delivery
                $order_model->order_status = Order::STATUS_OUT_FOR_DELIVERY;

            if( $order_model->mashkor_order_status == Order::MASHKOR_ORDER_STATUS_DELIVERED ) // Delivered
                $order_model->order_status = Order::STATUS_COMPLETE;

            if ($order_model->save()) {
                return [
                    'operation' => 'success'
                ];
            } else {

              Yii::error('[Mashkor (Webhook): Error while changing order status ]' . json_encode($order_model->getErrors()), __METHOD__);


              return [
                  'operation' => 'error',
                  'message' => $order_model->getErrors(),
              ];
            }

          } else {

            Yii::error('[Mashkor (Webhook): Error while changing order status ]' . json_encode($order_model->getErrors()), __METHOD__);


            return [
                'operation' => 'error',
                'message' => 'Invalid Order id',
            ];
          }

        } else {

          Yii::error('[Mashkor (Webhook): Error while changing order status ]' . json_encode($order_model->getErrors()), __METHOD__);


          return [
              'operation' => 'error',
              'message' => 'Failed to authorize the request.',
          ];
        }

    }

    /**
     * Update order status
     */
    public function actionUpdateArmadaOrderStatus() {

         $armada_delivery_code = Yii::$app->request->getBodyParam("code");

         if(!$armada_delivery_code ){
           return [
               'operation' => 'error',
               'message' => 'Invalid Delivery code',
           ];
         }

          $order_model = Order::find()
                        ->where(['armada_delivery_code' => $armada_delivery_code])
                        ->andWhere(['not', ['armada_delivery_code' => null]])
                        ->andWhere(['not', ['armada_qr_code_link' => null]])
                        ->andWhere(['not', ['armada_tracking_link' => null]])
                        ->one();

          if($order_model) {

            $order_model->armada_order_status = Yii::$app->request->getBodyParam("orderStatus");

            if( $order_model->armada_order_status == 'en_route' ) // In delivery
                $order_model->order_status = Order::STATUS_OUT_FOR_DELIVERY;

            else if( $order_model->armada_order_status == 'completed' ) // Delivered
                $order_model->order_status = Order::STATUS_COMPLETE;

            if ($order_model->save())
            {
                return [
                    'operation' => 'success'
                ];
            }
            else
            {
              Yii::error('[Armada (Webhook): Error while changing order status ]' . json_encode($order_model->getErrors()), __METHOD__);

              return [
                  'operation' => 'error',
                  'message' => $order_model->getErrors(),
              ];
            }

          } else {

            Yii::error('[Armada (Webhook): Error while changing order status ]', __METHOD__);

            return [
                'operation' => 'error',
                'message' => 'Invalid Delivery code',
            ];
          }
    }
}
