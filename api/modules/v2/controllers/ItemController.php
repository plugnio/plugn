<?php

namespace api\modules\v2\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use api\models\Item;
use api\models\Category;
use api\models\Restaurant;
use yii\web\NotFoundHttpException;

class ItemController extends BaseController {

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
    public function actionCategoryProducts($category_id = null, $slug = null) {

      $restaurant_uuid = Yii::$app->request->get("restaurant_uuid");

      if($restaurant_uuid) {

          $filter = $category_id? [
              'category.restaurant_uuid' => $restaurant_uuid,
              'category.category_id' => $category_id
          ]: [
              'category.restaurant_uuid' => $restaurant_uuid,
              'category.slug' => $slug
          ];

        $category = Category::find()
                    ->andWhere($filter)
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
     * list items
     * @return ActiveDataProvider
     */
    public function actionItems()
    {
        $restaurant_uuid = Yii::$app->request->get("restaurant_uuid");
        $category_id = Yii::$app->request->get("category_id");
        $keyword = Yii::$app->request->get("keyword");

        $restaurant = Restaurant::find()
            ->where(['restaurant_uuid' => $restaurant_uuid])
            ->one();

        $query = $restaurant
            ->getItems()
            ->with('options', 'options.extraOptions', 'itemImages')
            ->andWhere (['item_status' => Item::ITEM_STATUS_PUBLISH])
            ->orderBy ([new \yii\db\Expression('
                item.sort_number IS NULL,
                item.sort_number ASC,
                item.sku IS NULL,
                item.sku ASC')
            ]);

        if($keyword) {
            $query->filterKeyword($keyword);
        }

        if($category_id) {
            $query->joinWith('categoryItems')
                ->andWhere(['category_id' => $category_id]);
        }
           // ->orderBy([new \yii\db\Expression(' sort_number IS NULL, sort_number ASC')]);
        //->with('items', 'items.options', 'items.options.extraOptions', 'items.itemImages')

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Return restaurant menu
     */
    public function actionRestaurantMenu() {

        $restaurant_uuid = Yii::$app->request->get("restaurant_uuid");
        $wihoutItems = Yii::$app->request->get("wihoutItems");

        $restaurant = Restaurant::find()->where(['restaurant_uuid' => $restaurant_uuid])->one();

        if (!$restaurant) {
            return [
                'operation' => 'error',
                'message' => 'Store Uuid is invalid'
            ];
        }

          if($restaurant->is_myfatoorah_enable)
            unset($restaurant['live_public_key']);

        $restaurantMenu = [];

        if(!$wihoutItems) {

            $restaurantMenu = Category::find()
                ->andWhere(['restaurant_uuid' => $restaurant_uuid])
                ->with('items', 'items.options', 'items.options.extraOptions', 'items.itemImages')
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
        }

            return [
                'restaurant' => $restaurant,
                'restaurantTheme' => $restaurant->getRestaurantTheme()->one(),
                'restaurantMenu' => $restaurantMenu
            ];
    }

    /**
     * Return restaurant menu
     */
    public function actionCategoryItems() {

        $restaurant_uuid = Yii::$app->request->get("restaurant_uuid");
        $category_id = Yii::$app->request->get("category_id");
        $keyword = Yii::$app->request->get("keyword");

        $restaurant = Restaurant::find()
            ->where(['restaurant_uuid' => $restaurant_uuid])->one();

        if (!$restaurant) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
 
        $query = Category::find()
                ->andWhere(['restaurant_uuid' => $restaurant_uuid])
                ->with('items', 'items.options', 'items.itemImage')
                ->orderBy([new \yii\db\Expression('sort_number IS NULL, sort_number ASC')]);

        if($keyword) {
            $query->filterKeyword($keyword);
        }

        if($category_id) {
            $query->joinWith('categoryItems')
                ->andWhere(['category_id' => $category_id]);
        }

        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);        
    }

    /**
     * Return item's data
     */
    public function actionItemData()
    {
        $item_uuid = Yii::$app->request->get("item_uuid");
        $expand = Yii::$app->request->get("expand");

        //for old stores

        if(!$expand)
        {
            $item_model = Item::find()
                ->andWhere(['item_uuid' => $item_uuid, 'item_status' => Item::ITEM_STATUS_PUBLISH])
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

        return $this->findModel($item_uuid);
    }

    /**
     * Return item's data
     */
    public function actionView($slug)
    {
        $restaurant_uuid = Yii::$app->request->get("restaurant_uuid");

        return $this->findBySlug($slug, $restaurant_uuid);
    }

    /**
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = Item::findOne(['item_uuid' => $id, 'item_status' => Item::ITEM_STATUS_PUBLISH]);

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $slug
     * @return Item
     * @throws NotFoundHttpException
     */
    protected function findBySlug($slug, $restaurant_uuid = null)
    {
        $query = Item::find()
            ->andWhere(['slug' => $slug, 'item_status' => Item::ITEM_STATUS_PUBLISH]);

        if($restaurant_uuid) {
            $query->andWhere(['restaurant_uuid' => $restaurant_uuid]);
        }

        $model = $query->one();

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
