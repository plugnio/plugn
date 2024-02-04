<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use api\models\Order;
use common\models\Agent;
use common\models\OrderItem;
use common\models\OrderItemExtraOption;
use api\models\Restaurant;
use api\models\Payment;
use common\components\TapPayments;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class ZapierController extends BaseController {

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
   * Get store list
   * @return boolean
   */
    public function actionGetStoreList() {

      $storeList = [];

        foreach (Yii::$app->accountManager->getManagedAccounts() as $key => $managedAccount) {

            if(!$managedAccount->restaurant)
                continue;

            $storeList[$key]['id'] = $managedAccount->restaurant_uuid;
            $storeList[$key]['store_name'] = $managedAccount->restaurant->name;
      }

      return $storeList;


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
                    ->andWhere([
                        'IN',
                        'order_status', [
                            Order::STATUS_PENDING,
                            Order::STATUS_BEING_PREPARED,
                            Order::STATUS_OUT_FOR_DELIVERY,
                            Order::STATUS_ACCEPTED,
                            Order::STATUS_COMPLETE,
                            Order::STATUS_CANCELED
                        ]
                    ])
                    ->andWhere(['order.restaurant_uuid' => $restaurant_uuid])
                    ->asArray()
                    ->all();

            foreach ($orders as $key => $order) {
                $orders[$key]['id'] = $order['order_uuid'];
                $orders[$key]['estimated_time_of_arrival'] = date('c', strtotime($order['estimated_time_of_arrival']));
                $orders[$key]['order_created_at'] = date('c', strtotime($order['order_created_at']));
                $orders[$key]['order_updated_at'] = date('c', strtotime($order['order_updated_at']));


                foreach ($order['orderItems'] as $orderItemKey => $orderItem) {

                  unset($orders[$key]['orderItems'][$orderItemKey]['item']);
                  unset($orders[$key]['orderItems'][$orderItemKey]['order_item_id']);
                  unset($orders[$key]['orderItems'][$orderItemKey]['item_uuid']);
                }

                unset($orders[$key]['restaurant_uuid']);
                unset($orders[$key]['restaurant_branch_id']);
                unset($orders[$key]['armada_tracking_link']);
                unset($orders[$key]['items_has_been_restocked']);
                unset($orders[$key]['scheduled_time_start_from']);
                unset($orders[$key]['scheduled_time_start_to']);
                unset($orders[$key]['armada_qr_code_link']);
                unset($orders[$key]['voucher_id']);
                unset($orders[$key]['armada_delivery_code']);


                unset($orders[$key]['mashkor_order_number']);
                unset($orders[$key]['mashkor_tracking_link']);
                unset($orders[$key]['mashkor_driver_name']);
                unset($orders[$key]['mashkor_driver_name']);
                unset($orders[$key]['mashkor_driver_phone']);
                unset($orders[$key]['mashkor_order_status']);
                unset($orders[$key]['bank_discount_id']);
                unset($orders[$key]['bank_discount_id']);
                unset($orders[$key]['reminder_sent']);
                unset($orders[$key]['sms_sent']);



                unset($orders[$key]['payment_uuid']);
                unset($orders[$key]['order_uuid']);
                unset($orders[$key]['customer_id']);
                unset($orders[$key]['area_id']);
                unset($orders[$key]['payment_method_id']);
                unset($orders[$key]['subtotal_before_refund']);
                unset($orders[$key]['total_price_before_refund']);
            }

            return $orders;
        }
    }

}
