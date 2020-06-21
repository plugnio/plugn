<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use common\models\City;
use common\models\RestaurantBranch;
use common\models\Restaurant;
use common\models\RestaurantTheme;

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
                ->select(['restaurant_uuid', 'name', 'logo', 'tagline', 'restaurant_domain','custom_css'])
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
                'custom_css' => $restaurant->custom_css,
                'theme_color'=> $themeColor->primary,
            ];
        } else {
            return [
                'operation' => 'error',
                'message' => 'Branch name is invalid'
            ];
        }
    }

}
