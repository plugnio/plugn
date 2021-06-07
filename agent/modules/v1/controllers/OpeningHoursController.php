<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use agent\models\OpeningHour;

class OpeningHoursController extends Controller {

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
    * Get all opening hours store
     * @param type $id
     * @param type $store_uuid
     * @return type
     */
    public function actionList($store_uuid) {

      $store_model = Yii::$app->accountManager->getManagedAccount($store_uuid);

      $query = OpeningHour::find()
                    ->where(['restaurant_uuid' => $store_model->restaurant_uuid])
                    ->orderBy(['day_of_week' => SORT_ASC, 'open_at' => SORT_ASC]);


      return new ActiveDataProvider([
          'query' => $query
      ]);

    }



    /**
     * Create opening hours
     * @return array
     */
    public function actionCreate($day_of_week, $store_uuid) {

        $store_model = Yii::$app->accountManager->getManagedAccount($store_uuid);

        $model = new OpeningHour();
        $model->restaurant_uuid = $store_model->restaurant_uuid;

        $model->day_of_week = $day_of_week;
        $model->open_at = Yii::$app->request->getBodyParam("open_at");
        $model->close_at = Yii::$app->request->getBodyParam("close_at");


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
                   "message" => "We've faced a problem creating the Opening Hour"
               ];
           }
       }



       return [
           "operation" => "success",
           "message" => "Opening Hour created successfully",
           "model" => $model
       ];

    }

    /**
     * Update opening hours
     * @return array
     */
    public function actionUpdate($day_of_week, $store_uuid) {

        $model = $this->findModel($day_of_week, $store_uuid);

        $model->day_of_week = $day_of_week;
        $model->open_at = Yii::$app->request->getBodyParam("open_at");
        $model->close_at = Yii::$app->request->getBodyParam("close_at");


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
                   "message" => "We've faced a problem updating the Opening Hour"
               ];
           }
       }



       return [
           "operation" => "success",
           "message" => "Opening Hour updated successfully",
           "model" => $model
       ];

    }


        /**
        * Return OpeningHour detail
         * @param type $store_uuid
         * @param type $opening_hour_id
         * @return type
         */
        public function actionDetail($store_uuid, $day_of_week) {

          $model =  $this->findModel($day_of_week, $store_uuid);

          return $model;

      }


      /**
       * Delete Opening hours
       */
      public function actionDelete($day_of_week, $store_uuid)
      {
          $model =  $this->findModel($day_of_week, $store_uuid);


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
                      "message" => "We've faced a problem deleting opening hours"
                  ];
              }
          }

          return [
              "operation" => "success",
              "message" => "Opening Hour deleted successfully"
          ];
      }



      /**
       * Finds the OpeningHour model based on its primary key value.
       * If the model is not found, a 404 HTTP exception will be thrown.
       * @param integer $id
       * @return OpeningHour the loaded model
       * @throws NotFoundHttpException if the model cannot be found
       */
      protected function findModel($day_of_week, $store_uuid)
      {
          $store_model = Yii::$app->accountManager->getManagedAccount($store_uuid);

          if (($model = OpeningHour::find()->where(['day_of_week' => $day_of_week, 'restaurant_uuid' => $store_model->restaurant_uuid])->one()) !== null) {
              return $model;
          } else {
              throw new NotFoundHttpException('The requested record does not exist.');
          }
      }



}
