<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use common\models\BusinessLocation;

class BusinessLocationController extends Controller {

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
    * Get all store's branches
     * @param type $id
     * @param type $store_uuid
     * @return type
     */
    public function actionList($store_uuid) {

      if (Yii::$app->accountManager->getManagedAccount($store_uuid)) {

          $businessLocations =  BusinessLocation::find()
                    ->where(['restaurant_uuid' => $store_uuid])
                    ->asArray()
                    ->all();


          if (!$businessLocations) {
              return [
                  'operation' => 'error',
                  'message' => 'No results found'
              ];
          }

          return [
              'operation' => 'success',
              'body' => $businessLocations
          ];

      }

    }


    /**
    * Return Business Location detail
     * @param type $store_uuid
     * @param type $order_uuid
     * @return type
     */
    public function actionDetail($store_uuid, $business_location_id) {

      if (Yii::$app->accountManager->getManagedAccount($store_uuid)) {

        $businessLocation =  BusinessLocation::find()
                  ->where(['restaurant_uuid' => $store_uuid])
                  ->andWhere(['business_location_id' => $business_location_id])
                  ->with('deliveryZones')
                  ->asArray()
                  ->one();


          if (!$businessLocation) {

              return [
                  'operation' => 'error',
                  'message' => 'No results found.'
              ];
          }

          return [
              'operation' => 'success',
              'body' => $businessLocation
          ];

      }

  }

}
