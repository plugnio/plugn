<?php

namespace api\modules\v2\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use common\models\City;
use common\models\RestaurantBranch;
use common\models\Restaurant;
use common\models\RestaurantTheme;
use common\models\OpeningHour;
use common\models\RestaurantDelivery;
use common\models\BusinessLocation;

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

              for ($i = 0; $i <= OpeningHour::DAY_OF_WEEK_SATURDAY; $i++) {

                  $deliveryDate = strtotime("+$i day");

                  $opening_hrs = OpeningHour::find()->where(['restaurant_uuid' => $restaurant_uuid, 'day_of_week' => date('w' , $deliveryDate)])->one();

                  if($opening_hrs->is_closed)
                      continue;

                  $selectedDay = 'next '  . date('l', $deliveryDate);




                  $startTime =   date('c',strtotime( date('Y-m-d', strtotime($i == 0 ? "now" : $selectedDay)) . ' ' . $opening_hrs->open_at));

                  if($store_model->schedule_order){

                    $scheduleOrder = $opening_hrs->getDeliveryTimes($deliveryZone->delivery_time, date("Y-m-d", strtotime($startTime)) , $startTime);

                    if(count($scheduleOrder) > 0) {
                      array_push($schedule_time, [
                          'date' => date("c", strtotime($startTime)),
                          'dayOfWeek' => date("w", strtotime($startTime)),
                          'scheduleTimeSlots' => $scheduleOrder
                      ]);
                    }
                  }

              }

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

    /**
     * Return Store's Locations
     */
    public function actionListAllStoresLocations($id) {

        $storesLocations = BusinessLocation::find()
                        ->where(['restaurant_uuid' => $id])->all();

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
     * Return Restaurant's branches
     */
    public function actionListAllRestaurantsBranches($id) {

        $restaurantBranches = RestaurantBranch::find()
                        ->where(['restaurant_uuid' => $id])->all();

        if ($restaurantBranches) {
            return $restaurantBranches;
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
                  ->where(['store_branch_name' => $branch_name]);

          if( $store->exists() ){

            $restaurant = $store
                    ->select(['restaurant_uuid', 'name', 'logo', 'tagline', 'restaurant_domain', 'app_id', 'google_analytics_id', 'facebook_pixil_id', 'custom_css'])
                    ->one();


            $themeColor = RestaurantTheme::find()
                    ->select(['primary'])
                    ->where(['restaurant_uuid' => $restaurant->restaurant_uuid])
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
