<?php

namespace frontend\controllers;

use Yii;
use common\models\Order;
use common\models\Refund;
use common\models\AreaDeliveryZone;
use common\models\RefundedItem;
use frontend\models\OrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Customer;
use common\models\Restaurant;
use common\models\Payment;
use common\models\PaymentMethod;
use common\models\BusinessLocation;
use kartik\mpdf\Pdf;
use yii\helpers\Html;
use yii\base\Model;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller
{

    public $enableCsrfValidation = false;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [//allow authenticated users only
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex($storeUuid)
    {

        $restaurant = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $agentAssignment = $restaurant->getAgentAssignments()
            ->where([
                'restaurant_uuid' => $restaurant->restaurant_uuid,
                'agent_id' => Yii::$app->user->identity->agent_id
            ])->one();

        $searchModel = new OrderSearch();
        
        $count = $searchModel->search([], $restaurant->restaurant_uuid, $agentAssignment)->getCount();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $restaurant->restaurant_uuid, $agentAssignment);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'count' => $count,
            'dataProvider' => $dataProvider,
            'restaurant' => $restaurant
        ]);
    }

    public function actionDownloadPendingOrdersForMashkor($storeUuid)
    {
        $store = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $orders = Order::find()
            ->where(['order_status' => Order::STATUS_PENDING])
            ->andWhere(['<>', 'payment_method_id', 3])
            ->andWhere(['restaurant_uuid' => $store->restaurant_uuid])
            ->andWhere(['country_name' => 'Kuwait'])
            ->orderBy(['order_created_at' => SORT_ASC])
            ->all();

        header('Access-Control-Allow-Origin: *');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment;filename=\"orders.xlsx\"");
        header("Cache-Control: max-age=0");

        \moonland\phpexcel\Excel::export([
            'isMultipleSheet' => false,
            'models' => $orders,
            'columns' => [

                [
                    'attribute' => 'customer_name',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->customer_name;
                    },
                ],

                [
                    'header' => 'Customer Phone number',
                    'attribute' => 'customer_phone_number',
                    "format" => "raw",
                    "value" => function ($model) {
                        return $model->customer_phone_number;
                    }
                ],
                [
                    'header' => 'Vendor Order ID',
                    'attribute' => 'order_uuid',
                    "format" => "raw",
                    "value" => function ($model) {
                        return '#' . $model->order_uuid;
                    }
                ],
                [
                    'header' => 'COD',
                    'attribute' => 'order_uuid',
                    "format" => "raw",
                    "value" => function ($model) {
                        return 'No';
                    }
                ],

                [
                    'header' => 'Area',
                    // 'format' => 'html',
                    'value' => function ($data) {
                        if ($data->area_id)
                            return $data->area_name;
                    }
                ],
                [
                    'header' => 'Block',
                    // 'format' => 'html',
                    'value' => function ($data) {
                        if ($data->block)
                            return $data->block;
                    }
                ],
                [
                    'header' => 'Street',
                    // 'format' => 'html',
                    'value' => function ($data) {
                        if ($data->street)
                            return $data->street;
                    }
                ],
                [
                    'header' => 'Avenue',
                    // 'format' => 'html',
                    'value' => function ($data) {
                        return $data->avenue ? $data->avenue : '-----';
                    }
                ],
                [
                    'header' => 'Unit Type',
                    // 'format' => 'html',
                    'value' => function ($data) {
                        if ($data->unit_type)
                            return $data->unit_type;
                    }
                ],
                [
                    'header' => 'Floor',
                    // 'format' => 'html',
                    'value' => function ($data) {
                        return $data->floor ? $data->floor : '-----';
                    }
                ],
                [
                    'header' => 'Apartment',
                    // 'format' => 'html',
                    'value' => function ($data) {
                        return $data->apartment ? $data->apartment : '-----';
                    }
                ],
                [
                    'header' => 'Office',
                    // 'format' => 'html',
                    'value' => function ($data) {
                        return $data->office ? $data->office : '-----';
                    }
                ],
                [
                    'header' => 'House number/Building',
                    // 'format' => 'html',
                    'value' => function ($data) {
                        if ($data->house_number)
                            return $data->house_number;
                    }
                ],
                [
                    'header' => 'Extra Instruction',
                    // 'format' => 'html',
                    'value' => function ($data) {
                        if ($data->house_number)
                            return $data->special_directions;
                    }
                ],

            ]
        ]);

        return $this->redirect(['index', 'storeUuid' => $store->restaurant_uuid]);

    }


    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionOrdersReport($storeUuid)
    {
        $store = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $searchModel = new OrderSearch();

        if ($store->load(Yii::$app->request->post())) {

            list($start_date, $end_date) = explode(' - ', $store->export_orders_data_in_specific_date_range);

            $searchResult = Order::find()
                ->activeOrders($storeUuid)
                ->with('voucher')
                ->andWhere(['between', 'order_created_at', $start_date, $end_date])
                ->orderBy(['order_created_at' => SORT_ASC])
                ->all();

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
                        'header' => 'Order Mode',
                        'value' => function ($data) {
                            return $data->order_mode == Order::ORDER_MODE_DELIVERY ? 'Delivery' : 'Pickup';
                        }
                    ],
                    [
                        'header' => 'Area',
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
                            return \Yii::$app->formatter->asCurrency($data->delivery_fee, $data->currency->code, [
                                \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                            ]);
                        },
                    ],

                    [
                        'header' => 'Amount Charged',
                        'attribute' => 'total_price',
                        "value" => function ($data) {
                            return \Yii::$app->formatter->asCurrency($data->payment_uuid ? $data->payment->payment_amount_charged : $data->total_price, $data->currency->code, [
                                \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                            ]);
                        }
                    ],

                    [
                        'header' => 'Net Amount',
                        "value" => function ($data) {
                            if ($data->payment_uuid)
                                return \Yii::$app->formatter->asCurrency($data->payment->payment_net_amount, $data->currency->code, [
                                    \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                                ]);
                            else
                                return \Yii::$app->formatter->asCurrency($data->total_price, $data->currency->code, [
                                    \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                                ]);
                        }
                    ],
                    [
                        'header' => 'Plugn fee',
                        "value" => function ($data) {
                            if ($data->payment_uuid && $data->payment->plugn_fee) {
                                $plugnFee = $data->payment->plugn_fee + $data->payment->partner_fee;
                                return \Yii::$app->formatter->asCurrency($plugnFee, $data->currency->code, [
                                    \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                                ]);
                            } else
                                return \Yii::$app->formatter->asCurrency(0, $data->currency->code, [
                                    \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                                ]);
                        }
                    ],
                    [
                        'header' => 'Payment Gateway fee',
                        "value" => function ($data) {
                            if ($data->payment_uuid)
                                return \Yii::$app->formatter->asCurrency($data->payment->payment_gateway_fee, $data->currency->code);
                            else
                                return \Yii::$app->formatter->asCurrency(0, $data->currency->code, [
                                    \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                                ]);

                        }
                    ],

                    'order_created_at'
                ]
            ]);
        }

        return $this->render('orders-report', [
            'model' => $store
        ]);
    }

    /**
     * Lists all draft Orders.
     * @return mixed
     */
    public function actionDraft($storeUuid)
    {
        $restaurant = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $agentAssignment = $restaurant->getAgentAssignments()
            ->where([
                'restaurant_uuid' => $restaurant->restaurant_uuid,
                'agent_id' => Yii::$app->user->identity->agent_id
            ])->one();

        $searchModel = new OrderSearch();
        
        $count = $searchModel->searchDraftOrders([], $restaurant->restaurant_uuid, $agentAssignment)->getCount();

        $dataProvider = $searchModel->searchDraftOrders(Yii::$app->request->queryParams, $restaurant->restaurant_uuid, $agentAssignment);

        return $this->render('draft', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'count' => $count,
            'restaurant' => $restaurant
        ]);
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionAbandonedCheckout($storeUuid)
    {
        $restaurant = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $agentAssignment = $restaurant->getAgentAssignments()
            ->where(['restaurant_uuid' => $restaurant->restaurant_uuid, 'agent_id' => Yii::$app->user->identity->agent_id])->one();

        $searchModel = new OrderSearch();
        
        $count = $searchModel->searchAbandonedCheckoutOrders([], $restaurant->restaurant_uuid, $agentAssignment)->getCount();

        $dataProvider = $searchModel->searchAbandonedCheckoutOrders(Yii::$app->request->queryParams, $restaurant->restaurant_uuid, $agentAssignment);

        return $this->render('abandoned-checkout', [
            'searchModel' => $searchModel,
            'count' => $count,
            'dataProvider' => $dataProvider,
            'restaurant' => $restaurant
        ]);
    }


    /**
     * Request fulfillment
     * @param type $order_uuid
     * @param type $storeUuid
     * @param type $customerId
     */
    public function actionRequestFulfillment($order_uuid, $storeUuid)
    {

        $order = $this->findModel($order_uuid, $storeUuid);

        $requestFulfillmentApiResponse = Yii::$app->diggipacksWarehouseComponent->createOrder($order, $order->orderItems);


        if ($requestFulfillmentApiResponse->isOk) {

            $order->diggipack_awb_no = $requestFulfillmentApiResponse->data['awb_no'];

            $order->save(false);
            Yii::$app->session->setFlash('successResponse', "Fulfillment requested");

        } else {

            if ($requestFulfillmentApiResponse->content) {
                Yii::$app->session->setFlash('errorResponse', json_encode($requestFulfillmentApiResponse->content));
                Yii::error('Error while requesting fulfillment  [' . $order->restaurant->name . '] ' . json_encode($requestFulfillmentApiResponse->content));

            } else {

                Yii::$app->session->setFlash('errorResponse', "Sorry, we couldn't achieve your request at the moment. Please try again later, or contact our customer support.");
                Yii::error('Error while requesting fulfillment  [' . $order->restaurant->name . '] ' . json_encode($requestFulfillmentApiResponse));

            }

            return $this->redirect(['view', 'id' => $order_uuid, 'storeUuid' => $storeUuid]);
        }

        return $this->redirect(['view', 'id' => $order_uuid, 'storeUuid' => $storeUuid]);
    }


    /**
     * Request a driver from Mashkor
     * @param type $order_uuid
     * @param type $storeUuid
     */
    public function actionRequestDriverFromMashkor($order_uuid, $storeUuid, $mashkorBranchId)
    {

        $order = $this->findModel($order_uuid, $storeUuid);

        $createDeliveryApiResponse = Yii::$app->mashkorDelivery->createOrder($order, $mashkorBranchId);


        if ($createDeliveryApiResponse->isOk) {

            $order->mashkor_order_number = $createDeliveryApiResponse->data['data']['order_number'];
            $order->mashkor_order_status = Order::MASHKOR_ORDER_STATUS_CONFIRMED;
            $order->save(false);

            Yii::$app->session->setFlash('successResponse', "Your request has been successfully submitted");

        } else {

            if ($response = $createDeliveryApiResponse->data) {

                if (array_key_exists('message', $response)) {
                    $errorMessage =
                        str_replace(array('\'', '"', ',', ';', '<', '>'), ' ', $response['message']);

                    Yii::$app->session->setFlash('errorResponse', json_encode($errorMessage));

                } else
                    Yii::$app->session->setFlash('errorResponse', json_encode($createDeliveryApiResponse->data));

            } else
                Yii::$app->session->setFlash('errorResponse', "Sorry, we couldn't achieve your request at the moment. Please try again later, or contact our customer support.");


            Yii::error('Error while requesting driver from Mashkor  [' . $order->restaurant->name . '] ' . json_encode($createDeliveryApiResponse->data));

            return $this->redirect(['view', 'id' => $order_uuid, 'storeUuid' => $storeUuid]);
        }

        return $this->redirect(['view', 'id' => $order_uuid, 'storeUuid' => $storeUuid]);
    }


    /**
     * Request a driver from Armada
     * @param type $order_uuid
     * @param type $storeUuid
     */
    public function actionRequestDriverFromArmada($order_uuid, $storeUuid, $armadaApiKey)
    {

        $order = $this->findModel($order_uuid, $storeUuid);

        $createDeliveryApiResponse = Yii::$app->armadaDelivery->createDelivery($order, $armadaApiKey);


        if ($createDeliveryApiResponse->isOk) {

            $order->armada_tracking_link = $createDeliveryApiResponse->data['trackingLink'];
            $order->armada_qr_code_link = $createDeliveryApiResponse->data['qrCodeLink'];
            $order->armada_delivery_code = $createDeliveryApiResponse->data['code'];
            $order->armada_order_status = $createDeliveryApiResponse->data['orderStatus'];

            $order->save(false);
            Yii::$app->session->setFlash('successResponse', "Your request has been successfully submitted");

        } else {

            if ($createDeliveryApiResponse->content) {


                Yii::$app->session->setFlash('errorResponse', json_encode($createDeliveryApiResponse->content));
                Yii::error('Error while requesting driver from Armada  [' . $order->restaurant->name . '] ' . json_encode($createDeliveryApiResponse->content));

            } else {

                Yii::$app->session->setFlash('errorResponse', "Sorry, we couldn't achieve your request at the moment. Please try again later, or contact our customer support.");
                Yii::error('Error while requesting driver from Armada  [' . $order->restaurant->name . '] ' . json_encode($createDeliveryApiResponse));

            }

            return $this->redirect(['view', 'id' => $order_uuid, 'storeUuid' => $storeUuid]);
        }

        return $this->redirect(['view', 'id' => $order_uuid, 'storeUuid' => $storeUuid]);
    }


    /**
     * Update payment's status
     * @return mixed
     */
    public function actionUpdatePaymentStatus($id, $storeUuid)
    {

        try {
            $payment = Payment::findOne($id);

            if (($payment = Payment::find()->where(['payment_uuid' => $id, 'restaurant_uuid' => Yii::$app->accountManager->getManagedAccount($storeUuid)->restaurant_uuid])->one()) !== null) {

                if ($payment->payment_gateway_name == 'tap') {
                    $transaction_id = $payment->payment_gateway_transaction_id;
                    Payment::updatePaymentStatusFromTap($transaction_id, true);
                } else if ($payment->payment_gateway_name == 'myfatoorah') {
                    $invoice_id = $payment->payment_gateway_invoice_id;
                    Payment::updatePaymentStatusFromMyFatoorah($invoice_id, true);
                }

                return $this->redirect(['view', 'id' => $payment->order_uuid, 'storeUuid' => $storeUuid]);
            }

        } catch (\Exception $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    /**
     * Change order status
     *
     * @param type $order_uuid
     * @param type $storeUuid
     * @param type $status
     * @return type
     */
    public function actionChangeOrderStatus($order_uuid, $storeUuid, $status, $redirect = null)
    {
        $order = $this->findModel($order_uuid, $storeUuid);

        $previousOrderStatus = $order->order_status;

        $order->order_status = $status;

        if ($order->save(false)) {

            if ($previousOrderStatus == Order::STATUS_DRAFT && $order->order_status == Order::STATUS_PENDING) {
                $order->sendPaymentConfirmationEmail();
            }
        }

        if ($redirect)
            return $this->redirect(['index', 'storeUuid' => $storeUuid]);

        else
            return $this->redirect(['view', 'id' => $order->order_uuid, 'storeUuid' => $storeUuid]);
    }

    /**
     * Change order status
     *
     * @param type $order_uuid
     * @param type $storeUuid
     * @param type $status
     * @return type
     */
    public function actionViewInvoice($order_uuid, $storeUuid)
    {
        $order = $this->findModel($order_uuid, $storeUuid);

        // Item
        $orderItems = new \yii\data\ActiveDataProvider([
            'query' => $order->getOrderItems()->with(['currency']),
            'sort' => false,
        ]);

        // Item extra optn
        // $itemsExtraOpitons = new \yii\data\ActiveDataProvider([
        //     'query' => $order->getOrderItemExtraOptions()
        // ]);


        return $this->render('invoice', [
            'model' => $order,
            'orderItems' => $orderItems
        ]);
    }

    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $storeUuid)
    {

        $order = Order::find()->where(['order_uuid' => $id, 'restaurant_uuid' => Yii::$app->accountManager->getManagedAccount($storeUuid)->restaurant_uuid])->with(['currency', 'country', 'deliveryZone.country', 'pickupLocation', 'deliveryZone.businessLocation'])->one();


        if ($order) {

            // Item
            $orderItems = new \yii\data\ActiveDataProvider([
                'query' => $order->getOrderItems()->with(['orderItemExtraOptions', 'item', 'currency']),
                'sort' => false,
                'pagination' => false
            ]);

            // Item extra optn
            // $itemsExtraOpitons = new \yii\data\ActiveDataProvider([
            //     'query' => $order->getOrderItemExtraOptions(),
            //     'pagination' => false
            // ]);

            // order's Item
            $refundDataProvider = new \yii\data\ActiveDataProvider([
                'query' => $order->getRefunds(),
                'sort' => false
            ]);

            // order's Item
            $refundItemsDataProvider = new \yii\data\ActiveDataProvider([
                'query' => $order->getRefundedItems()->with('item'),
                'sort' => false
            ]);


            return $this->render('view', [
                'model' => $order,
                'refundDataProvider' => $refundDataProvider,
                'storeUuid' => $storeUuid,
                'refundItemsDataProvider' => $refundItemsDataProvider,
                'orderItems' => $orderItems
            ]);


        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($storeUuid)
    {

        $restaurant = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $model = new Order();
        $model->setScenario(Order::SCENARIO_CREATE_ORDER_BY_ADMIN);

        //as we will calculate after items get saved
        $model->total_price = 0;
        $model->subtotal = 0;

        $model->restaurant_uuid = $restaurant->restaurant_uuid;
        $model->is_order_scheduled = 0;

        if ($model->load(Yii::$app->request->post())) {
            // $model->payment_method_id = 3;

            if ($model->order_mode == Order::ORDER_MODE_DELIVERY) {

                $areaDeliveryZone = AreaDeliveryZone::find()->andWhere(['restaurant_uuid' => $restaurant->restaurant_uuid, 'area_id' => $model->area_id])->one();

                if ($areaDeliveryZone)
                    $model->delivery_zone_id = $areaDeliveryZone->delivery_zone_id;

            }


            if ($model->validate() && $model->save())
                return $this->redirect(['update', 'id' => $model->order_uuid, 'storeUuid' => $storeUuid]);

        }

        return $this->render('create', [
            'model' => $model,
            'restaurant' => $restaurant
        ]);
    }


    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $storeUuid)
    {
        $restaurant = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $model = $this->findModel($id, $storeUuid);
        $model->setScenario(Order::SCENARIO_CREATE_ORDER_BY_ADMIN);


        // order's Item
        $ordersItemDataProvider = new \yii\data\ActiveDataProvider([
            'query' => $model->getOrderItems(),
            'pagination' => false

        ]);


        if ($model->load(Yii::$app->request->post())) {

            if ($model->order_mode == Order::ORDER_MODE_DELIVERY) {
                $model->pickup_location_id = null;
                if ($areaDeliveryZone = AreaDeliveryZone::find()->where(['restaurant_uuid' => $restaurant->restaurant_uuid, 'area_id' => $model->area_id])->one())
                    $model->delivery_zone_id = $areaDeliveryZone->delivery_zone_id;

            } else {
                $model->delivery_zone_id = null;
                $model->area_id = null;
            }


            if ($model->validate() && $model->save())
                return $this->redirect(['update', 'id' => $model->order_uuid, 'storeUuid' => $storeUuid]);

        }


        return $this->render('update', [
            'model' => $model,
            'ordersItemDataProvider' => $ordersItemDataProvider,
            'restaurant' => $restaurant
        ]);
    }


    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRefundOrder($order_uuid, $storeUuid)
    {

        $order = $this->findModel($order_uuid, $storeUuid);

        if (($order->payment_uuid && !$order->payment->payment_gateway_invoice_id && !$order->payment->payment_gateway_transaction_id) || !$order->payment_uuid) {
            return $this->redirect(['view', 'id' => $order_uuid, 'storeUuid' => $storeUuid]);
        }

        $refunded_items = [new RefundedItem()];

        foreach ($order->getOrderItems()->all() as $key => $orderItem) {
            $refunded_items[$key] = new RefundedItem();
            $refunded_items[$key]->order_item_id = $orderItem->order_item_id;
            $refunded_items[$key]->order_uuid = $orderItem->order_uuid;
            $refunded_items[$key]->item_uuid = $orderItem->item_uuid;
            $refunded_items[$key]->item_name = $orderItem->item_name;
            $refunded_items[$key]->item_name_ar = $orderItem->item_name_ar;
            $refunded_items[$key]->item_price = $orderItem->item_price;
        }


        $model = new Refund();
        $model->setScenario('create');
        $model->restaurant_uuid = $order->restaurant_uuid;
        $model->payment_uuid = $order->payment_uuid;
        $model->order_uuid = $order->order_uuid;

        if (Model::loadMultiple($refunded_items, Yii::$app->request->post()) && $model->load(Yii::$app->request->post()) && $model->save()) {


            foreach ($refunded_items as $key => $refunded_item) {
                if ($refunded_item->qty > 0) {
                    $refunded_item->refund_id = $model->refund_id;
                    $refunded_item->save();

                }

            }

            return $this->redirect(['view', 'id' => $order_uuid, 'storeUuid' => $storeUuid]);

        }


        return $this->render('refund-order', [
            'model' => $model,
            'order' => $order,
            'refunded_items' => $refunded_items
        ]);
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $storeUuid)
    {
        $model = $this->findModel($id, $storeUuid);

        $model->setScenario(Order::SCENARIO_DELETE);

        $transaction = Yii::$app->db->beginTransaction();

        $model->restockItems();

        $model->is_deleted = 1;

        if (!$model->save()) {

            $transaction->rollBack();

            if (isset($model->errors)) {

                Yii::$app->session->setFlash('errorResponse', "We've faced a problem deleting the order");
                Yii::error('Error while deleting the order   [' . $model->restaurant->name . '] ' . json_encode($model->errors));

            } else {

                Yii::$app->session->setFlash('errorResponse', "We've faced a problem deleting the order");
                Yii::error('Error while deleting the order   [' . $model->restaurant->name . '] ');

            }
        } else {
            $transaction->commit();
        }

        return $this->redirect(['index', 'storeUuid' => $storeUuid]);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $storeUuid)
    {
        if (($model = Order::find()->where(['order_uuid' => $id, 'restaurant_uuid' => Yii::$app->accountManager->getManagedAccount($storeUuid)->restaurant_uuid])->with(['restaurant', 'currency', 'country', 'deliveryZone.country'])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
