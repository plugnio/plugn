<?php

namespace frontend\controllers;

use Yii;
use common\models\Customer;
use common\models\Order;
use frontend\models\CustomerSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CustomerController implements the CRUD actions for Customer model.
 */
class CustomerController extends Controller {

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
     * Lists all Customer models.
     * @return mixed
     */
    public function actionIndex($storeUuid) {

      // add conditions that should always apply here

        $restaurant_model = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $storeUuid);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'restaurant_model' => $restaurant_model,
                    'storeUuid' => $storeUuid
        ]);
    }


      /**
       * Creates a new Customer model.
       * If creation is successful, the browser will be redirected to the 'view' page.
       * @return mixed
       */
      // public function actionCreate($storeUuid)
      // {
          // $restaurant_model = Yii::$app->accountManager->getManagedAccount($storeUuid);
          //
          // $model = new Customer();
          // $model->setScenario(Customer::SCENARIO_CREATE_ORDER_BY_AGENT);
          // $model->restaurant_uuid = $storeUuid;
          //
          // if ($model->load(Yii::$app->request->post()) && $model->save()) {
          //     return $this->redirect(['view', 'id' => $model->customer_id,'storeUuid' => $storeUuid]);
          // }
          //
          // return $this->render('create', [
          //     'model' => $model,
          // ]);
      // }


      /**
       * Updates an existing Customer model.
       * If update is successful, the browser will be redirected to the 'view' page.
       * @param integer $id
       * @return mixed
       * @throws NotFoundHttpException if the model cannot be found
       */
      public function actionUpdate($id, $storeUuid)
      {
        $restaurant_model = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $model = $this->findModel($id, $storeUuid);

          if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->customer_id,'storeUuid' => $storeUuid]);
          }

          return $this->render('update', [
              'model' => $model,
          ]);
      }

    /**
     * Displays a single Customer model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $storeUuid) {
        $restaurant_model = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $model = $this->findModel($id, $storeUuid);

        // Customer's Orders Data
        $customersOrdersData = new \yii\data\ActiveDataProvider([
            'query' => $model->getOrders()->with(['currency'])->orderBy(['order_created_at' => SORT_ASC]),
            'pagination' => false
        ]);


        return $this->render('view', [
          'model' => $model,
          'customersOrdersData' => $customersOrdersData
        ]);
    }

    /**
    * Export customers data to excel
    * @return mixed
    */
    public function actionExportToExcel($storeUuid){
           $restaurant_model = Yii::$app->accountManager->getManagedAccount($storeUuid);

           $model = Customer::find()
           ->andWhere(['restaurant_uuid' => $restaurant_model->restaurant_uuid])
           ->orderBy(['customer_created_at' => SORT_DESC])
           ->all();

           header('Access-Control-Allow-Origin: *');
           header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
           header("Content-Disposition: attachment;filename=\"customers.xlsx\"");
           header("Cache-Control: max-age=0");

           if($restaurant_model->restaurant_uuid == 'rest_fe5b6a72-18a7-11ec-973b-069e9504599a'){
             \moonland\phpexcel\Excel::export([
                 'isMultipleSheet' => false,
                 'models' => $model,
                 'columns' => [
                     'customer_name',
                     'customer_email',
                     [
                         'attribute' => 'customer_phone_number',
                         "format" => "raw",
                         "value" => function($model) {
                             return str_replace(' ','',  strval($model->customer_phone_number));
                         }
                     ],
                     [
                         'attribute' => 'civil_id',
                         "format" => "raw",
                         "value" => function($data) {
                           return  $data->civil_id ;
                         }
                     ],
                     [
                         'attribute' => 'section',
                         "format" => "raw",
                         "value" => function($data) {
                           return  $data->section ;
                         }
                     ],
                     [
                         'attribute' => 'class',
                         "format" => "raw",
                         "value" => function($data) {
                           return  $data->class ;
                         }
                     ],
                     [
                         'attribute' => 'Total spent',
                         "format" => "raw",
                         "value" => function($data) {
                           $total_spent = $data->getOrders()
                                           ->andWhere([
                                               'NOT IN',
                                               'order_status' , [
                                                  Order::STATUS_DRAFT,
                                                  Order::STATUS_ABANDONED_CHECKOUT,
                                                  Order::STATUS_REFUNDED,
                                                  Order::STATUS_PARTIALLY_REFUNDED,
                                                  Order::STATUS_CANCELED
                                              ]
                                           ])
                                           ->sum('total_price');


                           $total_spent = \Yii::$app->formatter->asDecimal($total_spent ? $total_spent : 0 , 3);

                           return  Yii::$app->formatter->asCurrency($total_spent ? $total_spent : 0, $data->currency->code);
                         }
                     ],
                     [
                         'attribute' => 'Number of orders',
                         "format" => "raw",
                         "value" => function($model) {
                             return  $model->getOrders()
                                 ->andWhere([
                                     'NOT IN',
                                     'order_status' , [
                                         Order::STATUS_DRAFT,
                                         Order::STATUS_ABANDONED_CHECKOUT,
                                         Order::STATUS_REFUNDED,
                                         Order::STATUS_PARTIALLY_REFUNDED,
                                         Order::STATUS_CANCELED
                                     ]
                                 ])
                             ->count();
                         }
                     ],
                     [
                         'header' => 'Account created at',
                         "format" => "raw",
                         "value" => function($data) {
                           return  $data->customer_created_at ;
                         }
                     ]
                 ]
             ]);
           } else {
             \moonland\phpexcel\Excel::export([
                 'isMultipleSheet' => false,
                 'models' => $model,
                 'columns' => [
                     'customer_name',
                     'customer_email',
                     [
                         'attribute' => 'customer_phone_number',
                         "format" => "raw",
                         "value" => function($model) {
                             return str_replace(' ','',  strval($model->customer_phone_number));
                         }
                     ],
                     [
                         'attribute' => 'Total spent',
                         "format" => "raw",
                         "value" => function($data) {
                           $total_spent = $data->getOrders()
                                           ->andWhere([
                                               'NOT IN',
                                               'order_status' , [
                                                  Order::STATUS_DRAFT,
                                                  Order::STATUS_ABANDONED_CHECKOUT,
                                                  Order::STATUS_REFUNDED,
                                                  Order::STATUS_PARTIALLY_REFUNDED,
                                                  Order::STATUS_CANCELED
                                              ]
                                           ])
                                           ->sum('total_price');


                           $total_spent = \Yii::$app->formatter->asDecimal($total_spent ? $total_spent : 0 , 3);

                           return  Yii::$app->formatter->asCurrency($total_spent ? $total_spent : 0, $data->currency->code);
                         }
                     ],
                     [
                         'attribute' => 'Number of orders',
                         "format" => "raw",
                         "value" => function($model) {
                             return  $model->getOrders()
                                 ->andWhere([
                                     'NOT IN',
                                     'order_status' , [
                                         Order::STATUS_DRAFT,
                                         Order::STATUS_ABANDONED_CHECKOUT,
                                         Order::STATUS_REFUNDED,
                                         Order::STATUS_PARTIALLY_REFUNDED,
                                         Order::STATUS_CANCELED
                                     ]
                                 ])
                             ->count();
                         }
                     ],
                     [
                         'header' => 'Account created at',
                         "format" => "raw",
                         "value" => function($data) {
                           return  $data->customer_created_at ;
                         }
                     ]
                 ]
             ]);
           }
    }


    /**
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $storeUuid) {
        if (($model = Customer::find()->where(['customer_id'=>$id, 'restaurant_uuid' => Yii::$app->accountManager->getManagedAccount($storeUuid)->restaurant_uuid ])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
