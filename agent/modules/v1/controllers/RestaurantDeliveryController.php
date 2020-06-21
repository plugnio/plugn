<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use common\models\City;
use common\models\RestaurantDelivery;

class RestaurantDeliveryController extends Controller {

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
     * Return City list
     */
    public function actionDeliveredAreaData($id,$restaurant_uuid) {


        $restaurantDeliveryArea = RestaurantDelivery::find()
                ->where(['area_id' => $id, 'restaurant_uuid' => $restaurant_uuid])
                ->one();


        $restaurantDeliveryArea->delivery_time = Yii::$app->formatter->asDuration($restaurantDeliveryArea->delivery_time * 60);

        Yii::$app->formatter->language = 'ar-KW';
        $restaurantDeliveryArea->delivery_time_ar = Yii::$app->formatter->asDuration($restaurantDeliveryArea->delivery_time_ar * 60);

        return $restaurantDeliveryArea;
    }

    /**
     * Return City list
     */
    public function actionListAllCities($restaurant_uuid) {


        $allCitiesData = City::find()
                ->asArray()
                ->all();

        $restaurantDeliveryAreas = RestaurantDelivery::find()
                ->where(['restaurant_uuid' => $restaurant_uuid])
                ->asArray()
                ->with('area')
                ->all();

        foreach ($restaurantDeliveryAreas as $key => $delivery_area) {
            foreach ($allCitiesData as $key => $city) {
                if ($city['city_id'] == $delivery_area['area']['city_id']) {
                    $allCitiesData[$key]['areas'][] = $delivery_area['area'];
                }
            }
        }

        return $allCitiesData;
    }

}
