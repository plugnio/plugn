<?php

namespace frontend\controllers;

use Yii;
use common\models\OrderItemExtraOption;
use frontend\models\OrderItemExtraOptionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\OrderItem;
use common\models\ExtraOption;
use yii\helpers\ArrayHelper;

/**
 * OrderItemExtraOptionController implements the CRUD actions for OrderItemExtraOption model.
 */
class OrderItemExtraOptionController extends Controller {

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
     * Creates a new OrderItemExtraOption model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id, $restaurantUuid) {
        
        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);
                
        if ($order_item_model = OrderItem::find()->where(['order_item_id' => $id])->one()) {
            $model = new OrderItemExtraOption();
            $model->order_item_id = $id;

            //Get item's extra options to retrieve it on create-form page
            //todo retrieve item's extra optn only
            
            $extraOptions = $order_item_model->item->getExtraOptions()->all();
            
            foreach ($extraOptions as $key => $extraOption) {
                if($extraOption->item->item_uuid != $model->orderItem->item_uuid){
                    unset($extraOptions[$key]);
                }
            }
            
            $extraOptionsQuery = ArrayHelper::map($extraOptions, 'extra_option_id', 'extra_option_name');

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['order-item/view', 'id' => $model->order_item_id, 'restaurantUuid' => $restaurant_model->restaurant_uuid]);
            }

            return $this->render('create', [
                        'model' => $model,
                        'extraOptionsQuery' => $extraOptionsQuery
            ]);
        } else {
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Updates an existing OrderItemExtraOption model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $restaurantUuid) {
        $model = $this->findModel($id, $restaurantUuid);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->order_item_extra_option_id, 'restaurantUuid' => $restaurantUuid]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OrderItemExtraOption model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $restaurantUuid) {

        $model = $this->findModel($id, $restaurantUuid);
        $order_item_id = $model->order_item_id;

        $model->delete();

        return $this->redirect(['order-item/view', 'id' => $order_item_id, 'restaurantUuid' => $restaurantUuid]);
    }

    /**
     * Finds the OrderItemExtraOption model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OrderItemExtraOption the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $restaurantUuid) {
        if (($model = OrderItemExtraOption::findOne($id)) !== null) {
         if ($model->restaurant->restaurant_uuid == Yii::$app->accountManager->getManagedAccount($restaurantUuid)->restaurant_uuid)
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
