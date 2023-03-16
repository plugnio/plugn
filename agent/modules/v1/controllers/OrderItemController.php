<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\NotFoundHttpException;
use agent\models\Order;
use agent\models\OrderItem;


class OrderItemController extends BaseController {

    /**
     * Delete Order Item
     */
    public function actionUpdate()
    {
        $store_uuid = Yii::$app->request->getBodyParam('store_uuid');
        $order_uuid = Yii::$app->request->getBodyParam('order_uuid');
        $order_item_id = Yii::$app->request->getBodyParam('order_item_id');

        $model = $this->findModel($order_item_id, $order_uuid ,$store_uuid);
        
        $model->qty = Yii::$app->request->getBodyParam("qty");
        
        $model->customer_instruction = Yii::$app->request->getBodyParam("customer_instructions");
        
          if (!$model->save()) {
              return [
                  "operation" => "error",
                  "message" => $model->errors
              ];
          }

        return [
            "operation" => "success",
            "message" => "Order Item updated successfully"
        ];
    }

    /**
     * Delete Order Item
     */
    public function actionDelete()
    {
        $store_uuid = Yii::$app->request->getBodyParam('store_uuid');
        $order_uuid = Yii::$app->request->getBodyParam('order_uuid');
        $order_item_id = Yii::$app->request->getBodyParam('order_item_id');

        $model = $this->findModel($order_item_id, $order_uuid ,$store_uuid);

        if (!$model->delete())
        {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => "We've faced a problem deleting the order item"
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => "Order Item deleted successfully"
        ];
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($order_item_id, $order_uuid ,$store_uuid = null)
    {
        $store = Yii::$app->accountManager->getManagedAccount($store_uuid);

        $order = Order::find()
            ->filterBusinessLocationIfManager ($store->restaurant_uuid)
            ->andWhere([
                'restaurant_uuid' => $store->restaurant_uuid,
                'order_uuid' => $order_uuid
            ])
            ->one();

        if(!$order)
          throw new NotFoundHttpException('The requested record does not exist.');
        
        $model = OrderItem::find()
            ->where([
                'order_item_id' => $order_item_id, 
                'order_uuid' => $order->order_uuid
            ])
            ->one();
        
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
