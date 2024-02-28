<?php

namespace agent\modules\v1\controllers;


use agent\models\Item;
use agent\models\Refund;
use agent\models\RefundedItem;
use common\models\AreaDeliveryZone;
use common\models\Currency;
use common\models\Payment;
use common\models\PaymentMethod;
use common\models\RestaurantInvoice;
use common\models\shipping\Aramex;
use http\Exception\UnexpectedValueException;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use agent\models\Order;
use agent\models\OrderItem;
use agent\models\OrderItemExtraOption;
use yii\db\Expression;
use kartik\mpdf\Pdf;


class OrderController extends BaseController
{
    /**
     * list all orders where status = pending or being prepared
     * @param $type
     * @return ActiveDataProvider
     */
    public function actionLiveOrders()
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $query = Order::find()
            ->filterBusinessLocationIfManager($store->restaurant_uuid)
            ->andWhere(['restaurant_uuid' => $store->restaurant_uuid])
            ->andWhere(
                [
                    'order_status' => [
                        Order::STATUS_PENDING,
                        Order::STATUS_ACCEPTED,
                        Order::STATUS_BEING_PREPARED
                    ]
                ]
            )
            ->andWhere(['order.is_deleted' => 0])
            ->orderBy(['order_created_at' => SORT_DESC]);

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * list all order that has been completed order out for delivery
     * @param $type
     * @return ActiveDataProvider
     */
    public function actionArchiveOrders()
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $query = Order::find()
            ->filterBusinessLocationIfManager($store->restaurant_uuid)
            ->andWhere(['restaurant_uuid' => $store->restaurant_uuid])
            ->andWhere(
                [
                    'order_status' => [
                        Order::STATUS_OUT_FOR_DELIVERY,
                        Order::STATUS_COMPLETE,

                    ]
                ]
            )
            ->andWhere(['order.is_deleted' => 0])
            ->orderBy(['order_created_at' => SORT_DESC]);

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * list orders
     * @param $type
     * @return ActiveDataProvider
     */
    public function actionList($type = null)
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $keyword = Yii::$app->request->get('keyword');
        $order_uuid = Yii::$app->request->get('order_uuid');
        $phone = Yii::$app->request->get('phone');
        $status = Yii::$app->request->get('status');
        $customer = Yii::$app->request->get('customer');
        $date_range = Yii::$app->request->get('date_range');
        $customer_id = Yii::$app->request->get('customer_id');

        $query = Order::find()
            ->filterBusinessLocationIfManager($store->restaurant_uuid)
            ->andWhere(['restaurant_uuid' => $store->restaurant_uuid]);

        # as we already doing some search with keyword textbox so reusing that
        if ($order_uuid) {
            $keyword = $order_uuid;
        } else if ($phone) {
            $keyword = $phone;
        } else if ($customer) {
            $keyword = $customer;
        }

        // grid filtering conditions
        $query->orderBy(['order_created_at' => SORT_DESC]);

        if ($status == '11') {
            $query->liveOrders();
        } else if ($status == '12') {
            $query->archiveOrders();
        } else if ($status !== null) {
            $query->andFilterWhere(['order_status' => $status]);
        }

        if ($date_range) {
            $query->filterByCreatedDate($date_range);
        }

        if ($customer_id) {
            $query->andFilterWhere(['customer_id' => $customer_id]);
        }

        if ($keyword) {
            $query->filterByKeyword($keyword);
        }

        $query->andWhere(['restaurant_uuid' => $store->restaurant_uuid]);

