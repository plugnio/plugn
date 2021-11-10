<?php

namespace api\modules\v2\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use api\models\Item;
use api\models\Category;
use api\models\Restaurant;
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

          if(isset($category['items'])){
            foreach ($category['items'] as $key => $item) {
                unset($category['items'][$key]['unit_sold']);
            }
          }


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
     * Return restaurant menu
     */
    public function actionRestaurantMenu() {

        $restaurant_uuid = Yii::$app->request->get("restaurant_uuid");

        $restaurant = Restaurant::find()->where(['restaurant_uuid' => $restaurant_uuid])->one();



        if ($restaurant) {

          if($restaurant->is_myfatoorah_enable)
            unset($restaurant['live_public_key']);

            $restaurantMenu = Category::find()
                    ->andWhere(['restaurant_uuid' => $restaurant_uuid])
                    ->with('items', 'items.options', 'items.options.extraOptions','items.itemImages')
                    ->orderBy([new \yii\db\Expression('sort_number IS NULL, sort_number ASC')])
                    ->asArray()
                    ->all();


            foreach ($restaurantMenu as $category) {
                unset($category['categoryItems']);
            }

            foreach ($restaurantMenu as $key => $category) {
                unset($restaurantMenu[$key]['categoryItems']);

                foreach ($category['items'] as $itemKey => $item) {
                  unset($restaurantMenu[$key]['items'][$itemKey]['unit_sold']);
                }
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
     * Return Store's products
     */
    public function actionGetStoreCatalog() {
      $restaurant_uuid = Yii::$app->request->get("restaurant_uuid");

      $restaurant = Restaurant::find()->where(['restaurant_uuid' => $restaurant_uuid])->one();

              if ($restaurant) {

                  $query = Category::find()
                          ->andWhere(['restaurant_uuid' => $restaurant_uuid])
                          // ->with('items', 'items.options', 'items.options.extraOptions','items.itemImages')
                          ->orderBy([new \yii\db\Expression('sort_number IS NULL, sort_number ASC')]);
                          // ->all();


                  // foreach ($restaurantMenu as $category) {
                  //     unset($category['categoryItems']);
                  // }
                  //
                  // foreach ($restaurantMenu as $key => $category) {
                  //     unset($restaurantMenu[$key]['categoryItems']);
                  //
                  //     foreach ($category['items'] as $itemKey => $item) {
                  //       unset($restaurantMenu[$key]['items'][$itemKey]['unit_sold']);
                  //     }
                  // }

                  // return [
                  //     'restaurantMenu' => $restaurantMenu
                  // ];

                  return new ActiveDataProvider([
                      'query' => $query
                  ]);

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

          unset($item_model['unit_sold']);

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
