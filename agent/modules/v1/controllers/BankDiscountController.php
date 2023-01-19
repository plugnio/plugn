<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use agent\models\BankDiscount;


class BankDiscountController extends BaseController {

    /**
     * only owner will have access
     */
    private function ownerCheck()
    {
        if(!Yii::$app->accountManager->isOwner()) {
            throw new \yii\web\BadRequestHttpException(
                Yii::t('agent', 'You are not allowed to view discounts. Please contact with store owner')
            );
        }

        //should have access to store
        Yii::$app->accountManager->getManagedAccount();
        return true;
    }

    /**
     * @param $store_uuid
     * @return ActiveDataProvider
     */
    public function actionList($store_uuid = null) {

        $this->ownerCheck();
        $keyword = Yii::$app->request->get('keyword');
        $status = Yii::$app->request->get('status');

        Yii::$app->accountManager->getManagedAccount($store_uuid);

        $query =  BankDiscount::find()->joinWith('bank');

        if ($keyword){
            $query->andWhere(
                ['or',
                    ['like', 'bank_discount.discount_amount', $keyword],
                    ['like', 'bank.bank_name', $keyword],
                    ['like', 'bank_discount.max_redemption', $keyword],
                    ['like', 'bank_discount.discount_type', $keyword],
                    ['like', 'bank_discount.limit_per_customer', $keyword]
                ]
            );
        }

        $query->andWhere(['bank_discount_status' => $status]);

        $query->andWhere(['bank_discount.restaurant_uuid' => $store_uuid]);

        return new ActiveDataProvider([
          'query' => $query
        ]);
    }

    /**
     * Create bank discount
     * @return array
     */
    public function actionCreate() {
        $this->ownerCheck();
        $store_uuid = Yii::$app->request->getBodyParam("store_uuid");
        Yii::$app->accountManager->getManagedAccount($store_uuid);

        $model = new BankDiscount();
        $model->restaurant_uuid = $store_uuid;
        $model->bank_id = Yii::$app->request->getBodyParam("bank_id");
        $model->discount_type = (int) Yii::$app->request->getBodyParam("discount_type");
        $model->discount_amount = (int) Yii::$app->request->getBodyParam("discount_amount");
        $model->bank_discount_status = BankDiscount::BANK_DISCOUNT_STATUS_ACTIVE;

        if(Yii::$app->request->getBodyParam("valid_from"))
          $model->valid_from = Yii::$app->request->getBodyParam("valid_from");
        if(Yii::$app->request->getBodyParam("valid_until"))
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
            "message" => Yii::t ('agent',"Bank Discount created successfully"),
            "model" => BankDiscount::findOne($model->bank_discount_id)
        ];
    }

    /**
     * @param $bank_discount_id
     * @param $store_uuid
     * @return array|string[]
     * @throws NotFoundHttpException
     */
     public function actionUpdate($bank_discount_id, $store_uuid = null)
     {
         $this->ownerCheck();

         $model = $this->findModel($bank_discount_id, $store_uuid);

         $model->bank_id = Yii::$app->request->getBodyParam("bank_id");
         $model->discount_type = (int) Yii::$app->request->getBodyParam("discount_type");
         $model->discount_amount = (int) Yii::$app->request->getBodyParam("discount_amount");
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
                     "message" => Yii::t ('agent',"We've faced a problem updating the bank discount")
                 ];
             }
         }

         return [
             "operation" => "success",
             "message" => Yii::t ('agent',"Bank Discount updated successfully"),
             "model" => $model
         ];
     }

    /**
     * Ability to update bank discount status
     * @return array
     * @throws NotFoundHttpException
     */
     public function actionUpdateBankDiscountStatus() {
         
         $this->ownerCheck();

         $store_uuid =  Yii::$app->request->getBodyParam('store_uuid');
         $bank_discount_id =  Yii::$app->request->getBodyParam('bank_discount_id');
         
         $bankDiscountStatus = (int) Yii::$app->request->getBodyParam('bankDiscountStatus');

         $bank_discount_model = $this->findModel($bank_discount_id, $store_uuid);

         if ($bankDiscountStatus) {
              $bank_discount_model->bank_discount_status = $bankDiscountStatus;

             if (!$bank_discount_model->save()) {
                 return [
                     "operation" => "error",
                     "message" => $bank_discount_model->errors
                 ];
             }

             return [
                 "operation" => "success",
                 "message" => Yii::t ('agent',"Bank Discount Status updated successfully"),
                 "model" => $bank_discount_model
             ];
         }
     }

    /**
     * @param $store_uuid
     * @param $bank_discount_id
     * @return BankDiscount
     * @throws NotFoundHttpException
     */
    public function actionDetail($store_uuid = null, $bank_discount_id) {
        $this->ownerCheck();
        return $this->findModel($bank_discount_id, $store_uuid);
    }

     /**
      * Delete Bank Discount
      */
     public function actionDelete($bank_discount_id, $store_uuid = null)
     {
         $this->ownerCheck();
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
                     "message" => Yii::t ('agent',"We've faced a problem deleting the bank discount")
                 ];
             }
         }

         return [
             "operation" => "success",
             "message" => Yii::t ('agent',"Bank discount deleted successfully")
         ];
     }

    /**
     * Finds the Bank discount model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BankDiscount the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($bank_discount_id, $store_uuid = null)
    {
        $store = Yii::$app->accountManager->getManagedAccount($store_uuid);

        $model = BankDiscount::find()
            ->andWhere([
                'bank_discount_id' => $bank_discount_id,
                'restaurant_uuid' => $store->restaurant_uuid
            ])
            ->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