        if ($type == 'active') {
            $query->andWhere(
                [
                    'order_status' => [
                        Order::STATUS_PENDING,
                        Order::STATUS_BEING_PREPARED,
                        Order::STATUS_OUT_FOR_DELIVERY,
                        Order::STATUS_COMPLETE,
                        Order::STATUS_ACCEPTED
                    ]
                ]
            );
        } else if ($type == 'pending') {
            $query->andWhere(['order_status' => Order::STATUS_PENDING]);
        } else if ($type == 'abandoned') {
            $query->andWhere(['order_status' => Order::STATUS_ABANDONED_CHECKOUT]);
        } else if ($type == 'draft') {
            $query->andWhere(['order_status' => Order::STATUS_DRAFT]);
        }
        $query->andWhere(['order.is_deleted' => 0]);

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * get order stats
     * @return ActiveDataProvider
     */
    public function actionStats()
    {
        $customer_id = Yii::$app->request->get('customer_id');
        $keyword = Yii::$app->request->get('query');

        $store = Yii::$app->accountManager->getManagedAccount();

        $response = [];

        if ($customer_id) {
            $response['allCount'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->notDeleted()
                ->andFilterWhere([
                    'customer_id' => $customer_id
                ])
                ->count();
        } else {
            $response['allCount'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->count();
        }

        if ($customer_id) {
            $response['draftCount'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->notDeleted()
                ->andFilterWhere([
                    'customer_id' => $customer_id,
                    'order_status' => Order::STATUS_DRAFT
                ])
                ->count();
        } else {
            $response['draftCount'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->andFilterWhere(['order_status' => Order::STATUS_DRAFT])
                ->count();
        }

        if ($customer_id) {
            $response['acceptedCount'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->andFilterWhere([
                    'customer_id' => $customer_id,
                    'order_status' => Order::STATUS_ACCEPTED
                ])
                ->count();
        } else {
            $response['acceptedCount'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->andFilterWhere(['order_status' => Order::STATUS_ACCEPTED])
                ->count();
        }

        if ($customer_id) {
            $response['pendingCount'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->andFilterWhere([
                    'customer_id' => $customer_id,
                    'order_status' => Order::STATUS_PENDING
                ])
                ->count();
        } else {
            $response['pendingCount'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->andFilterWhere(['order_status' => Order::STATUS_PENDING])
                ->count();
        }

        if ($customer_id) {
            $response['preparedCount'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->andFilterWhere([
                    'customer_id' => $customer_id,
                    'order_status' => Order::STATUS_BEING_PREPARED
                ])
                ->count();
        } else {
            $response['preparedCount'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->andFilterWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                ->count();
        }

        if ($customer_id) {
            $response['outForDeliveryCount'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->andFilterWhere([
                    'customer_id' => $customer_id,
                    'order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                ->count();
        } else {
            $response['outForDeliveryCount'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->andFilterWhere([
                    'order_status' => Order::STATUS_OUT_FOR_DELIVERY
                ])
                ->count();
        }

        if ($customer_id) {
            $response['completeCount'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->andFilterWhere([
                    'customer_id' => $customer_id,
                    'order_status' => Order::STATUS_COMPLETE
                ])
                ->count();
        } else {
            $response['completeCount'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->andFilterWhere([
                    'order_status' => Order::STATUS_COMPLETE
                ])
                ->count();
        }

        if ($customer_id) {
            $response['canceledCount'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->andFilterWhere([
                    'customer_id' => $customer_id,
                    'order_status' => Order::STATUS_CANCELED
                ])
                ->count();
        } else {
            $response['canceledCount'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->andFilterWhere([
                    'order_status' => Order::STATUS_CANCELED
                ])
                ->count();
        }

        if ($customer_id) {
            $response['partialRefundedCount'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->andFilterWhere([
                    'customer_id' => $customer_id,
                    'order_status' => Order::STATUS_PARTIALLY_REFUNDED
                ])
                ->count();
        } else {
            $response['partialRefundedCount'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->andFilterWhere([
                    'order_status' => Order::STATUS_PARTIALLY_REFUNDED
                ])
                ->count();
        }

        if ($customer_id) {
            $response['refundedCount'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->andFilterWhere([
                    'customer_id' => $customer_id,
                    'order_status' => Order::STATUS_REFUNDED
                ])
                ->count();
        } else {
            $response['refundedCount'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->andFilterWhere([
                    'order_status' => Order::STATUS_REFUNDED
                ])
                ->count();
        }

        if ($customer_id) {
            $response['abandonedCount'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->andFilterWhere([
                    'customer_id' => $customer_id,
                    'order_status' => Order::STATUS_ABANDONED_CHECKOUT
                ])
                ->count();
        } else {
            $response['abandonedCount'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->andFilterWhere([
                    'order_status' => Order::STATUS_ABANDONED_CHECKOUT
                ])
                ->count();
        }

        if ($customer_id) {
            $response['liveOrders'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->andFilterWhere([
                    'customer_id' => $customer_id
                ])
                ->liveOrders()
                ->count();
        } else {
            $response['liveOrders'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->liveOrders()
                ->count();
        }

        if ($customer_id) {
            $response['archiveOrders'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->andFilterWhere([
                    'customer_id' => $customer_id
                ])
                ->archiveOrders()
                ->count();
        } else {
            $response['archiveOrders'] = $store->getOrders()
                ->filterByKeyword($keyword)
                ->notDeleted()
                ->filterBusinessLocationIfManager($store->restaurant_uuid)
                ->archiveOrders()
                ->count();
        }

        return $response;
    }

    /**
     * return pending order count
     * @return ActiveDataProvider
     */
    public function actionTotalPending()
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $totalPendingOrders = Order::find()
            ->notDeleted()
            ->filterBusinessLocationIfManager($store->restaurant_uuid)
            ->andWhere(['order_status' => Order::STATUS_PENDING])
            ->andWhere(['restaurant_uuid' => $store->restaurant_uuid])
            ->count();

        $latestPendingOrder = Order::find()
            ->notDeleted()
            ->filterBusinessLocationIfManager($store->restaurant_uuid)
            ->andWhere(['order_status' => Order::STATUS_PENDING])
            ->andWhere(['restaurant_uuid' => $store->restaurant_uuid])
            ->orderBy(['order_created_at' => SORT_DESC])
            ->one();

        $latestOrder = Order::find()
            ->notDeleted()
            ->filterBusinessLocationIfManager($store->restaurant_uuid)
            ->andWhere(['restaurant_uuid' => $store->restaurant_uuid])
            ->orderBy(['order_created_at' => SORT_DESC])
            ->one();

        $totalPendingInvoices = $store->getInvoices()
            ->locked() //->notPaid()
            ->count();

        /*$totalDraftInvoiceAmount = $store->getInvoices()
            ->andWhere(['invoice_status' => RestaurantInvoice::STATUS_UNPAID])
            ->sum('amount');*/

        return [
            'totalPendingOrders' => (int)$totalPendingOrders,
            'latestOrderId' => $latestOrder ? $latestOrder->order_uuid : null,
            'latestPendingOrderId' => $latestPendingOrder ? $latestPendingOrder->order_uuid : null,
            'totalPendingInvoices' => (int)$totalPendingInvoices,
            //'$totalDraftInvoiceAmount' => $totalDraftInvoiceAmount
        ];
    }

    /**
     * Place an order
     */
    public function actionPlaceAnOrder($store_uuid = null)
    {
        $area_id = Yii::$app->request->getBodyParam("area_id");
        $delivery_zone_id = Yii::$app->request->getBodyParam("delivery_zone_id");
        $country_id = Yii::$app->request->getBodyParam("country_id");

        $store = Yii::$app->accountManager->getManagedAccount($store_uuid);

        $order = new Order();

        $order->order_status = Order::STATUS_DRAFT;
        $order->restaurant_uuid = $store->restaurant_uuid;

        //Save Customer Info
        $order->utm_uuid = Yii::$app->request->getBodyParam("utm_uuid");

        $order->customer_name = Yii::$app->request->getBodyParam("customer_name");
        $order->customer_phone_number = str_replace(' ', '', strval(Yii::$app->request->getBodyParam("phone_number")));
        $order->customer_phone_country_code = Yii::$app->request->getBodyParam("country_code") ? Yii::$app->request->getBodyParam("country_code") : 965;
        $order->customer_email = Yii::$app->request->getBodyParam("email"); //optional

        $order->currency_code = Yii::$app->request->getBodyParam("currency_code");

        //payment method => cash
        $order->payment_method_id = 3;

        $order->order_mode = Yii::$app->request->getBodyParam("order_mode");

        //Delivery the order ASAP
        $order->is_order_scheduled = Yii::$app->request->getBodyParam("is_order_scheduled") ? Yii::$app->request->getBodyParam("is_order_scheduled") : 0;

        //Apply promo code
        if (Yii::$app->request->getBodyParam("voucher_id")) {
            $order->voucher_id = Yii::$app->request->getBodyParam("voucher_id");
        } else if (Yii::$app->request->getBodyParam("voucher_code")) {

            $voucherModel = $order->restaurant->getVouchers()
                ->andWhere(['code' => Yii::$app->request->getBodyParam("voucher_code")])
                ->one();

            if(!$voucherModel) {

                return [
                    'operation' => 'error',
                    'message' => Yii::t('app', "Invalid voucher code.")
                ];
            }

            $order->voucher_id = $voucherModel->voucher_id;
        }

        //if the order mode = 1 => Delivery

        if ($order->order_mode == Order::ORDER_MODE_DELIVERY) {

            //Deliver to Kuwait - GCC

            $area_delivery_zone = $store->getAreaDeliveryZones()
                ->andWhere(new Expression('area_id IS NULL OR area_id="' . $area_id . '"'))
                ->one();

            if (!$area_delivery_zone) {
                return [
                    'operation' => 'error',
                    'message' => Yii::t('app', "Store does not deliver to this delivery zone.")
                ];
            }

                $order->delivery_zone_id = $delivery_zone_id? $delivery_zone_id: $area_delivery_zone->delivery_zone_id;

                $order->area_id = $area_id;
                $order->unit_type = Yii::$app->request->getBodyParam("unit_type");
                $order->block = Yii::$app->request->getBodyParam("block");
                $order->street = Yii::$app->request->getBodyParam("street");
                $order->avenue = Yii::$app->request->getBodyParam("avenue"); //optional
                $order->house_number = Yii::$app->request->getBodyParam("house_number");
                $order->building = Yii::$app->request->getBodyParam("building");
                $order->floor = Yii::$app->request->getBodyParam("floor");
                $order->apartment = Yii::$app->request->getBodyParam("apartment");
                $order->office = Yii::$app->request->getBodyParam("office");
                $order->shipping_country_id = $country_id;
                $order->address_1 = Yii::$app->request->getBodyParam('address_1');
                $order->address_2 = Yii::$app->request->getBodyParam('address_2');
                $order->postalcode = Yii::$app->request->getBodyParam('postal_code');
                $order->city = Yii::$app->request->getBodyParam("city");

            $order->special_directions = Yii::$app->request->getBodyParam("special_directions"); //optional

        } else if ($order->order_mode == Order::ORDER_MODE_PICK_UP) {
            $order->pickup_location_id = Yii::$app->request->getBodyParam("business_location_id");
        }

        if (!$order->is_order_scheduled && !$store->isOpen()) {
            return [
                'operation' => 'error',
                'message' => Yii::t('agent', '{store} is currently closed and is not accepting orders at this time', [
                    'store' => $store->name
                ])
            ];
        }

        $transaction = Yii::$app->db->beginTransaction();

        if (!$order->save()) {
            $transaction->rollBack();

            //\Yii::error(json_encode($order->errors), __METHOD__);

            return [
                'operation' => 'error',
                'message' => $order->getErrors(),
            ];
        }

        $items = Yii::$app->request->getBodyParam("items");

        if (!$items) {
            $transaction->rollBack();

            return [
                'operation' => 'error',
                'message' => Yii::t('agent', 'Item Uuid is invalid.')
            ];
        }

        foreach ($items as $item) {

            //Save items to the above order
            $orderItem = new OrderItem;

            $orderItem->order_uuid = $order->order_uuid;
            $orderItem->item_uuid = $item["item_uuid"];
            $orderItem->item_variant_uuid = isset($item["item_variant_uuid"])? $item["item_variant_uuid"]: null;
            $orderItem->qty = (int)$item["qty"];

            $orderItem->shipping = isset($item["shipping"])? $item["shipping"]: true;
            $orderItem->weight = isset($item["weight"])? $item["weight"]: 0;

            $orderItem->width = isset($item["width"])? $item["width"]: 0;
            $orderItem->height = isset($item["height"])? $item["height"]: 0;
            $orderItem->length = isset($item["length"])? $item["length"]: 0;

            //optional field
            if (array_key_exists("customer_instruction", $item) && $item["customer_instruction"] != null)
                $orderItem->customer_instruction = $item["customer_instruction"];

            if (!$orderItem->save()) {
                $transaction->rollBack();

                if (!isset($orderItem->errors['qty']))
                {
                        //todo: notify vendor?
                    //\Yii::error(json_encode($orderItem->errors), __METHOD__);
                }

                return [
                    'operation' => 'error',
                    'message' => $orderItem->getErrors()
                ];
            }

            if (array_key_exists('extraOptions', $item)) {

                $extraOptionsArray = $item['extraOptions'];

                if (isset($extraOptionsArray) && count($extraOptionsArray) > 0) {

                    foreach ($extraOptionsArray as $key => $extraOption) {

                        $orderItemExtraOption = new OrderItemExtraOption;
                        $orderItemExtraOption->order_item_id = $orderItem->order_item_id;
                        $orderItemExtraOption->option_id = isset($extraOption['option_id'])?$extraOption['option_id']: null;
                        $orderItemExtraOption->extra_option_id = isset($extraOption['extra_option_id'])? $extraOption['extra_option_id']: null;
                        $orderItemExtraOption->qty = (int)$item["qty"];

                        if (!$orderItemExtraOption->save()) {

                            $transaction->rollBack();

                            if (!isset($orderItemExtraOption->errors['qty']))
                            {   
                                //todo: notiy vendor?
                                //\Yii::error(json_encode($orderItemExtraOption->errors), __METHOD__);

                                $response = [
                                    'operation' => 'error',
                                    'code' => 1,
                                    'message' => $orderItemExtraOption->errors,
                                ];
                            } 
                        }
                    }

                } else {

                    $response = [
                        'operation' => 'error',
                        'code' => 2,
                        'message' => $orderItem->getErrors ()
                    ];
                }
            } else {
                $response = [
                    'operation' => 'error',
                    'code' => 4,
                    'message' => Yii::t ('agent', 'Item Uuid is invalid.')
                ];
            }
        }
        
        $order->updateOrderTotalPrice();

        if ($order->order_mode == Order::ORDER_MODE_DELIVERY && $order->subtotal < $order->deliveryZone->min_charge) {
            $transaction->rollBack();

            return [
                'operation' => 'error',
                'message' => Yii::t('agent', 'Minimum order amount {amount}', [
                    'amount' => Yii::$app->formatter->asCurrency(
                        $order->deliveryZone->min_charge,
                        $order->currency->code,
                        [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => 10]
                    )
                ])
            ];
        }

        //for manual orders

        $order->deductStock();

        $transaction->commit();

        return [
            'operation' => 'success',
            "model" => Order::findOne($order->order_uuid),
            'message' => Yii::t('agent', 'Order created successfully')
        ];
    }

    /**
     * Update Order
     */
    public function actionUpdate($order_uuid, $store_uuid = null)
    {
        $area_id = Yii::$app->request->getBodyParam("area_id");
        $country_id = Yii::$app->request->getBodyParam("country_id");
        $delivery_zone_id = Yii::$app->request->getBodyParam("delivery_zone_id");

        $transaction = Yii::$app->db->beginTransaction();

        $order = $this->findModel($order_uuid, $store_uuid);

        //Save Customer Info
        $order->scenario = \common\models\Order::SCENARIO_CREATE_ORDER_BY_ADMIN;

        $currency_code = Yii::$app->request->getBodyParam('currency_code');

        //todo: don't allow currency change on edit
        
        if($currency_code != $order->currency_code)
        {
            $order->currency_code = $currency_code;
            $order->currency_rate = $order->currency->rate / $order->restaurant->currency->rate;
        }

        //todo: what if change, customer
        $order->customer_name = Yii::$app->request->getBodyParam("customer_name");
        $order->customer_phone_number = str_replace(' ', '', strval(Yii::$app->request->getBodyParam("customer_phone_number")));
        $order->customer_phone_country_code = Yii::$app->request->getBodyParam("country_code") ? Yii::$app->request->getBodyParam("customer_phone_country_code") : 965;
        $order->customer_email = Yii::$app->request->getBodyParam("customer_email"); //optional

        $order->order_mode = Yii::$app->request->getBodyParam("order_mode");
        $order->area_id = Yii::$app->request->getBodyParam("area_id");
        $order->unit_type = Yii::$app->request->getBodyParam("unit_type");
        $order->block = Yii::$app->request->getBodyParam("block");
        $order->street = Yii::$app->request->getBodyParam("street");
        $order->avenue = Yii::$app->request->getBodyParam("avenue"); //optional
        $order->house_number = Yii::$app->request->getBodyParam("house_number");
        $order->building = Yii::$app->request->getBodyParam("building");

        $order->payment_method_id = Yii::$app->request->getBodyParam('payment_method_id');
        if ($order->paymentMethod && $order->paymentMethod->payment_method_name) {
            $order->payment_method_name = $order->paymentMethod->payment_method_name;
            $order->payment_method_name_ar = $order->paymentMethod->payment_method_name_ar;
        }

        $order->total_price = Yii::$app->request->getBodyParam('total_price');
        $order->subtotal = Yii::$app->request->getBodyParam('subtotal');
        
        $order->estimated_time_of_arrival = date (
                "Y-m-d H:i:s",
                strtotime (Yii::$app->request->getBodyParam ('estimated_time_of_arrival'))
            );

        $order->currency_code = Yii::$app->request->getBodyParam('currency_code');
        $order->store_currency_code = Yii::$app->request->getBodyParam('currency_code');
        if ($order->restaurant && $order->restaurant->currency && $order->restaurant->currency->code) {
            $order->store_currency_code = $order->restaurant->currency->code;
        }

        //Apply promo code

        if (Yii::$app->request->getBodyParam("voucher_id")) {
            $order->voucher_id = Yii::$app->request->getBodyParam("voucher_id");
        }
        /*else if (($voucher = Yii::$app->request->getBodyParam("voucher")) !== null) {

            $voucherModel = $order->restaurant->getVouchers()
                ->andWhere(['code' => $voucher['code']])
                ->one();

            if(!$voucherModel) {
                    $transaction->rollBack();

                    return [
                        'operation' => 'error',
                        'message' => Yii::t('app', "Invalid voucher code.")
                    ];
            }

            $order->voucher_id = $voucherModel->voucher_id;
        }*/

        //if the order mode = 1 => Delivery
        #todo below code need to remove if not in use
        if ($order->order_mode == Order::ORDER_MODE_DELIVERY) {
            //Deliver to Kuwait - GCC

            $area_delivery_zone = $order->restaurant->getAreaDeliveryZones()
                ->andWhere(new Expression('area_id IS NULL OR area_id="' . $area_id . '"'))
                ->one();

            if (!$area_delivery_zone) {
                return [
                    'operation' => 'error',
                    'message' => Yii::t('app', "Store does not deliver to this delivery zone.")
                ];
            }

                //if delivery zone not specified

                $order->delivery_zone_id = $delivery_zone_id? $delivery_zone_id: $area_delivery_zone->delivery_zone_id;
                $order->floor = Yii::$app->request->getBodyParam("floor");
                $order->apartment = Yii::$app->request->getBodyParam("apartment");
                $order->office = Yii::$app->request->getBodyParam("office");

                $order->shipping_country_id = $country_id;
                $order->address_1 = Yii::$app->request->getBodyParam('address_1');
                $order->address_2 = Yii::$app->request->getBodyParam('address_2');
                $order->postalcode = Yii::$app->request->getBodyParam('postal_code');
                $order->city = Yii::$app->request->getBodyParam("city");

        } else if ($order->order_mode == Order::ORDER_MODE_PICK_UP) {
            $order->pickup_location_id = Yii::$app->request->getBodyParam("pickup_location_id");
        }

        $order->special_directions = Yii::$app->request->getBodyParam("special_directions"); //optional

        if (!$order->save()) {

            $transaction->rollBack();

            return [
                'operation' => 'error',
                'code' => 1,
                'message' => $order->getErrors(),
            ];
        }

        $orderItems = Yii::$app->request->getBodyParam('orderItems');

        //delete order items not available in input but in db

        OrderItem::deleteAll([
            'AND',
            ['order_uuid' => $order_uuid],
            [
                'NOT IN',
                'order_item_id',
                ArrayHelper::getColumn($orderItems, 'order_item_id')
            ]
        ]);

        //update order items

        foreach ($orderItems as $item) {

            if (isset($item["order_item_id"])) {
                $orderItem = $this->findOrderItem($order_uuid, $item["order_item_id"]);
            } else {
                $orderItem = new \common\models\OrderItem;
            }

            $orderItem->restaurant_uuid = $order->restaurant_uuid;//for newly added item
            $orderItem->order_uuid = $order->order_uuid;
            $orderItem->item_uuid = isset($item["item_uuid"]) ? $item["item_uuid"] : null;
            $orderItem->item_variant_uuid = isset($item["item_variant_uuid"])? $item["item_variant_uuid"]: null;
            $orderItem->item_name = $item["item_name"];
            $orderItem->item_name_ar = $item["item_name_ar"];
            $orderItem->qty = (int)$item["qty"];
            $orderItem->item_price = $item["item_price"];
            $orderItem->item_unit_price = isset($item["item_unit_price"])? $item["item_unit_price"]:
                $item["item_price"]/$item["qty"];

            if (isset($item["customer_instruction"]))
                $orderItem->customer_instruction = $item["customer_instruction"];

            if (!$orderItem->save()) {

                $transaction->rollBack();

                return [
                    'operation' => 'error',
                    'code' => 2,
                    'message' => $orderItem->getErrors()
                ];
            }

            if (!isset($item['orderItemExtraOptions']) || !is_array($item['orderItemExtraOptions'])) {
                continue;
            }

            foreach ($item['orderItemExtraOptions'] as $key => $extraOption) {

                //if item update

                if (isset($extraOption['order_item_extra_option_id'])) {
                    continue;//as we not updating order item options
                }

                $orderItemExtraOption = new \common\models\OrderItemExtraOption;
                $orderItemExtraOption->order_item_id = $orderItem->order_item_id;
                $orderItemExtraOption->extra_option_id = isset($extraOption['extra_option_id'])? $extraOption['extra_option_id']: null;
                $orderItemExtraOption->option_id = isset($extraOption['option_id'])?$extraOption['option_id']: null;
                $orderItemExtraOption->qty = (int)$item["qty"];

                if (!$orderItemExtraOption->save()) {

                    $transaction->rollBack();

                    return [
                        'operation' => 'error',
                        'message' => $orderItemExtraOption->errors,
                    ];
                }
            }
        }

        $order->updateOrderTotalPrice();

        if ($order->order_mode == Order::ORDER_MODE_DELIVERY && $order->subtotal < $order->deliveryZone->min_charge) {
            return [
                'operation' => 'error',
                'code' => 3,
                'message' => Yii::t('agent', 'Minimum order amount {amount}', [
                    'amount' => Yii::$app->formatter->asCurrency($order->deliveryZone->min_charge, $order->currency->code, [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => 10])
                ])
            ];
        }

        if(Yii::$app->request->getBodyParam('estimated_time_of_arrival')){

          $order->setScenario(Order::SCENARIO_CREATE_ORDER_BY_ADMIN);

          $order->estimated_time_of_arrival =
              date(
                  "Y-m-d H:i:s",
                  strtotime(Yii::$app->request->getBodyParam('estimated_time_of_arrival'))
              );

              if (!$order->save()) {
                  return [
                      'operation' => 'error',
                      'code' => 4,
                      'message' => $order->errors,
                  ];
              }
        }

        $transaction->commit();

        return [
            'operation' => 'success',
            'message' => Yii::t('agent', 'Order updated successfully'),
            "model" => $order
        ];
    }

    /**
     *  find oreder item if exists
     */
    public function findOrderItem($order_uuid, $order_item_id)
    {
        return OrderItem::findOne([
            'order_uuid' => $order_uuid,
            'order_item_id' => $order_item_id
        ]);
    }

    /**
     * try to initiate refund
     * @param $order_uuid
     * @throws NotFoundHttpException
     */
    public function actionRefund($order_uuid)
    {
        $model = $this->findModel($order_uuid);

        $refund_amount = Yii::$app->request->getBodyParam('refund_amount');

        $itemsToRefund = Yii::$app->request->getBodyParam('itemsToRefund');

        //validate order status for refund

        // if (!in_array ($model->order_status, [Order::STATUS_COMPLETE, Order::STATUS_PARTIALLY_REFUNDED])) {
        //     return [
        //         "operation" => "error",
        //         "message" => Yii::t ('agent', "Invalid order status to process refund!")
        //     ];
        // }

        //validate refund qty less than or equals to current qty

        // $maxRefundAmount = 0;
        //
        // foreach ($itemsToRefund as $key => $qty) {
        //
        //     $orderItem = OrderItem::find ()
        //         ->andWhere ([
        //             'order_uuid' => $order_uuid,
        //             'order_item_id' => $key
        //         ])
        //         ->one ();
        //
        //     $refundedQty = RefundedItem::find ()
        //         ->andWhere ([
        //             'order_uuid' => $order_uuid,
        //             'order_item_id' => $key
        //         ])
        //         ->sum ('qty');
        //
        //     if ($orderItem->qty - $refundedQty < $qty) {
        //         return [
        //             "operation" => "error",
        //             "message" => Yii::t ('agent', "Max {qty} {item} available for refund!", [
        //                 'qty' => $orderItem->qty - $refundedQty,
        //                 'item' => Yii::$app->language == 'ar' && $orderItem->item_name_ar ?
        //                     $orderItem->item_name_ar : $orderItem->item_name
        //             ])
        //         ];
        //     }
        //
        //     //calculate refund total
        //
        //     $unitPrice = $orderItem->item_price / $orderItem->qty;
        //
        //     $maxRefundAmount += $qty * $unitPrice;
        // }
        //
        // //refund_amount should be less than or equal to itemsToRefund's total refund amount
        //
        // if ($refund_amount > $maxRefundAmount) {
        //     return [
        //         "operation" => "error",
        //         "message" => Yii::t ('agent', "{amount} available for refund!", [
        //             'amount' => \Yii::$app->formatter->asCurrency ($maxRefundAmount, $model->currency->code)
        //         ])
        //     ];
        // }

        if ($refund_amount > $model->total) {
            return [
                "operation" => "error",
                "message" => Yii::t('agent', "{amount} available for refund!", [
                    'amount' => \Yii::$app->formatter->asCurrency($model->total, $model->currency->code)
                ])
            ];
        }
        $transaction = Yii::$app->db->beginTransaction();

        $refund = new Refund();
        $refund->restaurant_uuid = $model->restaurant_uuid;
        $refund->payment_uuid = $model->payment_uuid;
        $refund->order_uuid = $order_uuid;
        $refund->refund_amount = $refund_amount;
        $refund->reason = Yii::$app->request->getBodyParam('reason');

        if (!$refund->save()) {

            $transaction->rollBack();

            return [
                "operation" => "error",
                "message" => $refund->errors
            ];
        }

        foreach ($itemsToRefund as $key => $qty) {

            $orderItem = OrderItem::find()
                ->andWhere([
                    'order_uuid' => $order_uuid,
                    'order_item_id' => $key
                ])
                ->one();

            if (!$orderItem) {
                $transaction->rollBack();

                return [
                    "operation" => "error",
                    "message" => Yii::t('agent', "Item not found in order!")
                ];
            }

            $unitPrice = $orderItem->item_price / $orderItem->qty;

            $refundItem = new RefundedItem();
            $refundItem->refund_id = $refund->refund_id;
            $refundItem->order_item_id = $key;
            $refundItem->order_uuid = $order_uuid;
            $refundItem->item_uuid = $orderItem->item_uuid;
            $refundItem->item_name = $orderItem->item_name;
            $refundItem->item_name_ar = $orderItem->item_name_ar;
            $refundItem->item_price = $unitPrice * $qty;
            $refundItem->qty = $qty;

            if (!$refundItem->save()) {
                $transaction->rollBack();

                return [
                    "operation" => "error",
                    "message" => $refundItem->errors
                ];
            }
        }

        //todo: update refund qty ? and total ? in order_item and order?

        //update order status? if all item refunded mark as refunded as partially refunded

        $remainingQty = 0;

        foreach ($model->orderItems as $orderItem) {

            $refundedQty = RefundedItem::find()
                ->andWhere([
                    'order_uuid' => $order_uuid,
                    'order_item_id' => $orderItem->order_item_id
                ])
                ->sum('qty');

            $remainingQty += $orderItem->qty - $refundedQty;
        }

        if ($remainingQty > 0) {
            $model->order_status = Order::STATUS_PARTIALLY_REFUNDED;
        } else {
            $model->order_status = Order::STATUS_REFUNDED;
        }

        $model->setScenario(Order::SCENARIO_UPDATE_STATUS);

        if (!$model->save()) {

            $transaction->rollBack();

            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        $transaction->commit();

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Refund initiated successfully")
        ];
    }

    /**
     * Update Order Status
     */
    public function actionUpdateOrderStatus($order_uuid, $store_uuid = null)
    {
        $model = $this->findModel($order_uuid, $store_uuid);

        $model->setScenario(Order::SCENARIO_UPDATE_STATUS);

        $model->order_status = Yii::$app->request->getBodyParam("order_status");

        if (!$model->save()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent', "We've faced a problem updating the order status")
                ];
            }
        }

            $plugn_fee = 0;
            $payment_gateway_fee = 0;
            $plugn_fee_kwd = 0;

            //$total_price = $this->total_price;
            //$delivery_fee = $this->delivery_fee;
            //$subtotal = $this->subtotal;
            //$currency = $this->currency_code;

            $kwdCurrency = Currency::findOne(['code' => 'KWD']);

            //using store currency instead of user as user can have any currency but totals will be in store currency

            $rateKWD = $kwdCurrency->rate / $model->restaurant->currency->rate;

            $rate = 1 / $model->restaurant->currency->rate;// to USD

            if ($model->payment_uuid && $model->payment) {

                $plugn_fee_kwd = ($model->payment->plugn_fee + $model->payment->partner_fee) * $rateKWD;

                $plugn_fee = ($model->payment->plugn_fee + $model->payment->partner_fee) * $rate;

                //$total_price = $total_price * $rate;
                //$delivery_fee = $delivery_fee * $rate;
                //$subtotal = $subtotal * $rate;
                $payment_gateway_fee = $model->payment->payment_gateway_fee * $rate;
            }

            $order_total = $model->total_price * $rate;

            $store = $model->restaurant;

            $itemTypes = [];
            foreach ($store->restaurantItemTypes as $restaurantItemType) {
                $itemTypes[] = $restaurantItemType->businessItemType->business_item_type_en;
            }

            $productsList = null;

            foreach ($model->orderItems as $orderedItem) {
                $productsList[] = [
                    'product_id' => $orderedItem->item_uuid,
                    'sku' => $orderedItem->item && $orderedItem->item->sku ? $orderedItem->item->sku : null,
                    'name' => $orderedItem->item_name,
                    'price' => $orderedItem->item_price,
                    'quantity' => $orderedItem->qty,
                    'url' => $model->restaurant->restaurant_domain . '/product/' . $orderedItem->item_uuid,
                ];
            }

            $data = [
                'order_id' => $model->order_uuid,
                "status" => $model->getOrderStatusInEnglish(),
                "restaurant_uuid" => $model->restaurant_uuid,
                "store" => $store->name,
                "customer_name" => $model->customer_name,
                "customer_email" => $model->customer_email,
                "customer_id" => $model->customer_id,
                "country" => $model->country_name,
                'checkout_id' => $model->order_uuid,
                'is_market_order' => $model->is_market_order,
                'total' => $order_total,
                'revenue' => $plugn_fee,
                "store_revenue" => $order_total - $plugn_fee,
                'gateway_fee' => $payment_gateway_fee,
                'payment_method' => $model->payment_method_name,
                'gateway' => $model->payment_method_name,// $this->payment_uuid ? 'Tap' : null,
                'shipping' => ($model->delivery_fee * $rate),
                'subtotal' => ($model->subtotal * $rate),
                'currency' => $model->currency_code,
                "cash" => $model->paymentMethod && $model->paymentMethod->payment_method_code == PaymentMethod::CODE_CASH ?
                    ($model->total_price * $rate) : 0,
                'coupon' => $model->voucher && $model->voucher->code ? $model->voucher->code : null,
                'products' => $productsList,
                'storeItemTypes' => $itemTypes,
            ];

            Yii::$app->eventManager->track('Order Status Updated', $data,
                null,
                $model->restaurant_uuid);

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Order status updated successfully"),
            "model" => $model
        ];
    }

    /**
     * @param $order_uuid
     * @param $store_uuid
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionRequestDriverFromArmada($order_uuid, $store_uuid = null)
    {
        $armadaApiKey = Yii::$app->request->getBodyParam("armada_api_key");

        $model = $this->findModel($order_uuid, $store_uuid);

        $model->setScenario(Order::SCENARIO_UPDATE_ARMADA);

        if($model->businessLocation)
            $armadaApiKey = $model->businessLocation->armada_api_key;

        if(!$armadaApiKey)
            $armadaApiKey = $model->restaurant->armada_api_key;

        $createDeliveryApiResponse = Yii::$app->armadaDelivery->createDelivery ($model, $armadaApiKey);

        if ($createDeliveryApiResponse->isOk)
        {
            $model->armada_tracking_link = $createDeliveryApiResponse->data['trackingLink'];
            $model->armada_qr_code_link = $createDeliveryApiResponse->data['qrCodeLink'];
            $model->armada_delivery_code = $createDeliveryApiResponse->data['code'];

        } else {

            return [
                "operation" => "error",
                "code" => 1,
                "apiResponse" => $createDeliveryApiResponse->getContent(),
                "message" => Yii::t('agent', "We've faced a problem requesting driver from Armada")
            ];
        }

        if (!$model->save()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "code" => 2,
                    "message" => Yii::t('agent', "We've faced a problem requesting driver from Armada")
                ];
            }
        }

        return [
            "operation" => "success",
            "armada_tracking_link" => $model->armada_tracking_link,
            "armada_qr_code_link" => $model->armada_qr_code_link,
            "armada_delivery_code" => $model->armada_delivery_code,
            "message" => Yii::t('agent', "Your request has been successfully submitted")
        ];
    }

    /**
     * @param $order_uuid
     * @param $store_uuid
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionCreateShipmentAramex($order_uuid, $store_uuid = null)
    {
        $model = $this->findModel($order_uuid, $store_uuid);

        return Aramex::createDelivery ($model);
    }

    /**
     * @param $order_uuid
     * @return array
     * @throws NotFoundHttpException
     * @throws \SoapFault
     */
    public function actionSchedulePickupAramex($order_uuid)
    {
        $model = $this->findModel($order_uuid);

        return Aramex::schedulePickup($model);
    }

    /**
     * cancel delivery
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionCancelDelivery($id)
    {
        $model = $this->findModel($id);

        $model->setScenario(Order::SCENARIO_UPDATE_ARMADA);

        if($model->businessLocation)
            $armadaApiKey = $model->businessLocation->armada_api_key;

        if(!$armadaApiKey)
            $armadaApiKey = $model->restaurant->armada_api_key;

        $response = Yii::$app->armadaDelivery->cancelDelivery ($model, $armadaApiKey);

        if ($response->isOk)
        {
            //todo: for maskor

            return [
                "operation" => "success",
                "message" => Yii::t('agent', "Your request has been successfully cancelled")
            ];
        }

        return [
            "operation" => "error",
            "code" => 1,
            "apiResponse" => $response->getContent(),
            "message" => Yii::t('agent', "We've faced a problem requesting driver from Armada")
        ];
    }

    /**
     * Request a driver from Mashkor
     * @param type $order_uuid
     * @param type $store_uuid
     */
    public function actionRequestDriverFromMashkor($order_uuid, $store_uuid = null)
    {
        $mashkorBranchId = Yii::$app->request->getBodyParam("mashkor_branch_id");

        $model = $this->findModel($order_uuid, $store_uuid);

        $model->setScenario(Order::SCENARIO_UPDATE_MASHKOR);

        if($model->businessLocation)
            $mashkorBranchId = $model->businessLocation->mashkor_branch_id;

        if(!$mashkorBranchId) {
            $mashkorBranchId = $model->restaurant->mashkor_branch_id;
        }

        $createDeliveryApiResponse = Yii::$app->mashkorDelivery->createOrder ($model, $mashkorBranchId);

        if ($createDeliveryApiResponse->isOk) {

            $model->mashkor_order_number = $createDeliveryApiResponse->data['data']['order_number'];
            $model->mashkor_order_status = Order::MASHKOR_ORDER_STATUS_CONFIRMED;

        } else {

            return [
                "operation" => "error",
                "code" => 1,
                "apiResponse" => $createDeliveryApiResponse->getContent(),
                "message" => Yii::t('agent', "We've faced a problem requesting driver from Mashkor")
            ];
        }

        if (!$model->save()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "code" => 2,
                    "message" => Yii::t('agent', "We've faced a problem requesting driver from Mashkor")
                ];
            }
        }

        return [
            "operation" => "success",
            "mashkor_order_number" => $model->mashkor_order_number,
            "mashkor_order_status" => $model->mashkor_order_status,
            "message" => Yii::t('agent', "Your request has been successfully submitted")
        ];
    }

    /**
     * Return order detail
     * @param type $order_uuid
     * @return type
     */
    public function actionDetail($order_uuid)
    {
        return $this->findModel($order_uuid);
    }

    /**
     * Delete Order
     */
    public function actionDelete($order_uuid, $store_uuid = null)
    {
        $transaction = Yii::$app->db->beginTransaction();

        $model = $this->findModel($order_uuid, $store_uuid);

        $model->setScenario(\common\models\Order::SCENARIO_DELETE);

        $model->is_deleted = 1;

        $model->restockItems();

        if (!$model->save()) {

            $transaction->rollBack();

            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent', "We've faced a problem deleting the order")
                ];
            }
        }

        $transaction->commit();

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Order deleted successfully")
        ];
    }


    /**
     * Delete Order
     */
    public function actionSoftDelete($order_uuid, $store_uuid = null)
    {
        $model = $this->findModel($order_uuid, $store_uuid);

        $model->setScenario(Order::SCENARIO_DELETE);

        $transaction = Yii::$app->db->beginTransaction();

        $model->restockItems();

        $model->is_deleted = 1;

        if (!$model->save()) {
            $transaction->rollBack();
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent', "We've faced a problem deleting the order")
                ];
            }
        }

        $transaction->commit();

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Order deleted successfully")
        ];
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionOrdersReport()
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $start_date = Yii::$app->request->get('from');
        $end_date = Yii::$app->request->get('to');

        //todo: partial order as not active?

        $query = \common\models\Order::find()
            ->filterBusinessLocationIfManager($store->restaurant_uuid)
            //->andWhere(['order.restaurant_uuid' => $store->restaurant_uuid])
            ->joinWith(['currency', 'paymentMethod', 'payment'])
            ->activeOrders($store->restaurant_uuid)
            ->with('voucher')
            ->orderBy(['order_created_at' => SORT_ASC]);

        if ($start_date && $end_date) {
            $query->andWhere(new Expression('DATE(order.order_created_at) >= DATE("' . $start_date . '") AND
                    DATE(order.order_created_at) <= DATE("' . $end_date . '")'));
        } else {
            $query->andWhere(new Expression('DATE(order_created_at) > DATE_SUB(now(), INTERVAL 3 MONTH)'));
        }

        $searchResult = $query->all();

        header('Access-Control-Allow-Origin: *');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment;filename=\"orders.xlsx\"");
        header("Cache-Control: max-age=0");

        \moonland\phpexcel\Excel::export([
            'isMultipleSheet' => false,
            'models' => $searchResult,
            'columns' => [
                [
                    'attribute' => 'order_uuid',
                    "format" => "raw",
                    "value" => function ($model) {
                        return '#' . $model->order_uuid;
                    }
                ],
                [
                    'attribute' => 'customer_name',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->customer_name;
                    },
                ],
                'customer_phone_number',
                [
                    'header' => Yii::t('agent', 'Order Mode'),
                    'value' => function ($data) {
                        return $data->order_mode == Order::ORDER_MODE_DELIVERY ? 'Delivery' : 'Pickup';
                    }
                ],
                [
                    'header' => Yii::t('agent', 'Area'),
                    // 'format' => 'html',
                    'value' => function ($data) {
                        if ($data->area_id)
                            return $data->area_name;
                    }
                ],
                [
                    'attribute' => 'order_status',
                    "format" => "raw",
                    "value" => function ($model) {
                        return $model->orderStatusInEnglish;
                    }
                ],
                [
                    'attribute' => 'Payment method',
                    "format" => "raw",
                    "value" => function ($data) {
                        if ($data->payment_uuid)
                            return $data->payment->payment_current_status;
                        else if($data->paymentMethod)
                            return $data->paymentMethod->payment_method_name;
                    },
                ],
                [
                    'attribute' => 'Voucher Code',
                    "format" => "raw",
                    "value" => function ($data) {
                        if ($data->voucher_id && !empty($data->voucher))
                            return $data->voucher->code;
                        else
                            return '';
                    },
                ],

                [
                    'attribute' => 'delivery_fee',
                    "value" => function ($data) {
                        return \Yii::$app->formatter->asCurrency($data->delivery_fee, $data->currency->code);
                    },
                ],

                [
                    'header' => Yii::t('agent', 'Amount Charged'),
                    'attribute' => 'total_price',
                    "value" => function ($data) {
                        return \Yii::$app->formatter->asCurrency($data->payment_uuid ?
                            $data->payment->payment_amount_charged : $data->total, $data->currency->code);
                    }
                ],

                [
                    'header' => Yii::t('agent', 'Net Amount'),
                    "value" => function ($data) {
                        if ($data->payment_uuid)
                            return \Yii::$app->formatter->asCurrency($data->payment->payment_net_amount, $data->currency->code);
                        else
                            return \Yii::$app->formatter->asCurrency($data->total, $data->currency->code);
                    }
                ],
                [
                    'header' => Yii::t('agent', 'Plugn fee'),
                    "value" => function ($data) {
                        if ($data->payment_uuid && $data->payment->plugn_fee) {
                            $plugnFee = $data->payment->plugn_fee + $data->payment->partner_fee;
                            return \Yii::$app->formatter->asCurrency($plugnFee, $data->currency->code);
                        } else
                            return \Yii::$app->formatter->asCurrency(0, $data->currency->code);
                    }
                ],
                [
                    'header' => Yii::t('agent', 'Payment Gateway fee'),
                    "value" => function ($data) {
                        if ($data->payment_uuid)
                            return \Yii::$app->formatter->asCurrency($data->payment->payment_gateway_fee, $data->currency->code);
                        else
                            return \Yii::$app->formatter->asCurrency(0, $data->currency->code);

                    }
                ],

                'order_created_at'
            ]
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($store_uuid = null)
    {
        $restaurant = Yii::$app->accountManager->getManagedAccount($store_uuid);

        $model = new Order();
        $model->setScenario(\common\models\Order::SCENARIO_CREATE_ORDER_BY_ADMIN);

        $model->restaurant_uuid = $restaurant->restaurant_uuid;
        $model->is_order_scheduled = 0;

        $utm_uuid = Yii::$app->request->getBodyParam("utm_uuid");
        $order_mode = Yii::$app->request->getBodyParam('order_mode');
        $customer_name = Yii::$app->request->getBodyParam('customer_name');
        $area_name = Yii::$app->request->getBodyParam('area_name');
        $area_id = Yii::$app->request->getBodyParam('area_id');
        $delivery_zone_id = Yii::$app->request->getBodyParam('delivery_zone_id');
        $unit_type = Yii::$app->request->getBodyParam('unit_type');
        $street = Yii::$app->request->getBodyParam('street');
        $avenue = Yii::$app->request->getBodyParam('avenue');
        $building = Yii::$app->request->getBodyParam('building');
        $house_number = Yii::$app->request->getBodyParam('house_number');
        $block = Yii::$app->request->getBodyParam('block');
        $office = Yii::$app->request->getBodyParam('office');
        $apartment = Yii::$app->request->getBodyParam('apartment');
        $floor = Yii::$app->request->getBodyParam('floor');

        $customer_email = Yii::$app->request->getBodyParam('customer_email');
        $customer_phone_country_code = Yii::$app->request->getBodyParam('customer_phone_country_code');
        $customer_phone_number = Yii::$app->request->getBodyParam('customer_phone_number');
        $pickup_location_id = Yii::$app->request->getBodyParam('pickup_location_id');
        $estimated_time_of_arrival = Yii::$app->request->getBodyParam('estimated_time_of_arrival');
        $special_directions = Yii::$app->request->getBodyParam('special_directions');
        $voucher_code = Yii::$app->request->getBodyParam("voucher_code");

        $voucher = $restaurant->getVouchers()
            ->andWhere(['code' => $voucher_code])
            ->one();

        if($voucher_code) {
            if(!$voucher) {
                return [
                    'operation' => 'error',
                    'message' => Yii::t('app', "Invalid voucher code.")
                ];
            }
            else
            {
                $model->voucher_id = $voucher->voucher_id;
                $model->voucher_code = $voucher->code;
            }
        }

        $model->payment_method_id = Yii::$app->request->getBodyParam('payment_method_id');

        if ($model->paymentMethod && $model->paymentMethod->payment_method_name) {
            $model->payment_method_name = $model->paymentMethod->payment_method_name;
            $model->payment_method_name_ar = $model->paymentMethod->payment_method_name_ar;
        }

        $model->total_price = Yii::$app->request->getBodyParam('total_price');
        $model->subtotal = Yii::$app->request->getBodyParam('subtotal');

        $model->currency_code = Yii::$app->request->getBodyParam('currency_code');
        $model->store_currency_code = Yii::$app->request->getBodyParam('currency_code');

        if ($model->restaurant && $model->restaurant->currency && $model->restaurant->currency->code) {
            $model->store_currency_code = $model->restaurant->currency->code;
        }

        $model->utm_uuid = $utm_uuid;
        $model->order_mode = $order_mode;
        $model->customer_name = $customer_name;
        $model->area_name = $area_name;
        $model->area_name_ar = Yii::$app->request->getBodyParam('area_name_ar');
        $model->area_id = $area_id;
        $model->delivery_zone_id = $delivery_zone_id;
        $model->unit_type = $unit_type;
        $model->street = $street;
        $model->office = $office;
        $model->apartment = $apartment;
        $model->floor = $floor;
        $model->avenue = $avenue;
        $model->house_number = $house_number;
        $model->building = $building;
        $model->block = $block;
        $model->customer_id = Yii::$app->request->getBodyParam('customer_id');
        $model->city = Yii::$app->request->getBodyParam('city');
        $model->postalcode = Yii::$app->request->getBodyParam('postalcode');
        $model->address_1 = Yii::$app->request->getBodyParam('address_1');
        $model->address_2 = Yii::$app->request->getBodyParam('address_2');

        //$model->shipping_country_id = Yii::$app->request->getBodyParam('shipping_country_id');

        $model->customer_email = $customer_email;
        $model->customer_phone_country_code = $customer_phone_country_code;
        $model->customer_phone_number = $customer_phone_number;
        $model->pickup_location_id = $pickup_location_id;
        $model->estimated_time_of_arrival = date("Y-m-d H:i:s", strtotime($estimated_time_of_arrival));
        $model->special_directions = $special_directions;

        if ($model->order_mode == Order::ORDER_MODE_DELIVERY)
        {
            $query = AreaDeliveryZone::find()
                ->andWhere([
                    'restaurant_uuid' => $restaurant->restaurant_uuid,
                ]);

            if($model->area_id) {
                $query->andWhere(new Expression('area_id IS NULL OR area_id="' . $model->area_id . '"'));
            }

            if($model->delivery_zone_id) {
                $query->andWhere(['delivery_zone_id' => $model->delivery_zone_id]);
            }

            $areaDeliveryZone = $query->one();

            if (!$areaDeliveryZone) {
                return [
                    'operation' => 'error',
                    'message' => Yii::t('app', "Store does not deliver to this delivery zone.")
                ];
            }

            $model->delivery_zone_id = $areaDeliveryZone->delivery_zone_id;
        }

        $transaction = Yii::$app->db->beginTransaction();

        if (!$model->save()) {

            $transaction->rollBack();

            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    'code' => 1,
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent', "We've faced a problem created the order")
                ];
            }
        }

        //add order items

        $orderItems = Yii::$app->request->getBodyParam('orderItems');

        foreach ($orderItems as $item) {

            $orderItem = new \common\models\OrderItem;

            $orderItem->order_uuid = $model->order_uuid;
            $orderItem->item_uuid = isset($item["item_uuid"]) ? $item["item_uuid"] : null;
            $orderItem->item_variant_uuid = isset($item["item_variant_uuid"]) ? $item["item_variant_uuid"] : null;
            $orderItem->item_name = $item["item_name"];
            $orderItem->item_name_ar = $item["item_name_ar"];
            $orderItem->qty = (int)$item["qty"];

            $orderItem->shipping = isset($item["shipping"])? $item["shipping"]: true;
            $orderItem->weight = isset($item["weight"])? $item["weight"]: 0;

            $orderItem->width = isset($item["width"])? $item["width"]: 0;
            $orderItem->height = isset($item["height"])? $item["height"]: 0;
            $orderItem->length = isset($item["length"])? $item["length"]: 0;

            $orderItem->item_price = $item["item_price"];
            $orderItem->restaurant_uuid = $restaurant->restaurant_uuid;
            $orderItem->item_unit_price = isset($item["item_unit_price"])? $item["item_unit_price"]:
                $item["item_price"]/$item["qty"];

            if (isset($item["customer_instruction"]))
                $orderItem->customer_instruction = $item["customer_instruction"];

            if (!$orderItem->save()) {

                $transaction->rollBack();

                return [
                    'operation' => 'error',
                    'code' => 2,
                    'message' => $orderItem->getErrors ()
                ];
            }

            if (!isset($item['orderItemExtraOptions']) || !is_array($item['orderItemExtraOptions'])) {
                continue;
            }

            foreach ($item['orderItemExtraOptions'] as $key => $extraOption) {

                $orderItemExtraOption = new \common\models\OrderItemExtraOption;
                $orderItemExtraOption->order_item_id = $orderItem->order_item_id;
                $orderItemExtraOption->extra_option_id = isset($extraOption['extra_option_id'])? $extraOption['extra_option_id']: null;
                $orderItemExtraOption->option_id = isset($extraOption['option_id'])?$extraOption['option_id']: null;
                $orderItemExtraOption->qty = (int)$item["qty"];

                if (!$orderItemExtraOption->save()) {

                    $transaction->rollBack();

                    return [
                        'operation' => 'error',
                        'code' => 3,
                        'message' => $orderItemExtraOption->errors,
                    ];
                }
            }
        }

        //for manual orders

        $model->deductStock();

        $transaction->commit();

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Order created successfully")
        ];
    }

    /**
     * refresh payment status from tap api response
     * @param $order_uuid
     * @param $store_uuid
     * @return string[]|void
     * @throws NotFoundHttpException
     */
    public function actionRequestPaymentStatusFromTap($order_uuid, $store_uuid = null)
    {
        try {

            $store = Yii::$app->accountManager->getManagedAccount($store_uuid);

            $payment = Payment::find()->where([
                'order_uuid' => $order_uuid,//$payment->payment_uuid,
                'restaurant_uuid' => $store->restaurant_uuid
            ])->one();

            if ($payment !== null) {

                if ($payment->payment_gateway_name == 'tap') {
                    $transaction_id = $payment->payment_gateway_transaction_id;
                    Payment::updatePaymentStatusFromTap($transaction_id, true);
                } else if ($payment->payment_gateway_name == 'myfatoorah') {
                    $invoice_id = $payment->payment_gateway_invoice_id;
                    Payment::updatePaymentStatusFromMyFatoorah($invoice_id, true);
                }
                return [
                    "operation" => "success",
                ];
            }
        } catch (\Exception $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    /**
     * Download invoice as PDF
     * @param $id
     * @param $type
     * @return array|mixed
     */
    public function actionDownloadInvoice($id)
    {
        $order = $this->findModel($id);

        // Item extra optn
        // $itemsExtraOpitons = new \yii\data\ActiveDataProvider([
        //     'query' => $order->getOrderItemExtraOptions()
        // ]);

        $this->layout = 'pdf';

        $defaultLogo = Url::to('@web/images/icon-128x128.png', true);

        $content = $this->render('invoice', [
            'order' => $order,
            'defaultLogo' => $defaultLogo,
            'bankDiscount' => $order->voucher_discount,
            'voucherDiscount' => $order->bank_discount,
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
            'destination' => Pdf::DEST_BROWSER,
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
        return $pdf->render();
    }

    /**
     * @return string
     * @throws NotFoundHttpException
     * @throws \Mpdf\MpdfException
     * @throws \setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException
     * @throws \setasign\Fpdi\PdfParser\PdfParserException
     * @throws \setasign\Fpdi\PdfParser\Type\PdfTypeException
     * @throws \yii\base\InvalidConfigException
     */
    public function actionDownloadTodayInvoices()
    {
        $store = Yii::$app->accountManager->getManagedAccount();

        $this->layout = 'pdf';

        $defaultLogo = Url::to('@web/images/icon-128x128.png', true);

        $orderQuery = $store->getOrders()
           // ->limit(100);
            ->andWhere(new Expression("DATE(order_created_at) = DATE('".date("Y-m-d")."')"));

        $count = $orderQuery->count();

        if($count > 100) {

            throw new HttpException(406, Yii::t("app", "No more than 100 invoices can be downloaded on single request"));

            return [
                "operation" => "error",
                "message" => Yii::t("app", "No more than 100 invoices can be downloaded on single request")
            ];
        }

        $content = "";

        foreach ($orderQuery->batch() as $orders) {

            foreach ($orders as $order) {

                $content .= $this->render('invoice', [
                    'order' => $order,
                    'defaultLogo' => $defaultLogo,
                    'bankDiscount' => $order->voucher_discount,
                    'voucherDiscount' => $order->bank_discount,
                ]);
            }
        }

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
                'title' => 'Invoices',
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
            'destination' => Pdf::DEST_BROWSER,
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
        return $pdf->render();
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($order_uuid, $store_uuid = null)
    {
        $store = Yii::$app->accountManager->getManagedAccount($store_uuid);

        $model = Order::find()
            ->filterBusinessLocationIfManager($store->restaurant_uuid)
            ->andWhere([
                'order_uuid' => $order_uuid,
                'restaurant_uuid' => $store->restaurant_uuid
            ])
            ->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
