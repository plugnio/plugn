<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use common\models\BusinessLocation;

class BusinessLocationController extends Controller {

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
    * Get all store's branches
     * @param type $id
     * @param type $store_uuid
     * @return type
     */
    public function actionList($store_uuid) {

      $keyword = Yii::$app->request->get('keyword');

      Yii::$app->accountManager->getManagedAccount($store_uuid);

      $query =  BusinessLocation::find()->joinWith('country');

      if ($keyword){
        $query->where(['like', 'business_location_name', $keyword]);
        $query->orWhere(['like', 'business_location_name_ar', $keyword]);
        $query->orWhere(['like', 'country.country_name', $keyword]);
        $query->orWhere(['like', 'country.country_name_ar', $keyword]);
      }

      $query->andWhere(['restaurant_uuid' => $store_uuid]);

      return new ActiveDataProvider([
        'query' => $query
      ]);

  }

    /**
     * Create Business Location
     * @return array
     */
    public function actionCreate() {

        $store_uuid = Yii::$app->request->getBodyParam("store_uuid");
        Yii::$app->accountManager->getManagedAccount($store_uuid);


        $model = new BusinessLocation();
        $model->restaurant_uuid = $store_uuid;
        $model->country_id =  Yii::$app->request->getBodyParam("country_id");
        $model->business_location_name =  Yii::$app->request->getBodyParam("business_location_name");
        $model->business_location_name_ar =  Yii::$app->request->getBodyParam("business_location_name_ar");
        $model->support_pick_up = (int) Yii::$app->request->getBodyParam("support_pick_up");
        $model->business_location_tax = (double) Yii::$app->request->getBodyParam("business_location_tax");
        $model->mashkor_branch_id =  Yii::$app->request->getBodyParam("mashkor_branch_id");
        $model->armada_api_key =  Yii::$app->request->getBodyParam("armada_api_key");
        $model->address =  Yii::$app->request->getBodyParam("address");
        $model->latitude =  Yii::$app->request->getBodyParam("latitude");
        $model->longitude =  Yii::$app->request->getBodyParam("longitude");


        if (!$model->save()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        return [
            "operation" => "success",
            "message" => "Business Location created successfully",
            "model" => BusinessLocation::findOne($model->business_location_id)
        ];

    }



     /**
      * Update Business Location
      */
     public function actionUpdate($business_location_id, $store_uuid)
     {

         $model = $this->findModel($business_location_id, $store_uuid);

         $model->country_id =  Yii::$app->request->getBodyParam("country_id");
         $model->business_location_name =  Yii::$app->request->getBodyParam("business_location_name");
         $model->business_location_name_ar =  Yii::$app->request->getBodyParam("business_location_name_ar");
         $model->support_pick_up = (int) Yii::$app->request->getBodyParam("support_pick_up");
         $model->business_location_tax = (double) Yii::$app->request->getBodyParam("business_location_tax");
         $model->mashkor_branch_id =  Yii::$app->request->getBodyParam("mashkor_branch_id");
         $model->armada_api_key =  Yii::$app->request->getBodyParam("armada_api_key");
         $model->address =  Yii::$app->request->getBodyParam("address");
         $model->latitude =  Yii::$app->request->getBodyParam("latitude");
         $model->longitude =  Yii::$app->request->getBodyParam("longitude");


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
                     "message" => "We've faced a problem updating the business location"
                 ];
             }
         }

         return [
             "operation" => "success",
             "message" => "Business Location updated successfully",
             "model" => $model
         ];
     }

    /**
    * Return Business Location detail
     * @param type $store_uuid
     * @param type $order_uuid
     * @return type
     */
    public function actionDetail($store_uuid, $business_location_id) {

          $model = $this->findModel($business_location_id, $store_uuid);
          return $model;
  }


       /**
        * Delete Business Location
        */
       public function actionDelete($business_location_id, $store_uuid)
       {
           Yii::$app->accountManager->getManagedAccount($store_uuid);
           $model =  $this->findModel($business_location_id, $store_uuid);

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
                       "message" => "We've faced a problem deleting the business location"
                   ];
               }
           }

           return [
               "operation" => "success",
               "message" => "Business location deleted successfully"
           ];
       }



      /**
       * Finds the Business Location model based on its primary key value.
       * If the model is not found, a 404 HTTP exception will be thrown.
       * @param integer $id
       * @return BusinessLocation the loaded model
       * @throws NotFoundHttpException if the model cannot be found
       */
      protected function findModel($business_location_id, $store_uuid)
      {
          $store_model = Yii::$app->accountManager->getManagedAccount($store_uuid);

          if (($model = BusinessLocation::find()->where(['business_location_id' => $business_location_id, 'restaurant_uuid' => $store_model->restaurant_uuid])->one()) !== null) {
              return $model;
          } else {
              throw new NotFoundHttpException('The requested record does not exist.');
          }
      }


}
