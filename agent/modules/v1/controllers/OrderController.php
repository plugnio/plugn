<?php

namespace agent\modules\v1\controllers;

use common\models\AreaDeliveryZone;
use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use agent\models\Order;
use agent\models\OrderItem;
use agent\models\OrderItemExtraOption;
use yii\db\Expression;


class OrderController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors ();

        // remove authentication filter for cors to work
        unset($behaviors['authenticator']);

        // Allow XHR Requests from our different subdomains and dev machines
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className (),
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

        // Bearer Auth checks for Authorize: Bearer <Token> header to login the user
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::className (),
        ];
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions ();
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
            // optional:
            'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }

    /**
     * list orders
     * @param $type
     * @return ActiveDataProvider
     */
    public function actionList($type)
    {
        $store_uuid = Yii::$app->request->get ('store_uuid');
        $keyword = Yii::$app->request->get ('keyword');
        $order_uuid = Yii::$app->request->get ('order_uuid');
        $phone = Yii::$app->request->get ('phone');
        $status = Yii::$app->request->get ('status');
        $customer = Yii::$app->request->get ('customer');
        $date_range = Yii::$app->request->get ('date_range');
        $customer_id = Yii::$app->request->get ('customer_id');

        Yii::$app->accountManager->getManagedAccount ($store_uuid);

        $query = Order::find();

        # as we already doing some search with keyword textbox so reusing that
        if ($order_uuid) {
            $keyword = $order_uuid;
        } else if ($phone) {
            $keyword = $phone;
        } else if ($customer) {
            $keyword = $customer;
        }

        // grid filtering conditions
        $query->orderBy (['order_created_at' => SORT_DESC]);

        if ($status) {
            $query->andFilterWhere(['order_status' => $status]);
        }

        if ($date_range) {
            $query->filterByCreatedDate($date_range);
        }
        if ($customer_id) {
            $query->andFilterWhere(['customer_id' => $customer_id]);
        }
        if ($type == 'active') {
            $query->andWhere (
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
            $query->andWhere (['order_status' => Order::STATUS_PENDING]);
        } else if ($type == 'abandoned') {
            $query->andWhere (['order_status' => Order::STATUS_ABANDONED_CHECKOUT]);
        } else if ($type == 'draft') {
            $query->andWhere (['order_status' => Order::STATUS_DRAFT]);
        }

        $query->andWhere(['restaurant_uuid' => $store_uuid]);

        if ($keyword) {
            $query->andWhere (
                ['or',
                    ['like', 'business_location_name', $keyword],
                    ['like', 'payment_method_name', $keyword],
                    ['like', 'order_uuid', $keyword],
                    ['like', 'customer_name', $keyword],
                    ['like', 'customer_phone_number', $keyword],
                ]
            );
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * return active order count
     * @return ActiveDataProvider
     */
    public function actionTotalActive()
    {
        $store = Yii::$app->accountManager->getManagedAccount ();

        $totalActiveOrders = Order::find ()
            ->andWhere ([
                'in',
                'order_status',
                [
                    Order::STATUS_PENDING,
                    Order::STATUS_BEING_PREPARED,
                    Order::STATUS_OUT_FOR_DELIVERY,
                    Order::STATUS_COMPLETE,
                    Order::STATUS_ACCEPTED
                ]
            ])
            ->andWhere (['restaurant_uuid' => $store->restaurant_uuid])
            ->count();

        $latestOrder = Order::find ()
            ->andWhere ([
                'in',
                'order_status',
                [
                    Order::STATUS_PENDING,
                    Order::STATUS_BEING_PREPARED,
                    Order::STATUS_OUT_FOR_DELIVERY,
                    Order::STATUS_COMPLETE,
                    Order::STATUS_ACCEPTED
                ]
            ])
            ->andWhere (['restaurant_uuid' => $store->restaurant_uuid])
            ->orderBy (['order_created_at' => SORT_DESC])
            ->one();

        return [
            'totalActiveOrders' => (int) $totalActiveOrders,
            'latestOrderId' => $latestOrder? $latestOrder->order_uuid: null
        ];
    }

    /**
     * Place an order
     */
    public function actionPlaceAnOrder($store_uuid)
    {

        $store_model = Yii::$app->accountManager->getManagedAccount ($store_uuid);


        $order = new Order();

        $order->order_status = Order::STATUS_DRAFT;
        $order->restaurant_uuid = $store_model->restaurant_uuid;

        //Save Customer Info
        $order->customer_name = Yii::$app->request->getBodyParam ("customer_name");
        $order->customer_phone_number = str_replace (' ', '', strval (Yii::$app->request->getBodyParam ("phone_number")));
        $order->customer_phone_country_code = Yii::$app->request->getBodyParam ("country_code") ? Yii::$app->request->getBodyParam ("country_code") : 965;
        $order->customer_email = Yii::$app->request->getBodyParam ("email"); //optional
        //payment method => cash
        $order->payment_method_id = 3;


        $order->order_mode = Yii::$app->request->getBodyParam ("order_mode");


        //Delivery the order ASAP
        $order->is_order_scheduled = Yii::$app->request->getBodyParam ("is_order_scheduled") ? Yii::$app->request->getBodyParam ("is_order_scheduled") : 0;


        //Apply promo code
        if (Yii::$app->request->getBodyParam ("voucher_id")) {
            $order->voucher_id = Yii::$app->request->getBodyParam ("voucher_id");
        }


        //if the order mode = 1 => Delivery
        if ($order->order_mode == Order::ORDER_MODE_DELIVERY) {

            //Deliver to Kuwait - GCC
            if (Yii::$app->request->getBodyParam ("area_id") && Yii::$app->request->getBodyParam ("area_delivery_zone")) {
                $order->delivery_zone_id = Yii::$app->request->getBodyParam ("delivery_zone_id");
                $order->area_id = Yii::$app->request->getBodyParam ("area_id");
                $order->unit_type = Yii::$app->request->getBodyParam ("unit_type");
                $order->block = Yii::$app->request->getBodyParam ("block");
                $order->street = Yii::$app->request->getBodyParam ("street");
                $order->avenue = Yii::$app->request->getBodyParam ("avenue"); //optional
                $order->house_number = Yii::$app->request->getBodyParam ("house_number");


                if (Yii::$app->request->getBodyParam ("floor") != null && ($order->unit_type == 'Apartment' || $order->unit_type == 'Office'))
                    $order->floor = Yii::$app->request->getBodyParam ("floor");

                if (Yii::$app->request->getBodyParam ("apartment") != null && $order->unit_type == 'Apartment')
                    $order->apartment = Yii::$app->request->getBodyParam ("apartment");

                if (Yii::$app->request->getBodyParam ("office") != null && $order->unit_type == 'Office')
                    $order->office = Yii::$app->request->getBodyParam ("office");

            } //Delivery other countries
            else if (Yii::$app->request->getBodyParam ("country_id") && !Yii::$app->request->getBodyParam ("area_id") && !Yii::$app->request->getBodyParam ("area_delivery_zone")) {

                $order->delivery_zone_id = Yii::$app->request->getBodyParam ("delivery_zone_id");
                $order->shipping_country_id = Yii::$app->request->getBodyParam ("country_id");
                $order->address_1 = Yii::$app->request->getBodyParam ('address_1');
                $order->address_2 = Yii::$app->request->getBodyParam ('address_2');
                $order->postalcode = Yii::$app->request->getBodyParam ('postal_code');
                $order->city = Yii::$app->request->getBodyParam ("city");
            }

            $order->special_directions = Yii::$app->request->getBodyParam ("special_directions"); //optional


        } else if ($order->order_mode == Order::ORDER_MODE_PICK_UP) {
            $order->pickup_location_id = Yii::$app->request->getBodyParam ("business_location_id");
        }


        $response = [];

        if ($order->save ()) {


            $items = Yii::$app->request->getBodyParam ("items");


            if ($items) {

                foreach ($items as $item) {

                    //Save items to the above order
                    $orderItem = new OrderItem;

                    $orderItem->order_uuid = $order->order_uuid;
                    $orderItem->item_uuid = $item["item_uuid"];
                    $orderItem->qty = (int)$item["qty"];


                    //optional field
                    if (array_key_exists ("customer_instructions", $item) && $item["customer_instructions"] != null)
                        $orderItem->customer_instruction = $item["customer_instructions"];

                    if ($orderItem->save ()) {

                        // There seems to be an issue with your payment, please try again.
                        if (array_key_exists ('extraOptions', $item)) {


                            $extraOptionsArray = $item['extraOptions'];


                            if (isset($extraOptionsArray) && count ($extraOptionsArray) > 0) {

                                foreach ($extraOptionsArray as $key => $extraOption) {

                                    $orderItemExtraOption = new OrderItemExtraOption;
                                    $orderItemExtraOption->order_item_id = $orderItem->order_item_id;
                                    $orderItemExtraOption->extra_option_id = $extraOption['extra_option_id'];
                                    $orderItemExtraOption->qty = (int)$item["qty"];

                                    if (!$orderItemExtraOption->save ()) {

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
                            'message' => $orderItem->getErrors ()
                        ];
                    }
                }
            } else {
                $response = [
                    'operation' => 'error',
                    'message' => Yii::t('agent', 'Item Uuid is invalid.')
                ];
            }
        } else {
            $response = [
                'operation' => 'error',
                'message' => $order->getErrors (),
            ];
        }

        if (!$order->is_order_scheduled && !$store_model->isOpen ()) {
            $response = [
                'operation' => 'error',
                'message' => Yii::t('agent',  '{store} is currently closed and is not accepting orders at this time', [
                    'store' => $store_model->name
                ])
            ];
        }

        if ($response == null) {

            $order->updateOrderTotalPrice ();

            if ($order->order_mode == Order::ORDER_MODE_DELIVERY && $order->subtotal < $order->deliveryZone->min_charge) {
                $response = [
                    'operation' => 'error',
                    'message' => Yii::t('agent', 'Minimum order amount {amount}', [
                        'amount' => Yii::$app->formatter->asCurrency ($order->deliveryZone->min_charge, $order->currency->code, [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => 10])
                    ])
                ];
            }

            if ($response == null) {

                $response = [
                    'operation' => 'success',
                    "model" => Order::findOne ($order->order_uuid),
                    'message' => Yii::t('agent','Order created successfully')
                ];
            }
        }

        if (array_key_exists ('operation', $response) && $response['operation'] == 'error') {
            if (!isset($response['message']['qty']))
                \Yii::error (json_encode ($response['message']), __METHOD__); // Log error faced by user

            $order->delete ();
        }

        return $response;
    }

    /**
     * Update Order
     */
    public function actionUpdate($order_uuid, $store_uuid)
    {
        $order = $this->findModel ($order_uuid, $store_uuid);

        //Save Customer Info
        $order->customer_name = Yii::$app->request->getBodyParam ("customer_name");
        $order->customer_phone_number = str_replace (' ', '', strval (Yii::$app->request->getBodyParam ("phone_number")));
        $order->customer_phone_country_code = Yii::$app->request->getBodyParam ("country_code") ? Yii::$app->request->getBodyParam ("country_code") : 965;
        $order->customer_email = Yii::$app->request->getBodyParam ("email"); //optional

        $order->order_mode = Yii::$app->request->getBodyParam ("order_mode");

        //Apply promo code
        if (Yii::$app->request->getBodyParam ("voucher_id")) {
            $order->voucher_id = Yii::$app->request->getBodyParam ("voucher_id");
        }

        //if the order mode = 1 => Delivery
        if ($order->order_mode == Order::ORDER_MODE_DELIVERY) {

            //Deliver to Kuwait - GCC
            if (Yii::$app->request->getBodyParam ("area_id") && Yii::$app->request->getBodyParam ("area_delivery_zone")) {
                $order->delivery_zone_id = Yii::$app->request->getBodyParam ("delivery_zone_id");
                $order->area_id = Yii::$app->request->getBodyParam ("area_id");
                $order->unit_type = Yii::$app->request->getBodyParam ("unit_type");
                $order->block = Yii::$app->request->getBodyParam ("block");
                $order->street = Yii::$app->request->getBodyParam ("street");
                $order->avenue = Yii::$app->request->getBodyParam ("avenue"); //optional
                $order->house_number = Yii::$app->request->getBodyParam ("house_number");

                if (Yii::$app->request->getBodyParam ("floor") != null && ($order->unit_type == 'Apartment' || $order->unit_type == 'Office'))
                    $order->floor = Yii::$app->request->getBodyParam ("floor");

                if (Yii::$app->request->getBodyParam ("apartment") != null && $order->unit_type == 'Apartment')
                    $order->apartment = Yii::$app->request->getBodyParam ("apartment");

                if (Yii::$app->request->getBodyParam ("office") != null && $order->unit_type == 'Office')
                    $order->office = Yii::$app->request->getBodyParam ("office");

            } //Delivery other countries
            else if (Yii::$app->request->getBodyParam ("country_id") && !Yii::$app->request->getBodyParam ("area_id") && !Yii::$app->request->getBodyParam ("area_delivery_zone")) {

                $order->delivery_zone_id = Yii::$app->request->getBodyParam ("delivery_zone_id");
                $order->shipping_country_id = Yii::$app->request->getBodyParam ("country_id");
                $order->address_1 = Yii::$app->request->getBodyParam ('address_1');
                $order->address_2 = Yii::$app->request->getBodyParam ('address_2');
                $order->postalcode = Yii::$app->request->getBodyParam ('postal_code');
                $order->city = Yii::$app->request->getBodyParam ("city");
            }

            $order->special_directions = Yii::$app->request->getBodyParam ("special_directions"); //optional


        } else if ($order->order_mode == Order::ORDER_MODE_PICK_UP) {
            $order->pickup_location_id = Yii::$app->request->getBodyParam ("business_location_id");
        }

        $response = [];

        if ($order->save ()) {


            $items = Yii::$app->request->getBodyParam ("items");


            if ($items) {

                foreach ($items as $item) {

                    //Save items to the above order
                    $orderItem = new OrderItem;

                    $orderItem->order_uuid = $order->order_uuid;
                    $orderItem->item_uuid = $item["item_uuid"];
                    $orderItem->qty = (int)$item["qty"];


                    //optional field
                    if (array_key_exists ("customer_instructions", $item) && $item["customer_instructions"] != null)
                        $orderItem->customer_instruction = $item["customer_instructions"];

                    if ($orderItem->save ()) {

                        // There seems to be an issue with your payment, please try again.
                        if (array_key_exists ('extraOptions', $item)) {


                            $extraOptionsArray = $item['extraOptions'];


                            if (isset($extraOptionsArray) && count ($extraOptionsArray) > 0) {

                                foreach ($extraOptionsArray as $key => $extraOption) {

                                    $orderItemExtraOption = new OrderItemExtraOption;
                                    $orderItemExtraOption->order_item_id = $orderItem->order_item_id;
                                    $orderItemExtraOption->extra_option_id = $extraOption['extra_option_id'];
                                    $orderItemExtraOption->qty = (int)$item["qty"];

                                    if (!$orderItemExtraOption->save ()) {

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
                            'message' => $orderItem->getErrors ()
                        ];
                    }
                }
            } else {
                $response = [
                    'operation' => 'error',
                    'message' => Yii::t('agent', 'Item Uuid is invalid.')
                ];
            }
        } else {
            $response = [
                'operation' => 'error',
                'message' => $order->getErrors (),
            ];
        }


        if ($response == null) {

            $order->updateOrderTotalPrice ();

            if ($order->order_mode == Order::ORDER_MODE_DELIVERY && $order->subtotal < $order->deliveryZone->min_charge) {
                $response = [
                    'operation' => 'error',
                    'message' => Yii::t('agent', 'Minimum order amount {amount}', [
                        'amount' => Yii::$app->formatter->asCurrency ($order->deliveryZone->min_charge, $order->currency->code, [\NumberFormatter::MAX_SIGNIFICANT_DIGITS => 10])
                    ])
                ];
            }

            if ($response == null) {

                $response = [
                    'operation' => 'success',
                    'message' => Yii::t('agent','Order updated successfully'),
                    "model" => $order
                ];
            }
        }

        return $response;
    }

    /**
     * Update Order Status
     */
    public function actionUpdateOrderStatus($order_uuid, $store_uuid)
    {
        $model = $this->findModel ($order_uuid, $store_uuid);

        //Update order status
        $model->order_status = Yii::$app->request->getBodyParam ("order_status");

        if (!$model->save ()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent',"We've faced a problem updating the order status")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Order status updated successfully"),
            "model" => $model
        ];
    }

    /**
     * @param $order_uuid
     * @param $store_uuid
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionRequestDriverFromArmada($order_uuid, $store_uuid)
    {
        $armadaApiKey = Yii::$app->request->getBodyParam ("armada_api_key");

        $model = $this->findModel ($order_uuid, $store_uuid);

        $createDeliveryApiResponse = Yii::$app->armadaDelivery->createDelivery ($model, $armadaApiKey);


        if ($createDeliveryApiResponse->isOk) {

            $model->armada_tracking_link = $createDeliveryApiResponse->data['trackingLink'];
            $model->armada_qr_code_link = $createDeliveryApiResponse->data['qrCodeLink'];
            $model->armada_delivery_code = $createDeliveryApiResponse->data['code'];


        } else {

            return [
                "operation" => "error",
                "message" => Yii::t('agent',"We've faced a problem requesting driver from Armada")
            ];
        }


        if (!$model->save ()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent',"We've faced a problem requesting driver from Armada")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Your request has been successfully submitted")
        ];
    }

    /**
     * Request a driver from Mashkor
     * @param type $order_uuid
     * @param type $store_uuid
     */
    public function actionRequestDriverFromMashkor($order_uuid, $store_uuid)
    {
        $mashkorBranchId = Yii::$app->request->getBodyParam ("mashkor_branch_id");

        $model = $this->findModel ($order_uuid, $store_uuid);

        $createDeliveryApiResponse = Yii::$app->mashkorDelivery->createOrder ($model, $mashkorBranchId);

        if ($createDeliveryApiResponse->isOk) {

            $model->mashkor_order_number = $createDeliveryApiResponse->data['data']['order_number'];
            $model->mashkor_order_status = Order::MASHKOR_ORDER_STATUS_CONFIRMED;

        } else {

            return [
                "operation" => "error",
                "message" => Yii::t('agent',"We've faced a problem requesting driver from Mashkor")
            ];
        }

        if (!$model->save ()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent',"We've faced a problem requesting driver from Mashkor")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Your request has been successfully submitted")
        ];
    }

    /**
     * Return order detail
     * @param type $store_uuid
     * @param type $order_uuid
     * @return type
     */
    public function actionDetail($store_uuid, $order_uuid)
    {
        return $this->findModel ($order_uuid, $store_uuid);
    }

    /**
     * Delete Order
     */
    public function actionDelete($order_uuid, $store_uuid)
    {
        $model = $this->findModel ($order_uuid, $store_uuid);

        if (!$model->delete ()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent',"We've faced a problem deleting the order")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Order deleted successfully")
        ];
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionOrdersReport()
    {
        $store_model = Yii::$app->accountManager->getManagedAccount ();

        $start_date = Yii::$app->request->get('start_date');
        $end_date = Yii::$app->request->get('end_date');

        $query = \common\models\Order::find ()
            ->joinWith(['currency', 'paymentMethod', 'payment'])
            ->activeOrders ($store_model->restaurant_uuid)
            ->with ('voucher')
            ->orderBy (['order_created_at' => SORT_ASC]);

        if ($start_date && $end_date) {
            $query->andWhere (new Expression('DATE(order.order_created_at) >= DATE("'.$start_date.'") AND
                    DATE(order.order_created_at) <= DATE("'.$end_date.'")'));
        } else {
            $query->andWhere (new Expression('DATE(order_created_at) > DATE_SUB(now(), INTERVAL 3 MONTH)'));
        }

        $searchResult = $query->all ();

        header ('Access-Control-Allow-Origin: *');
        header ("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header ("Content-Disposition: attachment;filename=\"orders.xlsx\"");
        header ("Cache-Control: max-age=0");

        \moonland\phpexcel\Excel::export ([
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
                    'header' => Yii::t('agent','Order Mode'),
                    'value' => function ($data) {
                        return $data->order_mode == Order::ORDER_MODE_DELIVERY ? 'Delivery' : 'Pickup';
                    }
                ],
                [
                    'header' => Yii::t('agent','Area'),
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
                        else
                            return $data->paymentMethod->payment_method_name;
                    },
                ],
                [
                    'attribute' => 'Voucher Code',
                    "format" => "raw",
                    "value" => function ($data) {
                        if ($data->voucher_id)
                            return $data->voucher->code;
                        else
                            return '';
                    },
                ],

                [
                    'attribute' => 'delivery_fee',
                    "value" => function ($data) {
                        return \Yii::$app->formatter->asCurrency ($data->delivery_fee, $data->currency->code);
                    },
                ],

                [
                    'header' => Yii::t('agent','Amount Charged'),
                    'attribute' => 'total_price',
                    "value" => function ($data) {
                        return \Yii::$app->formatter->asCurrency ($data->payment_uuid ? $data->payment->payment_amount_charged : $data->total_price, $data->currency->code);
                    }
                ],

                [
                    'header' => Yii::t('agent','Net Amount'),
                    "value" => function ($data) {
                        if ($data->payment_uuid)
                            return \Yii::$app->formatter->asCurrency ($data->payment->payment_net_amount, $data->currency->code);
                        else
                            return \Yii::$app->formatter->asCurrency ($data->total_price, $data->currency->code);
                    }
                ],
                [
                    'header' => Yii::t('agent','Plugn fee'),
                    "value" => function ($data) {
                        if ($data->payment_uuid && $data->payment->plugn_fee){
                          $plugnFee = $data->payment->plugn_fee + $data->payment->partner_fee;
                          return \Yii::$app->formatter->asCurrency ($plugnFee, $data->currency->code);
                        }
                        else
                            return \Yii::$app->formatter->asCurrency (0, $data->currency->code);
                    }
                ],
                [
                    'header' => Yii::t('agent','Payment Gateway fee'),
                    "value" => function ($data) {
                        if ($data->payment_uuid)
                            return \Yii::$app->formatter->asCurrency ($data->payment->payment_gateway_fee, $data->currency->code);
                        else
                            return \Yii::$app->formatter->asCurrency (0, $data->currency->code);

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
    public function actionCreate($store_uuid) {

        $restaurant_model = Yii::$app->accountManager->getManagedAccount($store_uuid);

        $model = new Order();
        $model->setScenario(\common\models\Order::SCENARIO_CREATE_ORDER_BY_ADMIN);

        $model->restaurant_uuid = $restaurant_model->restaurant_uuid;
        $model->is_order_scheduled = 0;

        $order_mode = Yii::$app->request->getBodyParam('order_mode');
        $customer_name = Yii::$app->request->getBodyParam('customer_name');
        $area_name = Yii::$app->request->getBodyParam('area_name');
        $area_id = Yii::$app->request->getBodyParam('area_id');
        $unit_type = Yii::$app->request->getBodyParam('unit_type');
        $street = Yii::$app->request->getBodyParam('street');
        $avenue = Yii::$app->request->getBodyParam('avenue');
        $building = Yii::$app->request->getBodyParam('building');
        $block = Yii::$app->request->getBodyParam('block');
        $customer_email = Yii::$app->request->getBodyParam('customer_email');
        $customer_phone_country_code = Yii::$app->request->getBodyParam('customer_phone_country_code');
        $customer_phone_number = Yii::$app->request->getBodyParam('customer_phone_number');
        $pickup_location_id = Yii::$app->request->getBodyParam('pickup_location_id');
        $estimated_time_of_arrival = Yii::$app->request->getBodyParam('estimated_time_of_arrival');
        $special_directions = Yii::$app->request->getBodyParam('special_directions');

        $model->order_mode = $order_mode;
        $model->customer_name= $customer_name;
        $model->area_name = $area_name;
        $model->area_id = $area_id;
        $model->unit_type = $unit_type;
        $model->street = $street;
        $model->avenue = $avenue;
        $model->house_number = $building;
        $model->block = $block;
        $model->customer_email = $customer_email;
        $model->customer_phone_country_code = $customer_phone_country_code;
        $model->customer_phone_number = $customer_phone_number;
        $model->pickup_location_id = $pickup_location_id;
        $model->estimated_time_of_arrival = date ("Y-m-d H:i:s", strtotime ($estimated_time_of_arrival));
        $model->special_directions = $special_directions;

        if($model->order_mode == Order::ORDER_MODE_DELIVERY){

            $areaDeliveryZone = AreaDeliveryZone::find()
                ->andWhere([
                    'restaurant_uuid' => $restaurant_model->restaurant_uuid,
                    'area_id' => $model->area_id
                ])
                ->one();

            if($areaDeliveryZone)
                $model->delivery_zone_id = $areaDeliveryZone->delivery_zone_id;
        }

        if (!$model->save ()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent',"We've faced a problem created the order")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Order created successfully")
        ];
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($order_uuid, $store_uuid)
    {
        $store_model = Yii::$app->accountManager->getManagedAccount ($store_uuid);

        if (($model = Order::find ()->where (['order_uuid' => $order_uuid, 'restaurant_uuid' => $store_model->restaurant_uuid])->one ()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
