<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use agent\models\Category;


class CategoryController extends Controller {

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

        // Bearer Auth checks for Authorize: Bearer <Token> header to login the user
              $behaviors['authenticator'] = [
                  'class' => \yii\filters\auth\HttpBearerAuth::className(),
              ];
              // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
              $behaviors['authenticator']['except'] = ['options'];

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
    * Get all store's categories
     * @param type $id
     * @param type $store_uuid
     * @return ActiveDataProvider
     */
     public function actionList($store_uuid) {

         $keyword = Yii::$app->request->get('keyword');
         $page = Yii::$app->request->get('page');

         Yii::$app->accountManager->getManagedAccount($store_uuid);

         $query =  Category::find();

         if ($keyword){
             $query->andWhere([
                 'or', [
                     ['like', 'title', $keyword],
                     ['like', 'title_ar', $keyword],
                     ['like', 'subtitle', $keyword],
                     ['like', 'subtitle_ar', $keyword]
                 ]
             ]);
         }

         $query->andWhere(['restaurant_uuid' => $store_uuid]);

         if(!$page) {
             return new ActiveDataProvider([
                 'query' => $query,
                 'pagination' => false
             ]);
         }

         return new ActiveDataProvider([
           'query' => $query
         ]);
     }

    /**
     * Create category
     * @return array
     */
    public function actionCreate() {

        $store_uuid = Yii::$app->request->getBodyParam("store_uuid");

        Yii::$app->accountManager->getManagedAccount($store_uuid);

        $model = new Category();
        $model->restaurant_uuid = $store_uuid;
        $model->title = Yii::$app->request->getBodyParam("title");
        $model->title_ar = Yii::$app->request->getBodyParam("title_ar");
        $model->subtitle = Yii::$app->request->getBodyParam("subtitle");
        $model->subtitle_ar = Yii::$app->request->getBodyParam("subtitle_ar");
        $model->sort_number = Yii::$app->request->getBodyParam("sort_number");

        //validate before uploading image

        if (!$model->validate()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        $category_image = Yii::$app->request->getBodyParam('category_image');

        if($category_image) {
            $model->updateImage($category_image);
        }

        if (!$model->save()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Category created successfully"),
            "model" => Category::findOne($model->category_id)
        ];
    }

     /**
      * Update category
      */
     public function actionUpdate($category_id, $store_uuid)
     {
         $model = $this->findModel($category_id, $store_uuid);

         $model->title = Yii::$app->request->getBodyParam("title");
         $model->title_ar = Yii::$app->request->getBodyParam("title_ar");
         $model->subtitle = Yii::$app->request->getBodyParam("subtitle");
         $model->subtitle_ar = Yii::$app->request->getBodyParam("subtitle_ar");
         $model->sort_number = Yii::$app->request->getBodyParam("sort_number");

         //validate before uploading image

         if (!$model->validate()) {
             return [
                 "operation" => "error",
                 "message" => $model->errors
             ];
         }

         $category_image = Yii::$app->request->getBodyParam('category_image');

         if($model->category_image != $category_image) {
             $model->updateImage($category_image);
         }

         if (!$model->save())
         {
             if (isset($model->errors)) {
                 return [
                     "operation" => "error",
                     "message" => $model->errors
                 ];
             } else {
                 return [
                     "operation" => "error",
                     "message" => Yii::t('agent',"We've faced a problem updating the category")
                 ];
             }
         }

         return [
             "operation" => "success",
             "message" => Yii::t('agent',"Category updated successfully"),
             "model" => $model
         ];
     }

    /**
     * Allows agent to upload category image
     */
    public function actionUploadCategoryImage() {

        $category_image = urldecode(Yii::$app->request->getBodyParam('category_image'));
        $store_uuid = Yii::$app->request->getBodyParam('store_uuid');
        $category_id = Yii::$app->request->getBodyParam('category_id');

        $model = $this->findModel($category_id, $store_uuid);

        if (!isset($model->category_id)) {
            return [
                "operation" => "error",
                "message" => Yii::t('agent','Invalid Category ID')
            ];
        }

        //Delete old category image

        if ($model->category_image) {
            $model->deleteCategoryImage();
        }

        $model->category_image = basename($category_image);

        $result = $model->moveCategoryImageFromS3toCloudinary();

        if ($result) {
            return [
                'operation' => 'success',
                'url' => Url::to("@categoty-image/" . 'restaurants/' . $model->restaurant_uuid . "/category/" . $model->category_image),
                'logo' => $model->category_image,
                'message' => Yii::t('agent', 'Category Image Uploaded Successfully')
            ];
        } else {
            return [
                'operation' => 'error',
                'message' => $model->errors
            ];
        }
    }

      /**
       * Delete Category
       */
      public function actionDelete($category_id, $store_uuid)
      {
          Yii::$app->accountManager->getManagedAccount($store_uuid);
          $model =  $this->findModel($category_id, $store_uuid);

          if (!$model->delete())
          {
              if (isset($model->errors)) {
                  return [
                      "operation" => "error",
                      "message" => $model->errors
                  ];
              } else {
                  return [
                      "operation" => "error",
                      "message" => Yii::t('agent',"We've faced a problem deleting the category")
                  ];
              }
          }

          return [
              "operation" => "success",
              "message" => Yii::t('agent',"Category deleted successfully")
          ];
      }


        /**
        * Return Category detail
         * @param type $store_uuid
         * @param type $category_id
         * @return type
         */
        public function actionDetail($store_uuid, $category_id) {

            return $this->findModel($category_id, $store_uuid);
      }


      /**
       * Finds the Category model based on its primary key value.
       * If the model is not found, a 404 HTTP exception will be thrown.
       * @param integer $id
       * @return Category the loaded model
       * @throws NotFoundHttpException if the model cannot be found
       */
      protected function findModel($category_id, $store_uuid)
      {
          $store_model = Yii::$app->accountManager->getManagedAccount($store_uuid);

          if (($model = Category::find()->where(['category_id' => $category_id, 'restaurant_uuid' => $store_model->restaurant_uuid])->one()) !== null) {
              return $model;
          } else {
              throw new NotFoundHttpException('The requested record does not exist.');
          }
      }
}
