<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use common\models\Item;
use common\models\Category;
use common\models\City;
use common\models\Restaurant;
use common\models\ItemImage;
use common\models\AreaDeliveryZone;

class DeliveryZoneController extends Controller {

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
     * Return Delivery zones
     */
    public function actionDeliveryZone($restaurant_uuid) {

        if ($store_model = Restaurant::findOne($restaurant_uuid)) {

            $shipping_countries = $store_model->getShippingCountries()->asArray()->all();

            foreach ($shipping_countries as $key => $country) {
              $deliveryZones = $store_model->getCountryDeliveryZones($country['country_id'])->asArray()->all();
              $shipping_countries[$key]['businessLocations'] = $deliveryZones;
            }

            return $shipping_countries;


        } else {
            return [
                'operation' => 'error',
                'message' => 'Store Uuid is invalid'
            ];
        }
    }

    /**
     * Return List of countries available for delivery
     */
    public function actionListOfCountries($restaurant_uuid) {

        if ($store_model = Restaurant::findOne($restaurant_uuid)) {

            $shipping_countries = $store_model->getShippingCountries()->asArray()->all();


            foreach ($shipping_countries as $key => $country) {
              $shipping_countries[$key]['areas'] =
                $store_model->getAreaDeliveryZonesForSpecificCountry($country['country_id']) ? $store_model->getAreaDeliveryZonesForSpecificCountry($country['country_id'])->count() : null;

                if($shipping_countries[$key]['areas'] == 0 )
                  $shipping_countries[$key]['deliveryZone'] = $shipping_countries[$key]['deliveryZones'][0];


                unset($shipping_countries[$key]['deliveryZones']);

            }



            return $shipping_countries;


        } else {
            return [
                'operation' => 'error',
                'message' => 'Store Uuid is invalid'
            ];
        }
    }

    /**
     * Return Delivery zone
     */
    public function actionGetDeliveryZone($restaurant_uuid, $delivery_zone_id) {


        $area_id = Yii::$app->request->get("area_id");



        if ($store_model = Restaurant::findOne($restaurant_uuid)) {


            if( $deliveryZone = $store_model->getDeliveryZones()->where(['delivery_zone_id' => $delivery_zone_id])->asArray()->one() ){


              if($area_id && !AreaDeliveryZone::find()->where(['area_id' => $area_id , 'delivery_zone_id' => $delivery_zone_id])->exists()){
                return [
                    'operation' => 'error',
                    'message' => 'delivery zone id is invalid'
                ];
              }

              $deliveryZone['delivery_time'] = Yii::$app->formatter->asDuration($deliveryZone['delivery_time'] * 60);

              Yii::$app->formatter->language = 'ar-KW';
              $deliveryZone['delivery_time_ar'] = Yii::$app->formatter->asDuration(intval($deliveryZone['delivery_time']) * 60);

              return $deliveryZone;


            }
            else {
              return [
                  'operation' => 'error',
                  'message' => 'delivery zone id is invalid'
              ];
            }

        } else {
            return [
                'operation' => 'error',
                'message' => 'Store Uuid is invalid'
            ];
        }
    }

    /**
     * Return list of areas available for delivery
     */
    public function actionListOfAreas($restaurant_uuid, $country_id) {

        if ($store_model = Restaurant::findOne($restaurant_uuid)) {


          $countryCities = City::find()
                  ->where(['country_id' => $country_id])
                  ->asArray()
                  ->all();


                  if($countryCities){
                    $areaDeliveryZones = $store_model->getAreaDeliveryZonesForSpecificCountry($country_id)->asArray()->all();

                    foreach ($countryCities as $cityKey => $city) {
                      foreach ($areaDeliveryZones as $areaDeliveryZoneKey => $areaDeliveryZone) {

                            if(isset($areaDeliveryZone['area'])){
                              if($areaDeliveryZone['area']['city_id'] == $city['city_id']){
                                    $countryCities[$cityKey]['areas'][] = $areaDeliveryZone;
                                  }
                            }
                            else {
                              $countryCities[$cityKey]['areas'][] = $areaDeliveryZone;
                            }
                      }
                  }
              } else
                    return $store_model->getDeliveryZonesForSpecificCountry($country_id)->asArray()->all();


          $citiesData = [];
          foreach ($countryCities as $key => $city) {
            if(isset($city['areas']))
              $citiesData []= $city;
          }


          if(!empty($citiesData))
            return $citiesData ;
          else
          return $store_model->getDeliveryZonesForSpecificCountry($country_id)->asArray()->all();



        } else {
            return [
                'operation' => 'error',
                'message' => 'Store Uuid is invalid'
            ];
        }
    }




}
