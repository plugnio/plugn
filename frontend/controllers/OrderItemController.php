<?php

namespace frontend\controllers;

use Yii;
use common\models\OrderItem;
use frontend\models\OrderItemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Order;
use common\models\Item;
use yii\helpers\ArrayHelper;

/**
 * OrderItemController implements the CRUD actions for OrderItem model.
 */
class OrderItemController extends Controller {

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
     * Creates a new OrderItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id, $restaurantUuid) {

        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

        if (Order::find()->where(['order_uuid' => $id])->exists()) {

            $model = new OrderItem();
            $model->order_uuid = $id;

            //Get restaurant's items to retrieve it on create-form page
            $itemQuery = Item::find()->where(['restaurant_uuid' => $restaurant_model->restaurant_uuid])->asArray()->all();
            $restaurantsItems = ArrayHelper::map($itemQuery, 'item_uuid', 'item_name');


            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['order/update', 'id' => $model->order_uuid, 'restaurantUuid' => $restaurantUuid]);
            }

            return $this->render('create', [
                        'model' => $model,
                        'restaurantsItems' => $restaurantsItems
            ]);
        } else {
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Displays a single OrderItem model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $restaurantUuid) {

        $model = $this->findModel($id, $restaurantUuid);

        // Item extra optn
        $orderItemsExtraOpiton = new \yii\data\ActiveDataProvider([
            'query' => $model->getOrderItemExtraOptions()
        ]);


        return $this->render('view', [
                    'model' => $model,
                    'orderItemsExtraOpiton' => $orderItemsExtraOpiton
        ]);
    }

    /**
     * Updates an existing OrderItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $restaurantUuid) {
        $model = $this->findModel($id, $restaurantUuid);

        $order_model = Order::findOne($model->order_uuid);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->order_item_id, 'restaurantUuid' => $restaurantUuid]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrderItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $restaurantUuid) {
        $order_item_model = $this->findModel($id, $restaurantUuid);
        $order_uuid = $order_item_model->order_uuid;

        $order_item_model->delete();

        return $this->redirect(['order/update', 'id' => $order_uuid, 'restaurantUuid' => $restaurantUuid]);
    }

    /**
     * Finds the OrderItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrderItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $restaurantUuid) {
        if (($model = OrderItem::findOne($id)) !== null) {
            if ($model->restaurant->restaurant_uuid == Yii::$app->accountManager->getManagedAccount($restaurantUuid)->restaurant_uuid)
                return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
