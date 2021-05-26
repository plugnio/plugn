<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use common\models\Order;

class OrderController extends Controller {

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
     * Return a List pending Orders
     * @param type $id
     * @param type $store_uuid
     * @return type
     */
    public function actionListPendingOrders($store_uuid) {

      if (Yii::$app->accountManager->getManagedAccount($store_uuid)) {

          $pendingOrders =  Order::find()
                    ->where(['restaurant_uuid' => $store_uuid])
                    ->andWhere(['order_status' => Order::STATUS_PENDING])
                    ->orderBy(['order_created_at' => SORT_DESC]);


            return new ActiveDataProvider([
                'query' => $pendingOrders
            ]);

      }

    }




      /**
      * Return a List of Pending Order by keyword
      */
      public function actionFilterPending($store_uuid)
      {
        if (Yii::$app->accountManager->getManagedAccount($store_uuid)) {

          $keyword = Yii::$app->request->get('keyword');

          $query =  Order::find();

          if($keyword) {
                $query->where(['like', 'customer_name', $keyword]);
                $query->orWhere(['like', 'customer_phone_number', $keyword]);
                $query->orWhere(['like', 'order_uuid', $keyword]);
          }

          $query
          ->andWhere(['restaurant_uuid' => $store_uuid])
          ->andWhere(['order.order_status' => Order::STATUS_PENDING]);

          return new ActiveDataProvider([
              'query' => $query
          ]);

        }
      }



    /**
     * Return a List active Orders
     * @param type $id
     * @param type $store_uuid
     * @return type
     */
    public function actionListActiveOrders($store_uuid) {

      if (Yii::$app->accountManager->getManagedAccount($store_uuid)) {

          $activeOrders =  Order::find()
                    ->activeOrders($store_uuid)
                    ->orderBy(['order_created_at' => SORT_DESC]);


          return new ActiveDataProvider([
              'query' => $activeOrders
          ]);

      }

    }

    /**
    * Return a List of Active Order by keyword
    */
    public function actionFilterActive($store_uuid)
    {
      if (Yii::$app->accountManager->getManagedAccount($store_uuid)) {

        $keyword = Yii::$app->request->get('keyword');

        $query =  Order::find();

        if($keyword) {
              $query->where(['like', 'customer_name', $keyword]);
              $query->orWhere(['like', 'customer_phone_number', $keyword]);
              $query->orWhere(['like', 'order_uuid', $keyword]);
        }

        $query->andWhere(['restaurant_uuid' => $store_uuid])
        ->andWhere([ '!=' , 'order_status' , Order::STATUS_DRAFT])
        ->andWhere([ '!=' , 'order_status' , Order::STATUS_ABANDONED_CHECKOUT])
        ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
        ->andWhere(['!=', 'order_status', Order::STATUS_PARTIALLY_REFUNDED])
        ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED]);;

        return new ActiveDataProvider([
            'query' => $query
        ]);

      }
    }




    /**
     * Return a List draft Orders
     * @param type $id
     * @param type $store_uuid
     * @return type
     */
    public function actionListDraftOrders($store_uuid) {

      if (Yii::$app->accountManager->getManagedAccount($store_uuid)) {

          $draftOrders =  Order::find()
                    ->where(['order.restaurant_uuid' => $store_uuid])
                    ->andWhere(['order.order_status' => Order::STATUS_DRAFT])
                    ->orderBy(['order.order_created_at' => SORT_DESC]);


          return new ActiveDataProvider([
              'query' => $draftOrders
          ]);

      }

    }


      /**
      * Return a List of Draft Order by keyword
      */
      public function actionFilterDraft($store_uuid)
      {
        if (Yii::$app->accountManager->getManagedAccount($store_uuid)) {

          $keyword = Yii::$app->request->get('keyword');

          $query =  Order::find();

          if($keyword) {
                $query->where(['like', 'customer_name', $keyword]);
                $query->orWhere(['like', 'customer_phone_number', $keyword]);
                $query->orWhere(['like', 'order_uuid', $keyword]);
          }

          $query
                ->andWhere(['order.restaurant_uuid' => $store_uuid])
                ->andWhere(['order.order_status' => Order::STATUS_DRAFT]);

          return new ActiveDataProvider([
              'query' => $query
          ]);

        }
      }




    /**
     * Return a List abandoned Orders
     * @param type $id
     * @param type $store_uuid
     * @return type
     */
    public function actionListAbandonedOrders($store_uuid) {

      if (Yii::$app->accountManager->getManagedAccount($store_uuid)) {

          $abandonedOrders =  Order::find()
                    ->where(['order.restaurant_uuid' => $store_uuid])
                    ->andWhere(['order.order_status' => Order::STATUS_ABANDONED_CHECKOUT])
                    ->orderBy(['order.order_created_at' => SORT_DESC]);


            return new ActiveDataProvider([
                'query' => $abandonedOrders
            ]);

      }

  }


      /**
      * Return a List of Abandoned Order by keyword
      */
      public function actionFilterAbandoned($store_uuid)
      {
        if (Yii::$app->accountManager->getManagedAccount($store_uuid)) {

          $keyword = Yii::$app->request->get('keyword');

          $query =  Order::find();

          if($keyword) {
                $query->where(['like', 'customer_name', $keyword]);
                $query->orWhere(['like', 'customer_phone_number', $keyword]);
                $query->orWhere(['like', 'order_uuid', $keyword]);
          }

          $query
                ->andWhere(['order.restaurant_uuid' => $store_uuid])
                ->andWhere(['order.order_status' => Order::STATUS_ABANDONED_CHECKOUT]);

          return new ActiveDataProvider([
              'query' => $query
          ]);

        }
      }





    /**
    * Return order detail
     * @param type $store_uuid
     * @param type $order_uuid
     * @return type
     */
    public function actionDetail($store_uuid, $order_uuid) {

      if (Yii::$app->accountManager->getManagedAccount($store_uuid)) {

          $order =  Order::find()
                    ->where(['order.restaurant_uuid' => $store_uuid])
                    ->andWhere(['order.order_uuid' => $order_uuid])
                    ->asArray()
                    ->one();


          if (!$order) {

              return [
                  'operation' => 'error',
                  'message' => 'No results found.'
              ];
          }

          return [
              'operation' => 'success',
              'body' => $order
          ];

      }

  }

}
