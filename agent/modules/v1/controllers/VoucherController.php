<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use agent\models\Voucher;


class VoucherController extends BaseController
{
    /**
     * @param $store_uuid
     * @return ActiveDataProvider
     * 
     * @api {get} /vouchers Get all vouchers
     * @apiName GetAllVouchers
     * @apiGroup Voucher
     * 
     * @apiSuccess {string} message Message.
     * @apiSuccess {string} operation Operation.
     */
    public function actionList($store_uuid = null)
    {
        $this->authCheck();
        $keyword = Yii::$app->request->get('keyword');
        $status = Yii::$app->request->get('status');

        $store = Yii::$app->accountManager->getManagedAccount($store_uuid);

        $query = Voucher::find()
            ->andWhere(['restaurant_uuid' => $store->restaurant_uuid])
            ->orderBy('voucher_id DESC');

        if ($keyword && $keyword != 'null') {
            $query->andWhere([
                'or',
                ['like', 'code', $keyword],
                ['like', 'description', $keyword],
                ['like', 'description_ar', $keyword],
            ]);
        }

        if (in_array($status, [1, 2])) {
            $query->andWhere(['voucher_status' => $status]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Create voucher
     * @return array
     * 
     * @api {post} /vouchers Create voucher
     * @apiName CreateVoucher
     * @apiGroup Voucher
     * 
     * @apiParam {string} store_uuid Store UUID.
     * @apiParam {string} code Code.
     * @apiParam {string} description Description.
     * @apiParam {string} description_ar Description in Arabic.
     * @apiParam {string} discount_type Discount type.
     * @apiParam {string} discount_amount Discount amount.
     * @apiParam {string} valid_from Valid from.
     * @apiParam {string} valid_until Valid until.
     * @apiParam {string} max_redemption Max redemption.
     * @apiParam {string} limit_per_customer Limit per customer.
     * @apiParam {string} minimum_order_amount Minimum order amount.
     * @apiParam {string} exclude_discounted_items Exclude discounted items.
     * @apiParam {string} is_public Is public.
     *  
     * @apiSuccess {string} message Message.
     * @apiSuccess {string} operation Operation.
     */
    public function actionCreate()
    {
        $this->authCheck();

        $store_uuid = Yii::$app->request->getBodyParam("store_uuid");

        $store = Yii::$app->accountManager->getManagedAccount($store_uuid);

        $model = new Voucher();
        $model->restaurant_uuid = $store->restaurant_uuid;
        $model->code = Yii::$app->request->getBodyParam("code");
        $model->description = Yii::$app->request->getBodyParam("description");
        $model->description_ar = Yii::$app->request->getBodyParam("description_ar");
        $model->discount_type = (int)Yii::$app->request->getBodyParam("discount_type");
        $model->discount_amount = (int)Yii::$app->request->getBodyParam("discount_amount");
        $model->is_public = Yii::$app->request->getBodyParam("is_public");

        $model->voucher_status = Voucher::VOUCHER_STATUS_ACTIVE;

        if (Yii::$app->request->getBodyParam("valid_from"))
            $model->valid_from = Yii::$app->request->getBodyParam("valid_from");
        if (Yii::$app->request->getBodyParam("valid_until"))
            $model->valid_until = Yii::$app->request->getBodyParam("valid_until");

        $model->max_redemption = Yii::$app->request->getBodyParam("max_redemption") ? Yii::$app->request->getBodyParam("max_redemption") : 0;
        $model->limit_per_customer = Yii::$app->request->getBodyParam("limit_per_customer") ? Yii::$app->request->getBodyParam("limit_per_customer") : 0;
        $model->minimum_order_amount = Yii::$app->request->getBodyParam("minimum_order_amount") ? Yii::$app->request->getBodyParam("minimum_order_amount") : 0;
        $model->exclude_discounted_items = Yii::$app->request->getBodyParam("exclude_discounted_items");

        if (!$model->save()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Voucher created successfully"),
            "data" => Voucher::findOne($model->voucher_id)
        ];
    }

    /**
     * Update voucher
     * 
     * @api {PATCH} /vouchers/:voucher_id Update voucher
     * @apiName UpdateVoucher
     * @apiGroup Voucher
     * 
     * @apiParam {string} store_uuid Store UUID.
     * @apiParam {string} voucher_id Voucher ID.
     * @apiParam {string} code Code.
     * @apiParam {string} description Description.
     * @apiParam {string} description_ar Description in Arabic.
     * @apiParam {string} discount_type Discount type.
     * @apiParam {string} discount_amount Discount amount.
     * @apiParam {string} valid_from Valid from.
     * @apiParam {string} valid_until Valid until.
     * @apiParam {string} max_redemption Max redemption.
     * @apiParam {string} limit_per_customer Limit per customer.
     * @apiParam {string} minimum_order_amount Minimum order amount.
     * @apiParam {string} exclude_discounted_items Exclude discounted items.
     * @apiParam {string} is_public Is public.
     * 
     * @apiSuccess {string} message Message.
     * @apiSuccess {string} operation Operation.
     */
    public function actionUpdate($voucher_id, $store_uuid = null)
    {
        $this->authCheck();
        $model = $this->findModel($voucher_id, $store_uuid);
        $model->code = Yii::$app->request->getBodyParam("code");
        $model->description = Yii::$app->request->getBodyParam("description");
        $model->description_ar = Yii::$app->request->getBodyParam("description_ar");
        $model->discount_type = (int)Yii::$app->request->getBodyParam("discount_type");
        $model->discount_amount = (int)Yii::$app->request->getBodyParam("discount_amount");

        //$model->voucher_status = Yii::$app->request->getBodyParam("voucher_status");

        $model->valid_from = Yii::$app->request->getBodyParam("valid_from");
        $model->valid_until = Yii::$app->request->getBodyParam("valid_until");
        $model->max_redemption = Yii::$app->request->getBodyParam("max_redemption") ? Yii::$app->request->getBodyParam("max_redemption") : 0;

        $model->limit_per_customer = Yii::$app->request->getBodyParam("limit_per_customer") ? Yii::$app->request->getBodyParam("limit_per_customer") : 0;
        $model->minimum_order_amount = Yii::$app->request->getBodyParam("minimum_order_amount") ? Yii::$app->request->getBodyParam("minimum_order_amount") : 0;
        $model->exclude_discounted_items = Yii::$app->request->getBodyParam("exclude_discounted_items");
        $model->is_public = Yii::$app->request->getBodyParam("is_public");

        if (!$model->save()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent', "We've faced a problem updating the voucher"),
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Voucher updated successfully"),
            "data" => $model
        ];
    }

    /**
     * Ability to update voucher status
     * 
     * @api {PATCH} /vouchers/update-status Update voucher status
     * @apiName UpdateVoucherStatus
     * @apiGroup Voucher
     * 
     * @apiParam {string} store_uuid Store UUID.
     * @apiParam {string} voucher_id Voucher ID.
     * @apiParam {string} voucherStatus Voucher status.
     * 
     * @apiSuccess {string} message Message.
     * @apiSuccess {string} operation Operation.
     */
    public function actionUpdateVoucherStatus()
    {
        $this->authCheck();
        
        $store_uuid = Yii::$app->request->getBodyParam('store_uuid');
        $voucher_id = Yii::$app->request->getBodyParam('voucher_id');
        $voucherStatus = (int)Yii::$app->request->getBodyParam('voucherStatus');

        $voucher = $this->findModel($voucher_id, $store_uuid);

        /*if (!$voucherStatus) {
            return [
                "operation" => "success",
                "message" => Yii::t('agent', "Invalid Voucher Status updated successfully")
            ];
        }*/

        $voucher->setScenario(Voucher::SCENARIO_UPDATE_STATUS);

        $voucher->voucher_status = $voucherStatus;

        if (!$voucher->save()) {
            return [
                "operation" => "error",
                "message" => $voucher->errors
            ];
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Voucher Status updated successfully")
        ];
    }

    /**
     * Return Voucher detail
     * @param type $store_uuid
     * @param type $order_uuid
     * @return type
     * 
     * @api {get} /vouchers/:voucher_id Voucher detail
     * @apiName VoucherDetail
     * @apiGroup Voucher
     * 
     * @apiSuccess {string} message Message.
     * @apiSuccess {string} operation Operation.
     */
    public function actionDetail($voucher_id, $store_uuid = null)
    {
        $this->authCheck();
        return $this->findModel($voucher_id, $store_uuid);
    }

    /**
     * Delete Voucher
     * 
     * @api {DELETE} /vouchers/:voucher_id Delete voucher
     * @apiName DeleteVoucher
     * @apiGroup Voucher
     * 
     * @apiParam {string} store_uuid Store UUID.
     * 
     * @apiSuccess {string} message Message.
     * @apiSuccess {string} operation Operation.
     */
    public function actionRemove($voucher_id, $store_uuid = null)
    {
        $this->authCheck();

        Yii::$app->accountManager->getManagedAccount($store_uuid);

        $model = $this->findModel($voucher_id, $store_uuid);

        $model->setScenario(Voucher::SCENARIO_DELETE);

        $model->is_deleted = 1;

        if (!$model->save()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent', "We've faced a problem deleting the voucher")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Voucher deleted successfully")
        ];
    }

    /**
     * Finds the Voucher model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Country the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($voucher_id, $store_uuid = null)
    {
        $store = Yii::$app->accountManager->getManagedAccount($store_uuid);

        if (($model = Voucher::find()->where(['voucher_id' => $voucher_id, 'restaurant_uuid' => $store->restaurant_uuid])->one()) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }

    private function authCheck()
    {
        if (!Yii::$app->accountManager->isOwner()) {
            throw new \yii\web\BadRequestHttpException(
                Yii::t('agent', 'You are not allowed to manage discounts. Please contact with store owner')
            );
        }

        //should have access to store
        Yii::$app->accountManager->getManagedAccount();
        return true;
    }
}
