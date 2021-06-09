<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use agent\models\Order;
use common\models\Restaurant;
use common\models\OrderItem;


class OrderItemController extends Controller {

    public function behaviors() {
        $behaviors = parent::behaviors();

        // remove authentication filter for cors to work
        unset($behaviors['authenticator']);

        // Allow XHR Requests from our different subdomains and dev machines
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
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
                  'class' => \yii\filters\auth\HttpBearerAuth::className(),
              ];
              // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
              $behaviors['authenticator']['except'] = ['options'];

              return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions() {
        $actions = parent::actions();
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
            // optional:
            'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }

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
    protected function findModel($order_item_id, $order_uuid ,$store_uuid)
    {
        $store_model = Yii::$app->accountManager->getManagedAccount($store_uuid);
        $order_model = Order::findOne(['restaurant_uuid' => $store_model->restaurant_uuid, 'order_uuid' => $order_uuid]);

        if(!$order_model)
          throw new NotFoundHttpException('The requested record does not exist.');


        if (($model = OrderItem::find()->where(['order_item_id' => $order_item_id, 'order_uuid' => $order_model->order_uuid])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }


}
