<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use common\models\Order;
use common\models\Agent;
use common\models\OrderItem;
use common\models\OrderItemExtraOption;
use common\models\Restaurant;
use common\models\Payment;
use common\components\TapPayments;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class ZapierController extends Controller {

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


      // Basic Auth accepts Base64 encoded username/password and decodes it for you
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBasicAuth::className(),
            'except' => ['options'],
            'auth' => function ($email, $password) {
                $agent = Agent::findByEmail($email);
                if ($agent && $agent->validatePassword($password)) {
                    return $agent;
                }
                return null;
            }
        ];


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
   * CheckPendingOrders of type boolean and we want to return
   * True if there are pending  orders , false if these isn't any
   * @param type $restaurantUuid
   * @return boolean
   */
    public function actionGetLatestOrder($restaurant_uuid) {
        if (Yii::$app->accountManager->getManagedAccount($restaurant_uuid)) {

            $orders = Order::find()
                    ->joinWith('orderItems')
                    ->where(['restaurant_uuid' => $restaurant_uuid])
                    ->asArray()
                    ->all();

            foreach ($orders as $key => $order) {
                $orders[$key]['id'] = $order['order_uuid'];
                unset($orders[$key]['order_uuid']);
            }

            return $orders;
        }
    }

}
