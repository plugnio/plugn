<?php

namespace api\modules\v2\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use api\models\Item;
use api\models\Category;
use common\models\City;
use api\models\Restaurant;
use common\models\ItemImage;
use common\models\AreaDeliveryZone;
use common\models\DeliveryZone;

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
     * Return List of countries available for delivery
     */
    public function actionListOfCountries($restaurant_uuid) {

        if ($store_model = Restaurant::findOne($restaurant_uuid)) {

            $deliveryZones = $store_model->getDeliveryZones()->with('country')->asArray()->all();
            $shipping_countries = [];

            foreach ($deliveryZones as $key => $deliveryZone) {
              if(!array_search($deliveryZone['country']['country_id'], array_column($shipping_countries, 'country_id')))

              $isExist = false;

              foreach ($shipping_countries as  $shipping_country) {
                if($deliveryZone['country']['country_id'] == $shipping_country['country_id'])
                $isExist = true;
              }


              if(!$isExist)
                $shipping_countries[] = $deliveryZone['country'];
            }


            foreach ($shipping_countries as $key => $shippingCountry) {

                if($areaDeliveryZone = $store_model->getAreaDeliveryZonesForSpecificCountry($shippingCountry['country_id'])->one()){
                  $shipping_countries[$key]['areas'] =
                  !isset($areaDeliveryZone['area_id']) &&  $areaDeliveryZone->area_id  == null ? 0 : $store_model->getAreaDeliveryZonesForSpecificCountry($shippingCountry['country_id'])->count();


                if($shipping_countries[$key]['areas'] == 0){
                  $countryDeliveryZone = $store_model->getCountryDeliveryZones($shippingCountry['country_id'])->one();
                  $shipping_countries[$key]['delivery_zone_id'] = strval($countryDeliveryZone['delivery_zone_id']);
                }

              }  else {
                unset($shipping_countries[$key]);
              }

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
     * Return List of business locations that support pickup
     */
    public function actionListPickupLocations($restaurant_uuid) {

        if ($store_model = Restaurant::findOne($restaurant_uuid)) {

            $pickupLocations = $store_model->getPickupBusinessLocations()->asArray()->all();

            foreach ($pickupLocations as $key => $pickupLocation) {
              
              unset($pickupLocations[$key]['mashkor_branch_id']);
              unset($pickupLocations[$key]['armada_api_key']);
            }

            return $pickupLocations;


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


            if( $deliveryZone = $store_model->getDeliveryZones()->where(['delivery_zone_id' => $delivery_zone_id])->asArray()->joinWith(['businessLocation'])->one() ){

                unset($deliveryZone['businessLocation']['armada_api_key']);
                unset($deliveryZone['businessLocation']['mashkor_branch_id']);


              if($area_id && !AreaDeliveryZone::find()->where(['area_id' => $area_id , 'delivery_zone_id' => $delivery_zone_id])->exists()){
                return [
                    'operation' => 'error',
                    'message' => 'delivery zone id is invalid'
                ];
              }

              $deliveryTime = intval($deliveryZone['delivery_time']);
              $deliveryTimeInMin = intval($deliveryZone['delivery_time']);

              if(DeliveryZone::TIME_UNIT_DAY == $deliveryZone['time_unit'])
                $deliveryTime = $deliveryTime * 24 * 60 * 60;
              else if (DeliveryZone::TIME_UNIT_HRS == $deliveryZone['time_unit'])
                $deliveryTime = $deliveryTime *  60 * 60;
              else if (DeliveryZone::TIME_UNIT_MIN == $deliveryZone['time_unit'])
                $deliveryTime = $deliveryTime *  60;

              if(DeliveryZone::TIME_UNIT_DAY == $deliveryZone['time_unit'])
                $deliveryTimeInMin = $deliveryTimeInMin * 24 * 60;
              else if (DeliveryZone::TIME_UNIT_HRS == $deliveryZone['time_unit'])
                $deliveryTimeInMin = $deliveryTimeInMin *  60;



              $deliveryZone['delivery_time_in_min'] = $deliveryTimeInMin;

              $deliveryZone['delivery_time'] = Yii::$app->formatter->asDuration($deliveryTime);

              Yii::$app->formatter->language = 'ar-KW';
              $deliveryZone['delivery_time_ar'] = Yii::$app->formatter->asDuration(intval($deliveryTime));
              $deliveryZone['tax'] = $deliveryZone['delivery_zone_tax'] ? $deliveryZone['delivery_zone_tax']  : $deliveryZone['businessLocation']['business_location_tax'] ;

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
     * Return pickup location
     */
    public function actionGetPickupLocation($restaurant_uuid, $pickup_location_id) {

        if ($store_model = Restaurant::findOne($restaurant_uuid)) {

            if( $pickupLocation = $store_model->getBusinessLocations()->where(['business_location_id' => $pickup_location_id])->asArray()->one() ){

              unset($pickupLocation['armada_api_key']);
              unset($pickupLocation['mashkor_branch_id']);

              return $pickupLocation;
            }
            else {
              return [
                  'operation' => 'error',
                  'message' => 'pick up location id is invalid'
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
                  ->andWhere(['country_id' => $country_id])
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
              }

          $citiesData = [];
          foreach ($countryCities as $key => $city) {
            if(isset($city['areas']))
              $citiesData []= $city;
          }


          if(!empty($citiesData))
            return $citiesData ;


        } else {
            return [
                'operation' => 'error',
                'message' => 'Store Uuid is invalid'
            ];
        }
    }




}
