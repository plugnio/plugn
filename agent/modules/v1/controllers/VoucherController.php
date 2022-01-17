<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use agent\models\Voucher;


class VoucherController extends Controller
{
    public function behaviors()
    {
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
    public function actions()
    {
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
     * @param $store_uuid
     * @return ActiveDataProvider
     */
    public function actionList($store_uuid)
    {
        $this->authCheck();
        $keyword = Yii::$app->request->get('keyword');
        $status = Yii::$app->request->get('status');

        Yii::$app->accountManager->getManagedAccount($store_uuid);

        $query = Voucher::find()
            ->andWhere(['restaurant_uuid' => $store_uuid])
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
     */
    public function actionCreate()
    {
        $this->authCheck();
        $store_uuid = Yii::$app->request->getBodyParam("store_uuid");
        Yii::$app->accountManager->getManagedAccount($store_uuid);

        $model = new Voucher();
        $model->restaurant_uuid = $store_uuid;
        $model->code = Yii::$app->request->getBodyParam("code");
        $model->description = Yii::$app->request->getBodyParam("description");
        $model->description_ar = Yii::$app->request->getBodyParam("description_ar");
        $model->discount_type = (int)Yii::$app->request->getBodyParam("discount_type");
        $model->discount_amount = (int)Yii::$app->request->getBodyParam("discount_amount");
        $model->voucher_status = Voucher::VOUCHER_STATUS_ACTIVE;

        if (Yii::$app->request->getBodyParam("valid_from"))
            $model->valid_from = Yii::$app->request->getBodyParam("valid_from");
        if (Yii::$app->request->getBodyParam("valid_until"))
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
            "message" => Yii::t('agent', "Voucher created successfully"),
            "data" => Voucher::findOne($model->voucher_id)
        ];
    }

    /**
     * Update voucher
     */
    public function actionUpdate($voucher_id, $store_uuid)
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
     */
    public function actionUpdateVoucherStatus()
    {
        $this->authCheck();
        
        $store_uuid = Yii::$app->request->getBodyParam('store_uuid');
        $voucher_id = Yii::$app->request->getBodyParam('voucher_id');
        $voucherStatus = (int)Yii::$app->request->getBodyParam('voucherStatus');

        $voucher_model = $this->findModel($voucher_id, $store_uuid);

        /*if (!$voucherStatus) {
            return [
                "operation" => "success",
                "message" => Yii::t('agent', "Invalid Voucher Status updated successfully")
            ];
        }*/

        $voucher_model->voucher_status = $voucherStatus;

        if (!$voucher_model->save()) {
            return [
                "operation" => "error",
                "message" => $voucher_model->errors
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
     */
    public function actionDetail($store_uuid, $voucher_id)
    {
        $this->authCheck();
        return $this->findModel($voucher_id, $store_uuid);
    }

    /**
     * Delete Voucher
     */
    public function actionRemove($voucher_id, $store_uuid)
    {
        $this->authCheck();
        Yii::$app->accountManager->getManagedAccount($store_uuid);
        $model = $this->findModel($voucher_id, $store_uuid);

        if (!$model->delete()) {
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
    protected function findModel($voucher_id, $store_uuid)
    {
        $store_model = Yii::$app->accountManager->getManagedAccount($store_uuid);

        if (($model = Voucher::find()->where(['voucher_id' => $voucher_id, 'restaurant_uuid' => $store_model->restaurant_uuid])->one()) !== null) {
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
