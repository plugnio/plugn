<?php

namespace api\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use common\models\Item;
use common\models\Category;
use common\models\Restaurant;

class ItemController extends Controller {

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
     * Return restaurant menu
     */
    public function actionRestaurantMenu() {

        $restaurant_uuid = Yii::$app->request->get("restaurant_uuid");

        $restaurant = Restaurant::find()->where(['restaurant_uuid' => $restaurant_uuid])->one();

        if ($restaurant) {
            $restaurantMenu = Category::find()
                    ->where(['restaurant_uuid' => $restaurant_uuid])
                    ->with('items', 'items.options', 'items.options.extraOptions')
                    ->orderBy(['sort_number' => SORT_ASC])
                    ->asArray()
                    ->all();


            foreach ($restaurantMenu as $item) {
                unset($item['categoryItems']);
            }

            foreach ($restaurantMenu as $key => $item) {
                unset($restaurantMenu[$key]['categoryItems']);
            }

            return [
                'restaurant' => $restaurant,
                'restaurantTheme' => $restaurant->getRestaurantTheme()->one(),
                'restaurantMenu' => $restaurantMenu
            ];
        } else {
            return [
                'operation' => 'error',
                'message' => 'Restaurant Uuid is invalid'
            ];
        }
    }

    /**
     * Return item's data
     */
    public function actionItemData() {
        $item_uuid = Yii::$app->request->get("item_uuid");
        $restaurant_uuid = Yii::$app->request->get("restaurant_uuid");

        $item_model = Item::find()
                ->where(['item_uuid' => $item_uuid, 'restaurant_uuid' => $restaurant_uuid])
                ->with('options', 'options.extraOptions')
                ->asArray()
                ->one();

        if ($item_model) {
            return [
                'operation' => 'success',
                'itemData' => $item_model
            ];
        } else {
            return [
                'operation' => 'error',
                'message' => 'Item Uuid is invalid'
            ];
        }
    }

}
