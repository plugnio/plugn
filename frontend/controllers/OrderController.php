<?php

namespace frontend\controllers;

use Yii;
use common\models\Order;
use frontend\models\OrderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Customer;
use common\models\Restaurant;

/**
 * OrderController implements the CRUD actions for Order model.
 */
class OrderController extends Controller {

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

        $restaurant_model = Yii::$app->ownedAccountManager->getOwnedAccount($restaurantUuid);

        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $restaurant_model->restaurant_uuid);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'restaurant_model' => $restaurant_model
        ]);
    }

    public function actionChangeOrderStatus($id, $status) {
        $order_model = $this->findModel($id);

        $order_model->order_status = $status;
        $order_model->save(false);

        return $this->redirect(['view', 'id' => $order_model->order_uuid, 'restaurantUuid' => $restaurantUuid]);
    }

    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $restaurantUuid) {

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
                    'itemsExtraOpitons' => $itemsExtraOpitons
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
        $model = $this->findModel($id, $restaurantUuid);

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
        if (($model = Order::find()->where(['order_uuid' => $id,  'restaurant_uuid' => Yii::$app->ownedAccountManager->getOwnedAccount($restaurantUuid)->restaurant_uuid])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
