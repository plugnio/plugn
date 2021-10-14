<?php

namespace api\modules\v2\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use common\models\City;
use common\models\RestaurantBranch;
use api\models\Restaurant;
use common\models\RestaurantTheme;
use common\models\OpeningHour;
use common\models\RestaurantDelivery;
use api\models\BusinessLocation;
use common\models\DeliveryZone;

class StoreController extends Controller {

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
     * Return Restaurant's branches
     */
    public function actionGetOpeningHours() {


        $restaurant_uuid = Yii::$app->request->get("restaurant_uuid");
        $delivery_zone_id = Yii::$app->request->get("delivery_zone_id");

        if ($store_model = Restaurant::find()->where(['restaurant_uuid' => $restaurant_uuid])->one()) {
          $deliveryZone =  $store_model->getDeliveryZones()->where(['delivery_zone_id' => $delivery_zone_id ])->one();

          $schedule_time = [];


            if($deliveryZone){

              $timeUnit = $deliveryZone->time_unit == 'hrs' ? 'hour' : $deliveryZone->time_unit;
              $startDate = strtotime('+ ' . $deliveryZone->delivery_time . ' ' . $timeUnit );


              if($deliveryZone->time_unit == DeliveryZone::TIME_UNIT_MIN)
                $deliveryTime = intval($deliveryZone->delivery_time) ;
              else if($deliveryZone->time_unit == DeliveryZone::TIME_UNIT_HRS)
                $deliveryTime =  intval($deliveryZone->delivery_time) * 60;
              else if($deliveryZone->time_unit == DeliveryZone::TIME_UNIT_DAY)
                $deliveryTime =  intval($deliveryZone->delivery_time) * 24 * 60;

                    if($store_model->schedule_order){

                      $schedule_time = OpeningHour::getAvailableTimeSlots($deliveryTime, $store_model, $timeUnit);


                    }

                // }


              $todayOpeningHours = OpeningHour::find()->where(['restaurant_uuid' => $restaurant_uuid, 'day_of_week' => date('w' , strtotime("now"))])->one();
              $asap = date("c", strtotime('+' . $deliveryZone->delivery_time . ' ' . $deliveryZone->timeUnit,  Yii::$app->formatter->asTimestamp(date('Y-m-d H:i:s'))));


             return [
                    'ASAP' => $store_model->isOpen() ? $asap : null,
                    'scheduleOrder' => $store_model->schedule_order ?  ($schedule_time  ? $schedule_time  : null): null
                ];



            } else {
              return [
                  'operation' => 'error',
                  'message' => "Unfortunately we don't currently deliver to the selected area."
              ];
            }
        } else
            return [
                'operation' => 'error',
                'message' => 'Restaurant Uuid is invalid'
            ];
    }

    public function actionGetDeliveryTime() {


        $restaurant_uuid = Yii::$app->request->get("restaurant_uuid");
        $delivery_zone_id = Yii::$app->request->get("delivery_zone_id");
        $cart = Yii::$app->request->getBodyParam("cart");

        if ($store_model = Restaurant::find()->where(['restaurant_uuid' => $restaurant_uuid])->one()) {
          $deliveryZone =  $store_model->getDeliveryZones()->where(['delivery_zone_id' => $delivery_zone_id ])->one();

          $schedule_time = [];


            if($deliveryZone){

              $timeUnit = $deliveryZone->time_unit == 'hrs' ? 'hour' : $deliveryZone->time_unit;
              $startDate = strtotime('+ ' . $deliveryZone->delivery_time . ' ' . $timeUnit );


              if($deliveryZone->time_unit == DeliveryZone::TIME_UNIT_MIN)
                $deliveryTime = intval($deliveryZone->delivery_time) ;
              else if($deliveryZone->time_unit == DeliveryZone::TIME_UNIT_HRS)
                $deliveryTime =  intval($deliveryZone->delivery_time) * 60;
              else if($deliveryZone->time_unit == DeliveryZone::TIME_UNIT_DAY)
                $deliveryTime =  intval($deliveryZone->delivery_time) * 24 * 60;

                $prepTime = 0;

                if($cart && sizeof($cart) > 0){
                  foreach ($cart as $key => $item) {
                    if(isset($item['prep_time_in_min']) && $item['prep_time_in_min'] > $prepTime)
                      $prepTime = $item['prep_time_in_min'];
                  }
                }



                    if($store_model->schedule_order){

                      $schedule_time = OpeningHour::getDeliveryTime($deliveryTime, $prepTime,$store_model);

                    }

              $todayOpeningHours = OpeningHour::find()->where(['restaurant_uuid' => $restaurant_uuid, 'day_of_week' => date('w' , strtotime("now"))])->one();
              $asap = date("c", strtotime('+' . ($deliveryTime + $prepTime) . ' min ',  Yii::$app->formatter->asTimestamp(date('Y-m-d H:i:s'))));

             return [
                    'ASAP' => $store_model->isOpen($asap)  && $asap ? $asap : null,
                    'scheduleOrder' => $store_model->schedule_order ?  ($schedule_time  ? $schedule_time  : null): null
                ];



            } else {
              return [
                  'operation' => 'error',
                  'message' => "Unfortunately we don't currently deliver to the selected area."
              ];
            }
        } else
            return [
                'operation' => 'error',
                'message' => 'Restaurant Uuid is invalid'
            ];
    }


    /**
     * Return Store's Locations
     */
    public function actionListAllStoresLocations($id) {

        $storesLocations = BusinessLocation::find()
                        ->andWhere(['restaurant_uuid' => $id])->all();

        if ($storesLocations) {
            return $storesLocations;
        } else {
            return [
                'operation' => 'error',
                'message' => 'Store Uuid is invalid'
            ];
        }
    }

    /**
     * Return Restaurant's data
     */
    public function actionGetRestaurantData($branch_name) {

      $store = Restaurant::find()
              ->andWhere(['store_branch_name' => $branch_name]);

      if( $store->exists() ){

        $restaurant = $store
                ->select(['restaurant_uuid', 'name', 'logo', 'tagline', 'restaurant_domain', 'app_id', 'google_analytics_id', 'facebook_pixil_id','snapchat_pixil_id' , 'custom_css'])
                ->one();


        $themeColor = RestaurantTheme::find()
                ->select(['primary'])
                ->andWhere(['restaurant_uuid' => $restaurant->restaurant_uuid])
                ->one();

        if ($restaurant && $themeColor) {
            return [
                'restaurant_uuid' => $restaurant->restaurant_uuid,
                'name' => $restaurant->name,
                'logo' => $restaurant->logo,
                'tagline' => $restaurant->tagline,
                'restaurant_domain' => $restaurant->restaurant_domain,
                'app_id' => $restaurant->app_id,
                'google_analytics_id' => $restaurant->google_analytics_id,
                'facebook_pixil_id' => $restaurant->facebook_pixil_id,
                'snapchat_pixil_id' => $restaurant->snapchat_pixil_id,
                'custom_css' => $restaurant->custom_css,
                'theme_color' => $themeColor->primary,
            ];
        } else {
            return [
                'operation' => 'error',
                'message' => 'Branch name is invalid'
            ];
        }


      } else {
            return [
                'operation' => 'error',
                'message' => 'Branch name is invalid'
            ];
        }
    }

}
