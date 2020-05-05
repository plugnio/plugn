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
            'query' => $model->getOrders(),
        ]);


        return $this->render('view', [
          'model' => $model,
          'customersOrdersData' => $customersOrdersData
        ]);
    }


    /**
     * Updates an existing Customer model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $restaurantUuid) {
        $model = $this->findModel($id, $restaurantUuid);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->customer_id, 'restaurantUuid' => $restaurantUuid]);
        }

        return $this->render('update', [
                    'model' => $model,
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
