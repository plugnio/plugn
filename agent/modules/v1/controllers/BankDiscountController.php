<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use common\models\BankDiscount;

class BankDiscountController extends Controller {

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
    * Get all store's bankDiscounts
     * @param type $id
     * @param type $store_uuid
     * @return type
     */
    public function actionList($store_uuid) {

      if (Yii::$app->accountManager->getManagedAccount($store_uuid)) {

          $bankDiscounts =  BankDiscount::find()
                    ->where(['restaurant_uuid' => $store_uuid]);

          return new ActiveDataProvider([
            'query' => $bankDiscounts
          ]);

      }

    }


    /**
    * Return a List of bank discount by keyword
   */
    public function actionFilter($store_uuid)
    {
      if (Yii::$app->accountManager->getManagedAccount($store_uuid)) {

        $keyword = Yii::$app->request->get('keyword');

        $query =  BankDiscount::find()->joinWith('bank');

        if($keyword) {
              $query->andWhere(['like', 'discount_amount', $keyword]);
              $query->orWhere(['like', 'bank.bank_name', $keyword]);
              $query->orWhere(['like', 'max_redemption', $keyword]);
              $query->orWhere(['like', 'discount_type', $keyword]);
              $query->orWhere(['like', 'max_redemption', $keyword]);
              $query->orWhere(['like', 'limit_per_customer', $keyword]);
          }

        $query->andWhere(['restaurant_uuid' => $store_uuid]);

        return new ActiveDataProvider([
            'query' => $query
        ]);

      }
    }



    /**
     * Create bank discount
     * @return array
     */
    public function actionCreate() {

        $store_uuid = Yii::$app->request->getBodyParam("store_uuid");
        Yii::$app->accountManager->getManagedAccount($store_uuid);

        $model = new BankDiscount();
        $model->restaurant_uuid = $store_uuid;
        $model->bank_id = Yii::$app->request->getBodyParam("bank_id");
        $model->discount_type = (int) Yii::$app->request->getBodyParam("discount_type");
        $model->discount_amount = (int) Yii::$app->request->getBodyParam("discount_amount");
        $model->bank_discount_status = BankDiscount::BANK_DISCOUNT_STATUS_ACTIVE;
        $model->valid_from = Yii::$app->request->getBodyParam("valid_from");
        $model->valid_until = Yii::$app->request->getBodyParam("valid_until");
        $model->max_redemption = Yii::$app->request->getBodyParam("max_redemption") ? Yii::$app->request->getBodyParam("max_redemption") : 0;
        $model->limit_per_customer = Yii::$app->request->getBodyParam("limit_per_customer") ? Yii::$app->request->getBodyParam("limit_per_customer") : 0;
        $model->minimum_order_amount = Yii::$app->request->getBodyParam("minimum_order_amount") ? Yii::$app->request->getBodyParam("minimum_order_amount") : 0;


        if (!$model->save()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        return [
            "operation" => "success",
            "message" => "Bank Discount created successfully",
            "data" => BankDiscount::findOne($model->bank_discount_id)
        ];

    }



     /**
      * Update bank discount
      */
     public function actionUpdate($bank_discount_id, $store_uuid)
     {

         $model = $this->findModel($bank_discount_id, $store_uuid);

         $model->bank_id = Yii::$app->request->getBodyParam("bank_id");
         $model->discount_type = (int) Yii::$app->request->getBodyParam("discount_type");
         $model->discount_amount = (int) Yii::$app->request->getBodyParam("discount_amount");
         $model->bank_discount_status =  Yii::$app->request->getBodyParam("bank_discount_status");
         $model->valid_from = Yii::$app->request->getBodyParam("valid_from");
         $model->valid_until = Yii::$app->request->getBodyParam("valid_until");
         $model->max_redemption = Yii::$app->request->getBodyParam("max_redemption") ? Yii::$app->request->getBodyParam("max_redemption") : 0;
         $model->limit_per_customer = Yii::$app->request->getBodyParam("limit_per_customer") ? Yii::$app->request->getBodyParam("limit_per_customer") : 0;
         $model->minimum_order_amount = Yii::$app->request->getBodyParam("minimum_order_amount") ? Yii::$app->request->getBodyParam("minimum_order_amount") : 0;


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
                     "message" => "We've faced a problem updating the bank discount"
                 ];
             }
         }

         return [
             "operation" => "success",
             "message" => "Bank Discount updated successfully"
         ];
     }




    /**
    * Return bank discount detail
     * @param type $store_uuid
     * @param type $order_uuid
     * @return type
     */
    public function actionDetail($store_uuid, $bank_discount_id) {

        Yii::$app->accountManager->getManagedAccount($store_uuid);

        $bank_discount_model =  $this->findModel($bank_discount_id, $store_uuid);

        return $bank_discount_model;
  }


     /**
      * Delete Bank Discount
      */
     public function actionDelete($bank_discount_id, $store_uuid)
     {
         Yii::$app->accountManager->getManagedAccount($store_uuid);
         $model =  $this->findModel($bank_discount_id, $store_uuid);

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
                     "message" => "We've faced a problem deleting the bank discount"
                 ];
             }
         }

         return [
             "operation" => "success",
             "message" => "Bank discount deleted successfully"
         ];
     }



    /**
     * Finds the Bank discount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Country the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($bank_discount_id, $store_uuid)
    {
        if (($model = BankDiscount::find()->where(['bank_discount_id' => $bank_discount_id, 'restaurant_uuid' => $store_uuid])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }


}
