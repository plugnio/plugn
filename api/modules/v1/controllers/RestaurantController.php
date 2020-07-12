<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use common\models\City;
use common\models\RestaurantBranch;
use common\models\Restaurant;
use common\models\RestaurantTheme;
use common\models\OpeningHour;
use common\models\RestaurantDelivery;

class RestaurantController extends Controller {

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
        $area_id = Yii::$app->request->get("area_id");

        if (Restaurant::find()->where(['restaurant_uuid' => $restaurant_uuid])->exists()) {
          $deliveryArea = RestaurantDelivery::find()->where(['restaurant_uuid' => $restaurant_uuid , 'area_id' =>$area_id ])->one();

          $delivery_time = [];


            if($deliveryArea){

              for ($i = 0; $i <= OpeningHour::DAY_OF_WEEK_SATURDAY; $i++) {

                  $deliveryDate = strtotime("+$i day");

                  $opening_hrs = OpeningHour::find()->where(['restaurant_uuid' => $restaurant_uuid, 'day_of_week' => $i])->one();

                  if($opening_hrs->is_closed)
                      continue;

                  $deliveryTimes = $opening_hrs->getDeliveryTimes($deliveryArea->delivery_time);

                  if(count($deliveryTimes) > 0) {
                    array_push($delivery_time, [
                        'shortDate' => date("d M", $deliveryDate),
                        'dayOfWeek' => date("w", $deliveryDate),
                        'day' => $i == 0 ? 'Today' : ($i == 1 ? 'Tomorrow' : date("D", $deliveryDate)),
                        'times' => $deliveryTimes
                    ]);
                  }

              }

              return $delivery_time;

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

        $restaurant = Restaurant::find()
                ->select(['restaurant_uuid', 'name', 'logo', 'tagline', 'restaurant_domain', 'app_id', 'google_analytics_id', 'facebook_pixil_id', 'custom_css'])
                ->where(['store_branch_name' => $branch_name])
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
    }

}
