<?php

namespace frontend\controllers;

use Yii;
use common\models\Customer;
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
    public function actionIndex($restaurantUuid) {

        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

        $searchModel = new CustomerSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $restaurantUuid);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'restaurantUuid' => $restaurantUuid
        ]);
    }

    /**
     * Displays a single Customer model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $restaurantUuid) {
        $model = $this->findModel($id, $restaurantUuid);

        // Customer's Orders Data
        $customersOrdersData = new \yii\data\ActiveDataProvider([
            'query' => $model->getOrders()->orderBy(['order_created_at' => SORT_ASC]),
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
    public function actionExportToExcel($restaurantUuid){
           $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

           $model = Customer::find()
           ->where(['restaurant_uuid' => $restaurant_model->restaurant_uuid])
           ->orderBy(['customer_created_at' => SORT_DESC])
           ->all();

           header('Access-Control-Allow-Origin: *');
           header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
           header("Content-Disposition: attachment;filename=\"customers.xlsx\"");
           header("Cache-Control: max-age=0");
           \moonland\phpexcel\Excel::export([
               'isMultipleSheet' => false,
               'models' => $model,
               'columns' => [
                   'customer_name',
                   'customer_phone_number',
                   'customer_email',
                   [
                       'attribute' => 'Number of orders',
                       "format" => "raw",
                       "value" => function($model) {
                           return  $model->getOrders()->count();
                       }
                   ],
                   'customer_created_at',
               ]
           ]);
    }

    /**
     * Deletes an existing Customer model.
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
     * Finds the Customer model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Customer the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $restaurantUuid) {
        if (($model = Customer::find()->where(['customer_id'=>$id, 'restaurant_uuid' => $restaurantUuid])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
