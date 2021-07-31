<?php

namespace agent\modules\v1\controllers;

use agent\models\DeliveryZone;
use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use agent\models\City;


class CityController extends Controller {

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
    * Get all cities data
     * @return type
     */
    public function actionList() {

        $keyword = Yii::$app->request->get('keyword');
        $country_id = Yii::$app->request->get('country_id');
        $store_uuid = Yii::$app->request->get('store_uuid');
        $delivery_zone_id = Yii::$app->request->get('delivery_zone_id');

        Yii::$app->accountManager->getManagedAccount($store_uuid);

        if($delivery_zone_id) {
            $dz = DeliveryZone::findOne(['delivery_zone_id' => $delivery_zone_id]);

            if($dz)
                $country_id = $dz->country_id;
        }

        $query =  City::find();

        if ($keyword) {
          $query->andWhere([
              'OR',
              ['like', 'city_name', $keyword],
              ['like', 'city_name_ar', $keyword]
          ]);
        }

        $query->andWhere(['country_id' => $country_id]);

        return new ActiveDataProvider([
          'query' => $query
        ]);
    }

    /**
    * Return City detail
     * @param type $store_uuid
     * @param type $city_id
     * @return type
     */
    public function actionDetail($city_id, $store_uuid) {

      if (Yii::$app->accountManager->getManagedAccount($store_uuid)) {

        $city =  City::find()
                  ->andWhere(['city_id' =>  $city_id])
                  ->one();

        return $city;

      }

  }
}
