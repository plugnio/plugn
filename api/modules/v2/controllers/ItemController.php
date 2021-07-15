<?php

namespace api\modules\v2\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use common\models\Item;
use common\models\Category;
use common\models\Restaurant;
use common\models\ItemImage;

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
     * Return category's products
     */
    public function actionCategoryProducts($category_id) {
      $restaurant_uuid = Yii::$app->request->get("restaurant_uuid");

      if($restaurant_uuid){

        $category = Category::find()
                    ->andWhere([
                        'category.restaurant_uuid' => $restaurant_uuid,
                        'category.category_id' => $category_id
                    ])
                    ->joinWith(['items', 'items.options', 'items.options.extraOptions','items.itemImages'])
                    ->asArray()
                    ->one();

        return [
            'category' => $category,
        ];


      } else {
          return [
              'operation' => 'error',
              'message' => 'Store Uuid is invalid'
          ];
      }
    }

    /**
     */
    // public function actionDeleteItemImage() {
    //   $fullPath = Yii::$app->request->getBodyParam("file");
    //   $file_name = Yii::$app->request->getBodyParam("name");
    //
    //
    //   $restaurant_uuid = explode("restaurants/", $fullPath);
    //   $restaurant_uuid = $restaurant_uuid[1];
    //   $restaurant_uuid = explode("/items/" . $file_name, $restaurant_uuid);
    //   $restaurant_uuid = $restaurant_uuid[0];
    //
    //
    //   $item_image = ItemImage::find()->where(['product_file_name' => $file_name])->one();
    //
    //
    //   if($item_image->item->restaurant_uuid == $restaurant_uuid && $item_image)
    //     $item_image->delete();
    //
    //   return true;
    // }

    /**
     * Return restaurant menu
     */
    public function actionRestaurantMenu() {

        $restaurant_uuid = Yii::$app->request->get("restaurant_uuid");

        $restaurant = Restaurant::find()->where(['restaurant_uuid' => $restaurant_uuid])->one();

        if ($restaurant) {
            $restaurantMenu = Category::find()
                    ->andWhere(['restaurant_uuid' => $restaurant_uuid])
                    ->with('items', 'items.options', 'items.options.extraOptions','items.itemImages')
                    ->orderBy([new \yii\db\Expression('sort_number IS NULL, sort_number ASC')])
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
                'message' => 'Store Uuid is invalid'
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
                ->andWhere([
                    'item_uuid' => $item_uuid,
                    'restaurant_uuid' => $restaurant_uuid
                ])
                ->with('options', 'options.extraOptions','itemImages')
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
