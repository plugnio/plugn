<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use common\models\BankDiscount;

class BankDiscountController extends Controller {

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
    * Get all store's bankDiscounts
     * @param type $id
     * @param type $store_uuid
     * @return type
     */
    public function actionList($store_uuid) {

      if (Yii::$app->accountManager->getManagedAccount($store_uuid)) {

          $bankDiscounts =  BankDiscount::find()
                    ->where(['restaurant_uuid' => $store_uuid])
                    ->asArray()
                    ->all();


          if (!$bankDiscounts) {
              return [
                  'operation' => 'error',
                  'message' => 'No results found'
              ];
          }

          return [
              'operation' => 'success',
              'body' => $bankDiscounts
          ];

      }

    }


    /**
    * Return bank discount detail
     * @param type $store_uuid
     * @param type $order_uuid
     * @return type
     */
    public function actionDetail($store_uuid, $bank_discount_id) {

      if (Yii::$app->accountManager->getManagedAccount($store_uuid)) {

        $bankDiscount =  BankDiscount::find()
                  ->where(['restaurant_uuid' => $store_uuid])
                  ->andWhere(['bank_discount_id' => $bank_discount_id])
                  ->asArray()
                  ->one();


          if (!$bankDiscount) {

              return [
                  'operation' => 'error',
                  'message' => 'No results found.'
              ];
          }

          return [
              'operation' => 'success',
              'body' => $bankDiscount
          ];

      }

  }


}
