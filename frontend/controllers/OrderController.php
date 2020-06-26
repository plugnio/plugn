<?php

namespace frontend\controllers;

use Yii;
use common\models\Order;
use common\models\Refund;
use common\models\RefundedItem;
use frontend\models\OrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Customer;
use common\models\Restaurant;
use common\models\PaymentMethod;
use kartik\mpdf\Pdf;
use yii\helpers\Html;
use yii\base\Model;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller {

    public $enableCsrfValidation = false;

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
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
    public function actionIndex($restaurantUuid) {

        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $restaurant_model->restaurant_uuid);


        if ($restaurant_model->load(Yii::$app->request->post())) {

            list($start_date, $end_date) = explode(' - ', $restaurant_model->date_range_picker_with_time);


            $searchResult = Order::find()
                    ->where(['restaurant_uuid' => $restaurant_model->restaurant_uuid])
                    ->andWhere(['between', 'order_created_at', $start_date, $end_date])
                    ->andWhere([ '!=' , 'order_status' , Order::STATUS_DRAFT])
                    ->andWhere([ '!=' , 'order_status' , Order::STATUS_ABANDONED_CHECKOUT])
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
                        "value" => function($model) {
                            return '#' . $model->order_uuid;
                        }
                    ],
                    [
                        'attribute' => 'customer_name',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return $data->customer->customer_name;
                        },
                    ],
                    [
                        'attribute' => 'order_status',
                        "format" => "raw",
                        "value" => function($model) {
                            if ($model->order_status == Order::STATUS_PENDING)
                                return $model->orderStatus;
                            else if ($model->order_status == Order::STATUS_OUT_FOR_DELIVERY)
                                return $model->orderStatus;
                            else if ($model->order_status == Order::STATUS_BEING_PREPARED)
                                return $model->orderStatus;
                            else if ($model->order_status == Order::STATUS_COMPLETE)
                                return $model->orderStatus;
                            else if ($model->order_status == Order::STATUS_CANCELED)
                                return $model->orderStatus;
                            else if ($model->order_status == Order::STATUS_REFUNDED)
                                return $model->orderStatus;
                            else if ($model->order_status == Order::STATUS_PARTIALLY_REFUNDED)
                                return $model->orderStatus;
                        }
                    ],
                    [
                        'label' => 'Payment',
                        "format" => "raw",
                        "value" => function($data) {
                            if ($data->payment_uuid)
                                return $data->payment->payment_current_status;
                            else
                                return $data->paymentMethod->payment_method_name;
                        },
                    ],
                    'total_price_before_refund:currency',
                    [
                        'attribute' => 'order_created_at',
                        "format" => "raw",
                        "value" => function($model) {
                            return Yii::$app->formatter->asRelativeTime($model->order_created_at);
                        }
                    ],
                ]
            ]);
        }

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'restaurant_model' => $restaurant_model
        ]);
    }

    /**
     * Lists all draft Orders.
     * @return mixed
     */
    public function actionDraft($restaurantUuid) {

        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->searchDraftOrders(Yii::$app->request->queryParams, $restaurant_model->restaurant_uuid);

        return $this->render('draft', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'restaurant_model' => $restaurant_model
        ]);
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionAbandonedCheckout($restaurantUuid) {

        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->searchAbandonedCheckoutOrders(Yii::$app->request->queryParams, $restaurant_model->restaurant_uuid);



        return $this->render('abandoned-checkout', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'restaurant_model' => $restaurant_model
        ]);
    }

    /**
     * Request a driver from Armada
     * @param type $order_uuid
     * @param type $restaurantUuid
     */
    public function actionRequestDriverFromArmada($order_uuid, $restaurantUuid) {

        $order_model = $this->findModel($order_uuid, $restaurantUuid);

        $createDeliveryApiResponse = Yii::$app->armadaDelivery->createDelivery($order_model);

        $errorMessage = null;
        $successMessage = null;

        if ($createDeliveryApiResponse->isOk) {
            $order_model->tracking_link = $createDeliveryApiResponse->data['trackingLink'];
            $order_model->save(false);
            $successMessage = 'Your request has been successfully submitted';
        } else {

            if ($createDeliveryApiResponse->client)
                $errorMessage = 'Invalid api key';
            else if ($createDeliveryApiResponse->data['errors'])
                $errorMessage = json_encode($createDeliveryApiResponse->data['errors'][0]['description'], true);



            return $this->redirect(['view', 'id' => $order_uuid, 'restaurantUuid' => $restaurantUuid, 'errorMessage' => $errorMessage]);
        }

        return $this->redirect(['view', 'id' => $order_uuid, 'restaurantUuid' => $restaurantUuid, 'errorMessage' => $errorMessage, 'successMessage' => $successMessage,]);
    }

    /**
     * Change order status
     *
     * @param type $order_uuid
     * @param type $restaurantUuid
     * @param type $status
     * @return type
     */
    public function actionViewInvoice($order_uuid, $restaurantUuid) {
        $order_model = $this->findModel($order_uuid, $restaurantUuid);

        // Item
        $orderItems = new \yii\data\ActiveDataProvider([
            'query' => $order_model->getOrderItems(),
            'sort' => false
        ]);

        // Item extra optn
        $itemsExtraOpitons = new \yii\data\ActiveDataProvider([
            'query' => $order_model->getOrderItemExtraOptions()
        ]);


        return $this->render('invoice', [
                    'model' => $order_model,
                    'orderItems' => $orderItems,
                    'itemsExtraOpitons' => $itemsExtraOpitons,
        ]);
    }

    /**
     * Change order status
     *
     * @param type $order_uuid
     * @param type $restaurantUuid
     * @param type $status
     * @return type
     */
    public function actionChangeOrderStatus($order_uuid, $restaurantUuid, $status) {
        $order_model = $this->findModel($order_uuid, $restaurantUuid);

        $order_model->order_status = $status;
        $order_model->save(false);

        return $this->redirect(['view', 'id' => $order_model->order_uuid, 'restaurantUuid' => $restaurantUuid]);
    }

    /**
     * Download a PDF  order's invoice
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDownloadInvoice($restaurantUuid, $order_uuid) {


        $order_model = $this->findModel($order_uuid, $restaurantUuid);

        // Item
        $orderItems = new \yii\data\ActiveDataProvider([
            'query' => $order_model->getOrderItems(),
            'sort' => false
        ]);

        // Item extra optn
        $itemsExtraOpitons = new \yii\data\ActiveDataProvider([
            'query' => $order_model->getOrderItemExtraOptions()
        ]);

        $this->layout = 'pdf';


        $content = $this->render('invoice', [
            'model' => $order_model,
            'orderItems' => $orderItems,
            'itemsExtraOpitons' => $itemsExtraOpitons
        ]);

//
//        $content = $this->render('invoice', [
//            'model' => $order_model,
//            'orderItems ' => $orderItems,
//        ]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // any css to be embedded if required
            'cssFile' => '@frontend/web/css/invoice_1.css',
            // set mPDF properties on the fly
            'options' => [], //['title' => 'Booking #'.$id],
        ]);

        header('Access-Control-Allow-Origin: *');
        return $pdf->render();
    }

    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $restaurantUuid, $errorMessage = null, $successMessage = null) {

        $order_model = $this->findModel($id, $restaurantUuid);


        // Item
        $orderItems = new \yii\data\ActiveDataProvider([
            'query' => $order_model->getOrderItems(),
            'sort' => false
        ]);

        // Item extra optn
        $itemsExtraOpitons = new \yii\data\ActiveDataProvider([
            'query' => $order_model->getOrderItemExtraOptions()
        ]);

        return $this->render('view', [
                    'model' => $order_model,
                    'orderItems' => $orderItems,
                    'errorMessage' => $errorMessage,
                    'successMessage' => $successMessage,
                    'itemsExtraOpitons' => $itemsExtraOpitons
        ]);
    }

    /**
     * Creates a new Order model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($restaurantUuid) {

        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

        $model = new Order();
        $model->setScenario(Order::SCENARIO_CREATE_ORDER_BY_ADMIN);

        $model->restaurant_uuid = $restaurant_model->restaurant_uuid;

        if ($model->load(Yii::$app->request->post())) {
            $model->payment_method_id = 3;



        // order's Item
        $ordersItemDataProvider = new \yii\data\ActiveDataProvider([
            'query' => $model->getOrderItems()
        ]);



            if ($model->validate() && $model->save()) {
                return $this->render('update', [
                            'model' => $model,
                            'ordersItemDataProvider' => $ordersItemDataProvider,
                            'restaurant_model' => $restaurant_model
                ]);
            }
        }

        return $this->render('create', [
                    'model' => $model,
                    'restaurant_model' => $restaurant_model
        ]);
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $restaurantUuid) {

        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

        $model = $this->findModel($id, $restaurantUuid);
        $model->setScenario(Order::SCENARIO_CREATE_ORDER_BY_ADMIN);

        // order's Item
        $ordersItemDataProvider = new \yii\data\ActiveDataProvider([
            'query' => $model->getOrderItems()
        ]);


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->order_uuid, 'restaurantUuid' => $restaurantUuid]);
        }

        return $this->render('update', [
                    'model' => $model,
                    'ordersItemDataProvider' => $ordersItemDataProvider,
                    'restaurant_model' => $restaurant_model
        ]);
    }


    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRefundOrder($order_uuid, $restaurantUuid) {

      $order_model = $this->findModel($order_uuid, $restaurantUuid);

      // foreach ($order_model->getOrderItems()->all() as $orderItem) {
      //    $refunded_items_model = new RefundedItem();
      //    $refunded_items_model->order_item_id = $orderItem->order_item_id;
      // }

      $refunded_items_model = [new RefundedItem()];

      foreach ($order_model->getOrderItems()->all() as $key => $orderItem) {
           $refunded_items_model[$key] = new RefundedItem();
           $refunded_items_model[$key]->order_item_id = $orderItem->order_item_id;
           $refunded_items_model[$key]->order_uuid = $orderItem->order_uuid;
       }


      $model = new Refund();
      $model->restaurant_uuid = $order_model->restaurant_uuid;
      $model->order_uuid = $order_model->order_uuid;

      if(Model::loadMultiple($refunded_items_model, Yii::$app->request->post())  && $model->load(Yii::$app->request->post())){


          foreach ($refunded_items_model as  $key => $refunded_item_model) {
            if($refunded_item_model->qty > 0){
              $refunded_item_model->refund_id = $model->refund_id;
              $refunded_item_model->save();
            }


          if($model->save())
            return $this->redirect(['view', 'id' => $order_uuid, 'restaurantUuid' => $restaurantUuid]);
        }
     }


      return $this->render('refund-order', [
                  'model' => $model,
                  'refunded_items_model' => $refunded_items_model
      ]);
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $restaurantUuid) {
        $this->findModel($id, $restaurantUuid)->delete();

        return $this->redirect(['index', 'restaurantUuid' => $restaurantUuid]);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $restaurantUuid) {
        if (($model = Order::find()->where(['order_uuid' => $id, 'restaurant_uuid' => Yii::$app->accountManager->getManagedAccount($restaurantUuid)->restaurant_uuid])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
