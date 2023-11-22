<?php

namespace api\modules\v2\controllers;

use agent\models\PaymentMethod;
use common\models\PaymentFailed;
use kartik\mpdf\Pdf;
use Yii;
use yii\db\Expression;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use common\models\Voucher;
use common\models\Currency;
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
use yii\web\ForbiddenHttpException;


class OrderController extends Controller
{
    public function behaviors()
    {
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
    public function actions()
    {
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
     * initialize order without payment details
     * @param $id
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\db\Exception
     */
    public function actionInitOrder($id)
    {
        $restaurant = Restaurant::findOne($id);

        if (!$restaurant) {
            return [
                'operation' => 'error',
                'message' => 'Invalid Store',
                'code' => 1
            ];
        }

        $transaction = Yii::$app->db->beginTransaction();

        $order = new Order();

        $order->setScenario(Order::SCENARIO_INIT_ORDER);

        //as we will calculate after items get saved
        $order->total_price = 0;
        $order->subtotal = 0;
        $order->restaurant_uuid = $restaurant->restaurant_uuid;

        //Save Customer Info
        $order->utm_uuid = Yii::$app->request->getBodyParam("utm_uuid");

        if (Yii::$app->user->isGuest)
        {
            $order->customer_name = Yii::$app->request->getBodyParam("customer_name");
            $order->customer_phone_number = str_replace(' ', '', strval(Yii::$app->request->getBodyParam("phone_number")));
            $order->customer_phone_country_code = Yii::$app->request->getBodyParam("country_code") ? Yii::$app->request->getBodyParam("country_code") : 965;
            $order->customer_email = Yii::$app->request->getBodyParam("email"); //optional
        }
        else
        {
            $customer = Yii::$app->user->identity;

            $order->customer_name = $customer->customer_name;
            $order->customer_phone_number = $customer->customer_phone_number;
            $order->customer_phone_country_code = $customer->country_code;
            $order->customer_email =  $customer->customer_email;
            $order->customer_id = Yii::$app->user->getId();
        }

        if ($order->restaurant_uuid == 'rest_fe5b6a72-18a7-11ec-973b-069e9504599a') {
            if (Yii::$app->request->getBodyParam("civil_id"))
                $order->civil_id = Yii::$app->request->getBodyParam("civil_id");
            if (Yii::$app->request->getBodyParam("section"))
                $order->section = Yii::$app->request->getBodyParam("section");
            if (Yii::$app->request->getBodyParam("class"))
                $order->class = Yii::$app->request->getBodyParam("class");
        }

        //payment method

        //$order->payment_method_id = Yii::$app->request->getBodyParam("payment_method_id");
        $order->order_mode = Yii::$app->request->getBodyParam("order_mode");
        $order->currency_code = Yii::$app->currency->getCode();

        //Preorder
        $order->is_order_scheduled = Yii::$app->request->getBodyParam("is_order_scheduled") ?
            Yii::$app->request->getBodyParam("is_order_scheduled") : 0;

        //Apply promo code

        $order->voucher_id = Yii::$app->request->getBodyParam("voucher_id");

        //if the order mode = 1 => Delivery
        if ($order->order_mode == Order::ORDER_MODE_DELIVERY) {

                $city = Yii::$app->request->getBodyParam("city");

                if($city && isset($city['city_id'])) {
                    $order->city = Yii::$app->language == "ar" && !empty($city['city_name_ar']) ? $city['city_name_ar']:  $city['city_name'];
                } else {
                    $order->city = $city;
                }

                $order->address_1 = Yii::$app->request->getBodyParam('address_1');
                $order->address_2 = Yii::$app->request->getBodyParam('address_2');
                $order->postalcode = Yii::$app->request->getBodyParam('postal_code');

                $order->delivery_zone_id = Yii::$app->request->getBodyParam("delivery_zone_id");
                $order->shipping_country_id = Yii::$app->request->getBodyParam("country_id");
                $order->area_id = Yii::$app->request->getBodyParam("area_id");
                $order->unit_type = Yii::$app->request->getBodyParam("unit_type");
                $order->block = Yii::$app->request->getBodyParam("block");
                $order->street = Yii::$app->request->getBodyParam("street");
                $order->avenue = Yii::$app->request->getBodyParam("avenue"); //optional
                $order->house_number = Yii::$app->request->getBodyParam("house_number");

                if ((strtolower($order->unit_type) == Order::UNIT_TYPE_APARTMENT || strtolower($order->unit_type) == Order::UNIT_TYPE_OFFICE))
                    $order->floor = Yii::$app->request->getBodyParam("floor");

                if (strtolower($order->unit_type) == Order::UNIT_TYPE_APARTMENT)
                    $order->apartment = Yii::$app->request->getBodyParam("apartment");

                if (strtolower($order->unit_type) == Order::UNIT_TYPE_OFFICE)
                    $order->office = Yii::$app->request->getBodyParam("office");

            $order->special_directions = Yii::$app->request->getBodyParam("special_directions"); //optional

            if (Yii::$app->request->getBodyParam("deliver_location_latitude"))
                $order->latitude = Yii::$app->request->getBodyParam("deliver_location_latitude"); //optional
            if (Yii::$app->request->getBodyParam("deliver_location_longitude"))
                $order->longitude = Yii::$app->request->getBodyParam("deliver_location_longitude"); //optional

            //Preorder
            if ($order->is_order_scheduled != null && $order->is_order_scheduled == true && $restaurant->schedule_order) {
                $order->scheduled_time_start_from = date("Y-m-d H:i:s", strtotime(Yii::$app->request->getBodyParam("scheduled_time_start_from")));
                $order->scheduled_time_to = date("Y-m-d H:i:s", strtotime(Yii::$app->request->getBodyParam("scheduled_time_to")));
            }
        } else if ($order->order_mode == Order::ORDER_MODE_PICK_UP) {
            $order->pickup_location_id = Yii::$app->request->getBodyParam("business_location_id");
        }

        $order->order_instruction = Yii::$app->request->getBodyParam("order_instruction");

        if (!$order->save()) {

            $transaction->rollBack();

            return [
                'operation' => 'error',
                'message' => $order->getErrors(),
                'code' => 2
            ];
        }

        if ($order->restaurant->enable_gift_message) {
            //save gift message
            $order->sender_name = Yii::$app->request->getBodyParam("sender_name");
            $order->recipient_name = Yii::$app->request->getBodyParam("recipient_name");
            $order->recipient_phone_number = Yii::$app->request->getBodyParam("recipient_phone_number");
            $order->gift_message = Yii::$app->request->getBodyParam("gift_message");
        }

        $items = Yii::$app->request->getBodyParam("items");

        if (!$items) {
            $transaction->rollBack();
            return [
                'operation' => 'error',
                'message' => 'Item not available.',
                'code' => 3
            ];
        }

        foreach ($items as $item) {

            //Save items to the above order
            $orderItem = new OrderItem;
            $orderItem->order_uuid = $order->order_uuid;
            $orderItem->restaurant_uuid = $order->restaurant_uuid;
            $orderItem->item_uuid = $item["item_uuid"];
            $orderItem->item_variant_uuid = isset($item["item_variant_uuid"]) ? $item["item_variant_uuid"] : null;
            $orderItem->qty = (int)$item["qty"];

            //optional field
            if (array_key_exists("customer_instructions", $item) && $item["customer_instructions"] != null)
                $orderItem->customer_instruction = $item["customer_instructions"];

            if (!$orderItem->save()) {
                $transaction->rollBack();
                return [
                    'operation' => 'error',
                    'message' => $orderItem->getErrors(),
                    'code' => 4
                ];
            }

            if (array_key_exists('extraOptions', $item)) {

                $extraOptionsArray = $item['extraOptions'];

                if (isset($extraOptionsArray) && count($extraOptionsArray) > 0) {

                    foreach ($extraOptionsArray as $key => $extraOption) {

                        $orderItemExtraOption = new OrderItemExtraOption;
                        $orderItemExtraOption->order_item_id = $orderItem->order_item_id;
                        $orderItemExtraOption->extra_option_name = isset($extraOption['extra_option_name']) ? $extraOption['extra_option_name'] : null;
                        $orderItemExtraOption->extra_option_name_ar = isset($extraOption['extra_option_name_ar']) ? $extraOption['extra_option_name_ar'] : null;

                        $orderItemExtraOption->extra_option_id = isset($extraOption['extra_option_id']) ? $extraOption['extra_option_id'] : null;
                        $orderItemExtraOption->option_id = isset($extraOption['option_id']) ? $extraOption['option_id'] : null;
                        $orderItemExtraOption->qty = (int)$item["qty"];

                        if (!$orderItemExtraOption->save()) {
                            $transaction->rollBack();
                            return [
                                'operation' => 'error',
                                'message' => $orderItemExtraOption->getErrors(),
                                'code' => 5
                            ];
                        }
                    }
                }
            }
        }

        if (!$order->is_order_scheduled && !$restaurant->isOpen()) {

            $transaction->rollBack();

            return [
                'operation' => 'error',
                'message' => $restaurant->name . ' is currently closed and is not accepting orders at this time',
                'code' => 6
            ];
        }

        if (!$order->updateOrderTotalPrice()) {
            $transaction->rollBack();
            return [
                'operation' => 'error',
                'message' => $order->getErrors(),
                'code' => 7
            ];
        }

        /**
         * payment method should be free checkout if total is zero
         */
        if ($order->total_price == 0) {

            $freeCheckout = $order->restaurant->getPaymentMethods()
                ->andWhere(['payment_method_code' => PaymentMethod::CODE_FREE_CHECKOUT])
                ->one();

            if (!$freeCheckout) {

                $transaction->rollBack();

                return [
                    'operation' => 'error',
                    'message' => "Free checkout not enabled on this store!",
                    'code' => 8
                ];

            } else if ($order->payment_method_id && $order->payment_method_id != $freeCheckout->payment_method_id) {

                $transaction->rollBack();

                return [
                    'operation' => 'error',
                    'message' => "Invalid payment method for free order!",
                    'code' => 9
                ];
            }
        }

        if ($order->order_mode == Order::ORDER_MODE_DELIVERY && $order->subtotal < $order->deliveryZone->min_charge) {

            $transaction->rollBack();

            return [
                'operation' => 'error',
                'message' => 'Minimum order amount ' . Yii::$app->formatter->asCurrency($order->deliveryZone->min_charge,
                        $order->currency->code, [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => $order->currency->decimal_place]),
                'code' => 10
            ];
        }

        $bank_name = Yii::$app->request->getBodyParam("bank_name");

        if ($bank_name) {

            $bank_discount_model = BankDiscount::find()
                ->innerJoin('bank', 'bank.bank_id = bank_discount.bank_id')
                ->andWhere(['bank.bank_name' => $bank_name])
                ->andWhere(['restaurant_uuid' => $order->restaurant_uuid])
                ->andWhere(['<=', 'minimum_order_amount', $order->total_price])
                ->one();

            if ($bank_discount_model) {

                if ($bank_discount_model->isValid($order->customer_phone_number)) {
                    $customerBankDiscount = new CustomerBankDiscount();
                    $customerBankDiscount->customer_id = $order->customer_id;
                    $customerBankDiscount->bank_discount_id = $bank_discount_model->bank_discount_id;
                    $customerBankDiscount->save();
                }

                $order->bank_discount_id = $bank_discount_model->bank_discount_id;

                if (!$order->updateOrderTotalPrice()) {
                    return [
                        'operation' => 'error',
                        'message' => $order->getErrors(),
                        'code' => 14
                    ];
                }
            }
        }

        $transaction->commit();

        //for https://pogi.sentry.io/issues/3889482226/?project=5220572&query=is%3Aunresolved&referrer=issue-stream&stream_index=0
        
        \common\models\Restaurant::updateAll([
            'last_order_at' => new Expression('NOW()'),
            'total_orders' => $restaurant->total_orders + 1
        ], [
            'restaurant_uuid' => $restaurant->restaurant_uuid
        ]);

        if(YII_ENV == 'prod') {

            Yii::$app->eventManager->track('Order Initiated', $order->attributes,
                null,
                $restaurant->restaurant_uuid
            );
        }

        return [
            'operation' => 'success',
            'order' => $order
        ];
    }

    /**
     * Place an order
     */
    public function actionPlaceAnOrder($id)
    {
        $order_uuid = Yii::$app->request->getBodyParam('order_uuid');

        $restaurant = Restaurant::findOne($id);

        if ($order_uuid) {
            $order = $this->findModel($order_uuid);
        } else {
            $response = $this->actionInitOrder($id);

            if ($response['operation'] == 'error') {
                return $response;
            }

            $order = $response['order'];
        }

        //Apply promo code

        $order->voucher_id = Yii::$app->request->getBodyParam("voucher_id");

        //bank discount

        $order->payment_method_id = Yii::$app->request->getBodyParam("payment_method_id");

        $order->order_instruction = Yii::$app->request->getBodyParam("order_instruction");

        //save gift message
        $order->sender_name = Yii::$app->request->getBodyParam("sender_name");
        $order->recipient_name = Yii::$app->request->getBodyParam("recipient_name");
        $order->recipient_phone_number = Yii::$app->request->getBodyParam("recipient_phone_number");
        $order->gift_message = Yii::$app->request->getBodyParam("gift_message");

        if ($order->voucher_id) {
            $order->calculateOrderTotalPrice();//update total based on voucher
        }

        if (!$order->save()) {
            return [
                'operation' => 'error',
                'message' => $order->getErrors(),
                'code' => 2
            ];
        }

        if(!$order->paymentMethod)
        {
            return [
                'operation' => 'error',
                'message' => "Missing payment method",
                'code' => 2
            ];
        }

        //if payment method not cash redirect customer to payment gateway

        if (in_array($order->paymentMethod->payment_method_code, [
            PaymentMethod::CODE_FREE_CHECKOUT,
            PaymentMethod::CODE_CASH
        ])) {
            //pay by Cash or Free checkout
            //Change order status to pending
            $order->changeOrderStatusToPending();
            $order->sendPaymentConfirmationEmail();

            Yii::info("[" . $order->restaurant->name . ": " . $order->customer_name . " has placed an order for " .
                Yii::$app->formatter->asCurrency($order->total, $order->currency->code, [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => $order->currency->decimal_place]) . '] ' . 'Paid with ' . $order->payment_method_name, __METHOD__);

            return [
                'operation' => 'success',
                'order_uuid' => $order->order_uuid,
                'message' => 'Order created successfully'
            ];
        }

        if (empty($order->paymentMethod->source_id)) {
            $this->redirect(['payment/' . strtolower($order->paymentMethod->payment_method_code), 'order_uuid' => $order->order_uuid]);
        }

        // Create new payment record
        $payment = new Payment;
        $payment->restaurant_uuid = $restaurant->restaurant_uuid;

        $payment->customer_id = $order->customer->customer_id; //customer id
        $payment->order_uuid = $order->order_uuid;
        $payment->payment_amount_charged = $order->total;
        $payment->payment_current_status = "Redirected to payment gateway";
        $payment->is_sandbox = $restaurant->is_sandbox;

        if ($restaurant->is_tap_enable) {

            $payment->payment_mode = $order->paymentMethod->source_id;
            $payment->payment_gateway_name = 'tap';

            if (
                $payment->payment_mode == TapPayments::GATEWAY_VISA_MASTERCARD &&
                Yii::$app->request->getBodyParam("payment_token") &&
                Yii::$app->request->getBodyParam("bank_name")
            ) {

                Yii::$app->tapPayments->setApiKeys(
                    $order->restaurant->live_api_key,
                    $order->restaurant->test_api_key,
                    $payment->is_sandbox
                );

                $response = Yii::$app->tapPayments->retrieveToken(Yii::$app->request->getBodyParam("payment_token"));

                $responseContent = json_decode($response->content);

                // Validate that theres no error from TAP gateway

                if (isset($responseContent->status) && $responseContent->status == "fail") {

                    return [
                        'operation' => 'error',
                        'message' => 'Invalid Token ID',
                        'code' => 11
                    ];

                } else if (isset($responseContent->id) && $responseContent->id) {
                    $payment->payment_token = Yii::$app->request->getBodyParam("payment_token");
                }

            }

            if (!$payment->save()) {
                return [
                    'operation' => 'error',
                    'message' => $payment->getErrors(),
                    'code' => 13
                ];
            }

//Update payment_uuid in order
            $order->payment_uuid = $payment->payment_uuid;
            $order->save(false);

            Yii::info("[" . $restaurant->name . ": Payment Attempt Started] " . $order->customer_name . ' start attempting making a payment ' .
                Yii::$app->formatter->asCurrency($order->total, $order->currency->code,
                    [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => $order->currency->decimal_place]), __METHOD__);

// Redirect to payment gateway

            Yii::$app->tapPayments->setApiKeys(
                $order->restaurant->live_api_key,
                $order->restaurant->test_api_key,
                $order->restaurant->is_sandbox
            );

            $response = Yii::$app->tapPayments->createCharge(
                $order->currency->code,
                "Order placed from: " . $order->customer_name, // Description
                $order->restaurant->name, //Statement Desc.
                $payment->payment_uuid, // Reference
                $order->total,
                $order->customer_name,
                $order->customer_email,
                $order->customer_phone_country_code,
                $order->customer_phone_number,
                $order->restaurant->platform_fee,
                Url::to(['order/callback'], true),
                Url::to(['order/payment-webhook'], true),
                $order->paymentMethod->source_id == TapPayments::GATEWAY_VISA_MASTERCARD && $payment->payment_token ?
                    $payment->payment_token : $order->paymentMethod->source_id,
                $order->restaurant->warehouse_fee,
                $order->restaurant->warehouse_delivery_charges,
                $order->area_id ? $order->area->country->country_name : '',
                $order->restaurant_uuid
            );

            $responseContent = json_decode($response->content);

// Validate that theres no error from TAP gateway
            if (isset($responseContent->errors)) {

                // Yii::error($responseContent, 'application');

                $errorMessage = "Error: " . $responseContent->errors[0]->code . " - " . $responseContent->errors[0]->description;

                /*$requestBody = [
                    $order->currency->code,
                    "Order placed from: " . $order->customer_name, // Description
                    $order->restaurant->name, //Statement Desc.
                    $payment->payment_uuid, // Reference
                    $order->total,
                    $order->customer_name,
                    $order->customer_email,
                    $order->customer_phone_country_code,
                    $order->customer_phone_number,
                    $order->restaurant->platform_fee,
                    Url::to(['order/callback'], true),
                    Url::to(['order/payment-webhook'], true),
                    $order->paymentMethod->source_id == TapPayments::GATEWAY_VISA_MASTERCARD &&
                    $payment->payment_token ? $payment->payment_token : $order->paymentMethod->source_id,
                    $order->restaurant->warehouse_fee,
                    $order->restaurant->warehouse_delivery_charges,
                    $order->area_id ? $order->area->country->country_name : ''
                ];*/

                Yii::error("Order #" . $order->order_uuid . " Error: " . $responseContent->errors[0]->code . " - " . $responseContent->errors[0]->description, 'application');

                $paymentFailed = new PaymentFailed();
                $paymentFailed->payment_uuid = $payment->payment_uuid;
                $paymentFailed->customer_id = $order->customer_id;
                $paymentFailed->order_uuid = $order->order_uuid;
                $paymentFailed->response = print_r($responseContent->errors, true);
                $paymentFailed->save();

                return [
                    'operation' => 'error',
                    'message' => $errorMessage,
                    //'rerquestSent' => $requestBody,
                    'code' => 15,
                    'errors' => $responseContent->errors
                ];
            }

            if ($responseContent->id) {

                $chargeId = $responseContent->id;

                $redirectUrl = isset($responseContent->transaction->url)?
                    $responseContent->transaction->url: null;

                $payment->payment_gateway_transaction_id = $chargeId;

                if (!$payment->save(false)) {

                    Yii::error("Order #" . $order->order_uuid . " Error: " . print_r($payment->getErrors(), true), 'application');

                    return [
                        'operation' => 'error',
                        'message' => $payment->getErrors(),
                        'code' => 16
                    ];
                }
            } else {

                Yii::error("Order #" . $order->order_uuid . " Error: Payment Issue > Charge id is missing",
                    'application');

                return [
                    'operation' => 'error',
                    'message' => 'Payment Issue > Charge id is missing',
                    'code' => 17
                ];
            }

            if(!$redirectUrl) {
                $redirectUrl = Url::to(['order/callback', 'tap_id' => $payment->payment_gateway_transaction_id]);
            }

            return [
                'operation' => 'redirecting',
                'is_sandbox' => $order->restaurant->is_sandbox,
                'redirectUrl' => $redirectUrl,
            ];

        } else if ($restaurant->is_myfatoorah_enable) {

            $payment->payment_gateway_name = 'myfatoorah';
            $payment->payment_mode = $order->paymentMethod->payment_method_name;

            if (!$payment->save()) {

                return [
                    'operation' => 'error',
                    'message' => $payment->getErrors(),
                    'code' => 19
                ];
            }

            //Update payment_uuid in order
            $order->payment_uuid = $payment->payment_uuid;
            $order->save(false);

            Yii::info("[" . $restaurant->name . ": Payment Attempt Started] " . $order->customer_name . ' start attempting making a payment ' .
                Yii::$app->formatter->asCurrency($order->total, $order->currency->code, [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => $order->currency->decimal_place]), __METHOD__);

            Yii::$app->myFatoorahPayment->setApiKeys($order->currency->code, $order->restaurant->is_sandbox);

            $initiatePayment = Yii::$app->myFatoorahPayment->initiatePayment($order->total, $order->currency->code);

            $initiatePaymentResponse = json_decode($initiatePayment->content);

            if (!$initiatePaymentResponse->IsSuccess) {

                $errorMessage = "Error: " . $initiatePaymentResponse->Message . " - " . isset($responseContent->ValidationErrors) ?
                    json_encode($initiatePaymentResponse->ValidationErrors) : $initiatePaymentResponse->Message;

                $paymentFailed = new PaymentFailed();
                $paymentFailed->payment_uuid = $payment->payment_uuid;
                $paymentFailed->customer_id = $order->customer_id;
                $paymentFailed->order_uuid = $order->order_uuid;
                $paymentFailed->response = $errorMessage;
                $paymentFailed->save();

                return [
                    'operation' => 'error',
                    'message' => $errorMessage,
                    'code' => 21
                ];
            }

            $paymentMethodId = null;

            foreach ($initiatePaymentResponse->Data->PaymentMethods as $key => $paymentMethod) {
                if ($order->paymentMethod->payment_method_code == $paymentMethod->PaymentMethodCode) {
                    $paymentMethodId = $paymentMethod->PaymentMethodId;
                }
            }

            if ($paymentMethodId == null) {

                return [
                    'operation' => 'error',
                    'message' => 'This payment method is not supported',
                    'code' => 22
                ];
            }

            Yii::$app->myFatoorahPayment->setApiKeys($order->currency->code, $order->restaurant->is_sandbox);

            $response = Yii::$app->myFatoorahPayment->createCharge(
                $order->currency->code,
                $order->total,
                $order->customer_name,
                $order->customer_email,
                $order->customer_phone_country_code,
                $order->customer_phone_number,
                Url::to(['order/my-fatoorah-callback'], true),
                $order->order_uuid,
                $restaurant->supplierCode,
                $order->restaurant->platform_fee,
                $paymentMethodId,
                $order->paymentMethod->payment_method_code,
                $order->restaurant->warehouse_fee,
                $order->restaurant->warehouse_delivery_charges,
                $order->area_id ? $order->area->country->country_name : '',
                $order->restaurant_uuid
            );

            $responseContent = json_decode($response->content);

            if (!$responseContent->IsSuccess) {
                $errorMessage = "Error: " . $responseContent->Message . " - " . isset($responseContent->ValidationErrors) ? json_encode($responseContent->ValidationErrors[0]->Error) : $responseContent->Message;

                return [
                    'operation' => 'error',
                    'message' => $errorMessage,
                    'code' => 23
                ];
            }

            if ($responseContent->Data) {

                $invoiceId = $responseContent->Data->InvoiceId;
                $redirectUrl = $responseContent->Data->PaymentURL;
                $payment->payment_gateway_invoice_id = $invoiceId;

                if (!$payment->save(false)) {

                    $paymentFailed = new PaymentFailed();
                    $paymentFailed->payment_uuid = $payment->payment_uuid;
                    $paymentFailed->customer_id = $order->customer_id;
                    $paymentFailed->order_uuid = $order->order_uuid;
                    $paymentFailed->response = print_r($payment->getErrors(), true);
                    $paymentFailed->save();

                    return [
                        'operation' => 'error',
                        'message' => $payment->getErrors(),
                        'code' => 24
                    ];
                }
            } else {

                return [
                    'operation' => 'error',
                    'message' => $responseContent,
                    'code' => 25
                ];
            }

            return [
                'operation' => 'redirecting',
                'redirectUrl' => $redirectUrl,
                'orderUuid' => $order->order_uuid,
                'total_price' => $order->total
            ];

        } else {

            //Yii::error("Order #". $order->order_uuid . " Error: " . $e->getMessage(), 'application');

            return [
                'operation' => 'error',
                'message' => 'Sorry we are not able to process your request Please try again later'
            ];
        }

    }

    /**
     * Process callback from My Fatoorah payment gateway
     * @param string $paymentId
     * @return mixed
     */
    public function actionMyFatoorahCallback($paymentId)
    {
        //todo: what if not KWD or SAR

        //todo: setApiKeys with sandbox status?

        Yii::$app->myFatoorahPayment->setApiKeys('KWD');

        $response = Yii::$app->myFatoorahPayment->retrieveCharge($paymentId, 'PaymentId');

        $responseContent = json_decode($response->content);

        if (!$responseContent->IsSuccess) {
            Yii::$app->myFatoorahPayment->setApiKeys('SAR');

            $response = Yii::$app->myFatoorahPayment->retrieveCharge($paymentId, 'PaymentId');

            $responseContent = json_decode($response->content);
        }

        if ($responseContent->IsSuccess) {

            $paymentRecord = Payment::updatePaymentStatusFromMyFatoorah($responseContent->Data->InvoiceId);

            $paymentRecord->payment_gateway_transaction_id = $responseContent->Data->InvoiceTransactions[0]->TransactionId; //TransactionId
            $paymentRecord->payment_gateway_payment_id = $responseContent->Data->InvoiceTransactions[0]->PaymentId; //payment_gateway_transaction_id = PaymentId
            $paymentRecord->payment_gateway_order_id = $responseContent->Data->InvoiceTransactions[0]->ReferenceId;
            $paymentRecord->save(false);

            // Redirect back to app for Failed Payment
            if ($paymentRecord->payment_current_status != 'Paid' && $paymentRecord->payment_current_status != 'Succss' && $paymentRecord->payment_current_status != 'SUCCSS' && $paymentRecord->payment_current_status != 'SUCCESS') {
                $url = $paymentRecord->restaurant->restaurant_domain . '/payment-failed/' . $paymentRecord->order_uuid;

                return Yii::$app->getResponse()->redirect($url)->send(301);
            }

            //$this->actionDownloadInvoice($paymentRecord->order_uuid);

            // Redirect back to app

            $url = $paymentRecord->restaurant->restaurant_domain . '/payment-success/' . $paymentRecord->order_uuid . '/' . $paymentRecord->payment_uuid;

            return Yii::$app->getResponse()->redirect($url)->send(301);
        } else {
            $errorMessage = "Error: " . $responseContent->Message . " - " . isset($responseContent->ValidationErrors) ? json_encode($responseContent->ValidationErrors) : $responseContent->Message;

            \Yii::error('[Payment Issue]' . $errorMessage, __METHOD__); // Log error faced by user

            //throw new NotFoundHttpException(json_encode($errorMessage));

            $paymentRecord = \common\models\Payment::find()->where(['payment_gateway_invoice_id' => $responseContent->Data->InvoiceId])->one();

            $url = $paymentRecord->restaurant->restaurant_domain . '/payment-failed/' . $paymentRecord->order_uuid;

            return Yii::$app->getResponse()->redirect($url)->send(301);
        }
    }

    /**
     * Process callback from TAP payment gateway
     * @param string $tap_id
     * @return mixed
     */
    public function actionCallback($tap_id)
    {
        try {
            $paymentRecord = Payment::updatePaymentStatusFromTap($tap_id);
            $paymentRecord->received_callback = true;
            $paymentRecord->save(false);

            // Redirect back to app for failed Payment
            if ($paymentRecord->payment_current_status != 'CAPTURED') {
                $url = $paymentRecord->restaurant->restaurant_domain . '/payment-failed/' . $paymentRecord->order_uuid;

                return Yii::$app->getResponse()->redirect($url)->send(301);
            }

            //$this->actionDownloadInvoice($paymentRecord->order_uuid);

            // Redirect back to app
            // $paymentRecord->order->changeOrderStatusToPending();
            $url = $paymentRecord->restaurant->restaurant_domain . '/payment-success/' . $paymentRecord->order_uuid . '/' . $paymentRecord->payment_uuid;
//            return $this->redirect($paymentRecord->restaurant->restaurant_domain . '/payment-success/' . $paymentRecord->order_uuid . '/' . $paymentRecord->payment_uuid);

            return Yii::$app->getResponse()->redirect($url)->send(301);


        } catch (\Exception $e) {
            //todo: notify vendor/admin?

            Yii::error($e->getMessage());

            $paymentRecord = \common\models\Payment::findOne(['payment_gateway_transaction_id' => $tap_id]);

            $url = $paymentRecord->restaurant->restaurant_domain . '/payment-failed/' . $paymentRecord->order_uuid;

            return Yii::$app->getResponse()->redirect($url)->send(301);
        }
    }

    /**
     * Download invoice
     * @param $id
     * @return string
     * @throws \Mpdf\MpdfException
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDownloadInvoice($id)
    {
        $order = $this->findModel($id);

        // Item extra optn
        // $itemsExtraOpitons = new \yii\data\ActiveDataProvider([
        //     'query' => $order->getOrderItemExtraOptions()
        // ]);

        $this->layout = 'pdf';

        $defaultLogo = Url::to('@web/img/icon-128x128.png', true);

        $content = $this->render('invoice', [
            'order' => $order,
            'defaultLogo' => $defaultLogo,
            'bankDiscount' => $order->bank_discount,
            'voucherDiscount' => $order->voucher_discount,
        ]);

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        //echo  __DIR__ . '/../../../web/fonts/Nunito';
        //die();
        $pdf = new Pdf([
            'options' => [
                'defaultheaderline' => 0,  //for header
                'defaulfooterline' => 0,  //for footer
                'title' => 'Invoice #' . $order->order_uuid,
                'fontDir' => array_merge($fontDirs, [
                    __DIR__ . '/Nunito',
                    // __DIR__ . '/../../../web/fonts/Nunito/'
                ]),
                'fontdata' => array_merge($fontData, [
                    "Nunito" => [
                        'R' => 'Nunito-Regular.ttf',
                        'B' => 'Nunito-Bold.ttf',
                        'B' => 'Nunito-Italic.ttf',
                    ],
                    "NunitoSans" => [
                        'R' => 'NunitoSans-Regular.ttf',
                        'I' => "NunitoSans-Italic.ttf", // Italic  - OPTIONAL
                        'B' => 'NunitoSans-Bold.ttf' // Bold    - OPTIONAL
                    ],
                ])
            ],
            'defaultFont' => "Nunito",
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            'marginTop' => 5,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_FILE,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => [
                __DIR__ . '/../../../web/css/invoice.css',
                '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css'
            ]
            //Url::to('@web/css/invoice.css', true),
            //'@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
//            'methods' => [
//                'SetHeader'=>[$candidate->employeeId .'<br/>'. 'Prepared by '.Yii::$app->user->identity->staff_name],
//                'SetHeader'=>[$candidate->employeeId .'<br/>'. 'Prepared by Khalid'],
//            ]
        ]);

        header('Access-Control-Allow-Origin: *');

        $file = $pdf->render();

        return Yii::$app->response->sendFile($file, 'Invoide-' . $id . '.png');
    }

    /**
     * Process callback from TAP payment gateway
     * @param string $tap_id
     * @return mixed
     */
    public function actionPaymentWebhook()
    {
        $headers = Yii::$app->request->headers;
        $headerSignature = $headers->get('hashstring');

        $charge_id = Yii::$app->request->getBodyParam("id");
        $status = Yii::$app->request->getBodyParam("status");
        $amount = Yii::$app->request->getBodyParam("amount");
        $currency = Yii::$app->request->getBodyParam("currency");
        $reference = Yii::$app->request->getBodyParam("reference");
        $destinations = Yii::$app->request->getBodyParam("destinations");
        $response = Yii::$app->request->getBodyParam("response");
        $source = Yii::$app->request->getBodyParam("source");
        $transaction = Yii::$app->request->getBodyParam("transaction");
        $acquirer = Yii::$app->request->getBodyParam("acquirer");

        if ($currency_mode = Currency::find()->where(['code' => $currency])->one())
            $decimal_place = $currency_mode->decimal_place;
        else
            throw new ForbiddenHttpException('Invalid Currency code');

        if (isset($reference)) {
            $gateway_reference = $reference['gateway'];
            $payment_reference = $reference['payment'];
        }

        if (isset($transaction)) {
            $created = $transaction['created'];
        }

        $amountCharged = \Yii::$app->formatter->asDecimal($amount, $decimal_place);

        $toBeHashedString = 'x_id' . $charge_id . 'x_amount' . $amountCharged . 'x_currency' . $currency . 'x_gateway_reference' . $gateway_reference . 'x_payment_reference' . $payment_reference . 'x_status' . $status . 'x_created' . $created . '';

        $isValidSignature = true;

        //Check If Enabled Secret Key and If The header has request
        if ($headerSignature != null) {

            $response_message = null;

            if (isset($acquirer)) {
                if (
                    isset($acquirer['response']) &&
                    isset($acquirer['response']['message'])
                )
                    $response_message = $acquirer['response']['message'];
            } else {
                if (isset($response) && isset($response['message']))
                    $response_message = $response['message'];
            }

            $paymentRecord = Payment::updatePaymentStatus(
                $charge_id,
                $status,
                $destinations,
                $source,
                $reference,
                $response_message,
                $response);

            $isValidSignature = false;

            if (!$isValidSignature) {
                Yii::$app->tapPayments->setApiKeys(
                    $paymentRecord->restaurant->live_api_key,
                    $paymentRecord->restaurant->test_api_key,
                    $paymentRecord->is_sandbox
                );

                $isValidSignature = Yii::$app->tapPayments->checkTapSignature($toBeHashedString, $headerSignature);

                if (!$isValidSignature) {

                    //todo: notify vendor/admin?

                    //Yii::error('Invalid Signature', __METHOD__);

                    throw new ForbiddenHttpException('Invalid Signature');
                }
            }

            $paymentRecord->received_callback = true;
            $paymentRecord->save(false);

            if ($paymentRecord) {
                return [
                    'operation' => 'success',
                    'message' => 'Payment status has been updated successfully'
                ];
            }
        }
    }

    /**
     * list all orders
     * @return ActiveDataProvider
     */
    public function actionList()
    {
        $store_uuid = Yii::$app->request->get('restaurant_uuid');
        $phone_number = Yii::$app->request->get('phone_number');
        $email = Yii::$app->request->get('email');

        $query = Order::find()
            ->andWhere(['restaurant_uuid' => $store_uuid])
            /*->andWhere(
                [
                    'order_status' => [
                        Order::STATUS_PENDING,
                        Order::STATUS_ACCEPTED,
                        Order::STATUS_BEING_PREPARED
                    ]
                ]
            )*/
            ->andWhere(['order.is_deleted' => 0])
            ->orderBy(['order_created_at' => SORT_DESC]);

        if(!Yii::$app->user->isGuest) 
        {
            $query->andWhere(['customer_id' => Yii::$app->user->getId()]);
        } 
        else if($email || $phone_number) 
        {
                $query->andWhere([
                    'OR',
                    ['customer_email' => $email],
                    ['customer_phone_number' => $phone_number]
                ]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Get Order detail
     * WIll delete this fun after we merge with all stores
     * @param type $id
     * @param type $restaurant_uuid
     * @return type
     */
    public function actionOrderDetails($id, $restaurant_uuid)
    {
        $model = Order::find()
            ->andWhere(['order_uuid' => $id, 'restaurant_uuid' => $restaurant_uuid,
                'order.is_deleted' => 0])
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


        if (isset($model['payment'])) {
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
    public function actionApplyBankDiscount()
    {
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
    public function actionApplyPromoCode($order_uuid = null)
    {
        $restaurant_uuid = Yii::$app->request->get("restaurant_uuid");
        $phone_number = Yii::$app->request->get("phone_number");
        $code = Yii::$app->request->get("code");

        $voucher = Voucher::find()->where([
            'restaurant_uuid' => $restaurant_uuid,
            'code' => $code,
            'voucher_status' => Voucher::VOUCHER_STATUS_ACTIVE
        ])->one();

        if ($voucher && $voucher->isValid($phone_number)) {

            $order = null; 
            
            if($order_uuid) {

                $order = $this->findModel($order_uuid, $restaurant_uuid);

                //check order not contain discounted item

                $order->setScenario(Order::SCENARIO_APPLY_VOUCHER);

                $order->voucher_id = $voucher->voucher_id;

                $itemTotal = $order->calculateOrderItemsTotalPrice();

                if (
                    $order->order_status != \common\models\Order::STATUS_REFUNDED &&
                    $order->order_status != \common\models\Order::STATUS_PARTIALLY_REFUNDED
                ) {
                    $order->subtotal_before_refund = round($itemTotal, $order->currency->decimal_place);
                    $order->total_price_before_refund = round($itemTotal, $order->currency->decimal_place);
                }

                $order->subtotal = round($itemTotal, $order->currency->decimal_place);

                $order->total_price = round($order->calculateOrderTotalPrice($itemTotal), $order->currency->decimal_place);

                if(!$order->save()) {
                    return [
                        'operation' => 'error',
                        'message' => $order->errors
                    ];
                }
            }

            return [
                'operation' => 'success',
                'voucher' => $voucher,
                'order' => $order
            ];
        }

        return [
            'operation' => 'error',
            'message' => 'Voucher code is invalid or expired'
        ];
    }

    /**
     * update order instruction
     */
    public function actionInstruction($order_uuid = null)
    {
        $order_instruction = Yii::$app->request->getBodyParam('order_instruction');

        Order::updateAll(['order_instruction' => $order_instruction], [
            'order_uuid' => $order_uuid
        ]);

        return [
            'operation' => 'success',
        ];
    }

    /**
     * CheckPendingOrders of type boolean and we want to return
     * True if there are pending  orders , false if these isn't any
     * @param type $restaurantUuid
     * @return boolean
     */
    public function actionCheckPendingOrders($restaurant_uuid)
    {
        return Order::find()
            ->andWhere([
                'restaurant_uuid' => $restaurant_uuid,
                'order_status' => Order::STATUS_PENDING
            ])->exists();
    }

    /**
     * Update order status
     */
    public function actionUpdateMashkorOrderStatus()
    {

        $mashkor_order_number = Yii::$app->request->getBodyParam("order_number");
        $mashkor_secret_token = Yii::$app->request->getBodyParam("webhook_token");

        if (!$mashkor_order_number) {
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

            if ($order_model) {

                $order_model->setScenario(Order::SCENARIO_UPDATE_MASHKOR_STATUS);

                $order_model->mashkor_driver_name = Yii::$app->request->getBodyParam("driver_name");
                $order_model->mashkor_driver_phone = Yii::$app->request->getBodyParam("driver_phone");
                $order_model->mashkor_tracking_link = Yii::$app->request->getBodyParam("tracking_link");
                $order_model->mashkor_order_status = Yii::$app->request->getBodyParam("order_status");

                if ($order_model->mashkor_order_status == Order::MASHKOR_ORDER_STATUS_IN_DELIVERY) // In delivery
                    $order_model->order_status = Order::STATUS_OUT_FOR_DELIVERY;

                if ($order_model->mashkor_order_status == Order::MASHKOR_ORDER_STATUS_DELIVERED) // Delivered
                    $order_model->order_status = Order::STATUS_COMPLETE;

                if ($order_model->save()) {
                    return [
                        'operation' => 'success'
                    ];
                } else {

                    //Yii::error('[Mashkor (Webhook): Error while changing order status ]' . json_encode($order_model->getErrors()), __METHOD__);


                    return [
                        'operation' => 'error',
                        'message' => $order_model->getErrors(),
                    ];
                }

            } else {

                //Yii::error('[Mashkor (Webhook): Error while changing order status ]' . json_encode($order_model->getErrors()), __METHOD__);


                return [
                    'operation' => 'error',
                    'message' => 'Invalid Order id',
                ];
            }

        } else {

            //Yii::error('[Mashkor (Webhook): Error while changing order status ]' . json_encode($order_model->getErrors()), __METHOD__);


            return [
                'operation' => 'error',
                'message' => 'Failed to authorize the request.',
            ];
        }

    }

    /**
     * Update order status
     */
    public function actionUpdateArmadaOrderStatus()
    {
        $armada_delivery_code = Yii::$app->request->getBodyParam("code");

        if (!$armada_delivery_code) {
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

        if ($order_model) {

            $order_model->setScenario(Order::SCENARIO_UPDATE_ARMADA_STATUS);

            $order_model->armada_order_status = Yii::$app->request->getBodyParam("orderStatus");

            if ($order_model->armada_order_status == 'en_route') // In delivery
                $order_model->order_status = Order::STATUS_OUT_FOR_DELIVERY;

            else if ($order_model->armada_order_status == 'completed') // Delivered
                $order_model->order_status = Order::STATUS_COMPLETE;

            if ($order_model->save()) {
                return [
                    'operation' => 'success'
                ];
            } else {
                //Yii::error('[Armada (Webhook): Error while changing order status ]' . json_encode($order_model->getErrors()), __METHOD__);

                return [
                    'operation' => 'error',
                    'message' => $order_model->getErrors(),
                ];
            }

        } else {

            // Yii::error('[Armada (Webhook): Error while changing order status ]', __METHOD__);

            return [
                'operation' => 'error',
                'message' => 'Invalid Delivery code',
            ];
        }
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($order_uuid, $restaurant_uuid = null)
    {
        $query = Order::find()
            ->andWhere([
                'order_uuid' => $order_uuid
            ]);

        if($restaurant_uuid) {
            $query->andWhere(['restaurant_uuid' => $restaurant_uuid]);
        }

        $model = $query
            ->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
