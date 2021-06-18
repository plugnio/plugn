<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use agent\models\Restaurant;
use common\components\FileUploader;
use agent\models\RestaurantPaymentMethod;
use agent\models\TapQueue;


class StoreController extends Controller
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
     * Return an overview of the store details
     * @param type $store_uuid
     */
    public function actionDetail($store_uuid)
    {
        return $this->findModel($store_uuid);
    }

    /**
     * Return an overview of the store details
     * @param type $store_uuid
     */
    public function actionUpdate($store_uuid)
    {
        $store = $this->findModel($store_uuid);

        $store->country_id = Yii::$app->request->getBodyParam('country_id');
        $store->restaurant_email_notification = Yii::$app->request->getBodyParam('email_notification');
        $store->phone_number = Yii::$app->request->getBodyParam('mobile');
        $store->phone_number_country_code = Yii::$app->request->getBodyParam('mobile_country_code');
        $store->name = Yii::$app->request->getBodyParam('name');
        $store->name_ar = Yii::$app->request->getBodyParam('name_ar');
        $store->schedule_interval = Yii::$app->request->getBodyParam('schedule_interval');
        $store->schedule_order = Yii::$app->request->getBodyParam('schedule_order');
        $store->restaurant_email = Yii::$app->request->getBodyParam('store_email');
        $store->tagline = Yii::$app->request->getBodyParam('tagline');
        $store->tagline_ar = Yii::$app->request->getBodyParam('tagline_ar');

        if (!$store->save()) {
            return self::message("error",$store->getErrors());
        }

        return self::message("success",'Store details updated successfully');
    }

    /**
     * Displays  Real time orders
     *
     * @return mixed
     */
    public function actionConnectDomain()
    {
        $domain = Yii::$app->request->getBodyParam('domain');

        $store = Yii::$app->accountManager->getManagedAccount();

        if ($store->restaurant_domain == $domain) {
            return self::message("error",'New domain can not be same as old domain');
        }

        $old_domain = $store->restaurant_domain;

        $store->setScenario(Restaurant::SCENARIO_CONNECT_DOMAIN);

        $store->restaurant_domain = rtrim($domain, '/');

        if (!$store->save()) {
            return self::message("error",$store->getErrors());
        }

        \Yii::$app->mailer->compose([
            'html' => 'domain-update-request',
        ], [
            'store_name' => $store->name,
            'new_domain' => $store->restaurant_domain,
            'old_domain' => $old_domain
        ])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo(Yii::$app->params['supportEmail'])
            ->setSubject('[Plugn] Agent updated DN')
            ->send();

        return self::message("success","Congratulations you have successfully changed your domain name");
    }

    /**
     * Disable payment method
     * @return mixed
     */
    public function actionDisablePaymentMethod($id, $paymentMethodId)
    {
        $model = $this->findModel($id);

        RestaurantPaymentMethod::deleteAll([
            'restaurant_uuid' => $model->restaurant_uuid,
            'payment_method_id' => $paymentMethodId
        ]);

        return self::message("success",'Payment method disabled successfully');
    }

    /**
     * Enable payment method
     * @return mixed
     */
    public function actionEnablePaymentMethod($id, $paymentMethodId)
    {
        $model = $this->findModel($id);

        $method = new RestaurantPaymentMethod();
        $method->payment_method_id = $paymentMethodId;
        $method->restaurant_uuid = $model->restaurant_uuid;

        if (!$method->save()) {
            return self::message("error",$method->getErrors());
        }
        return self::message("success",'Payment method added successfully');
    }

    /**
     * View payment settings page
     * @return mixed
     */
    public function actionViewPaymentMethods($id)
    {
        $model = $this->findModel($id);

        $query = $model->getPaymentMethods();

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Create tap account
     * @param type $id
     * @return array
     */
    public function actionCreateTapAccount($id)
    {
        $model = $this->findModel($id);

        $model->setScenario(Restaurant::SCENARIO_CREATE_TAP_ACCOUNT);

        $model->owner_first_name = Yii::$app->request->getBodyParam('owner_first_name');
        $model->owner_last_name = Yii::$app->request->getBodyParam('owner_last_name');
        $model->owner_email = Yii::$app->request->getBodyParam('owner_email');
        $model->owner_number = Yii::$app->request->getBodyParam('owner_number');
        $model->company_name = Yii::$app->request->getBodyParam('company_name');
        $model->vendor_sector = Yii::$app->request->getBodyParam('vendor_sector');
        $model->business_type = Yii::$app->request->getBodyParam('business_type');
        $model->license_number = Yii::$app->request->getBodyParam('license_number');
        $model->iban = Yii::$app->request->getBodyParam('iban');
        $model->identification_file_front_side = Yii::$app->request->getBodyParam('identification_file_front_side');
        $model->identification_file_back_side = Yii::$app->request->getBodyParam('identification_file_back_side');
        $model->commercial_license_file = Yii::$app->request->getBodyParam('commercial_license_file');
        $model->authorized_signature_file = Yii::$app->request->getBodyParam('authorized_signature_file');

        if ($model->country->iso != 'KW') {
            $model->business_type = 'corp';
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$model->save()) {
                $transaction->rollBack();
                return self::message("error",$model->errors);
            }

            /*-------- uploading documents-------*/
            if (
            !$model->uploadFileToCloudinary(
                $model->identification_file_front_side, 'identification_file_front_side')
            ) {
                $transaction->rollBack();
                return self::message("error",$model->errors);
            }

            if (
            !$model->uploadFileToCloudinary(
                $model->identification_file_back_side, 'identification_file_back_side')
            ) {
                $transaction->rollBack();
                return self::message("error",$model->errors);
            }

            if (
            !$model->uploadFileToCloudinary(
                $model->commercial_license_file, 'commercial_license_file')
            ) {
                $transaction->rollBack();
                return self::message("error",$model->errors);
            }
            if (
            !$model->uploadFileToCloudinary(
                $model->authorized_signature_file, 'authorized_signature_file')
            ) {
                $transaction->rollBack();
                return self::message("error",$model->errors);
            }

            /*-------- uploading documents-------*/

            $tap_queue_model = new TapQueue;
            $tap_queue_model->queue_status = TapQueue::QUEUE_STATUS_PENDING;
            $tap_queue_model->restaurant_uuid = $model->restaurant_uuid;
            if (!$tap_queue_model->save()) {
                $transaction->rollBack();
                return self::message("error",$model->errors);
            }

            $model->tap_queue_id = $tap_queue_model->tap_queue_id;
            $model->save(false);

            $transaction->commit();

            return self::message("success","Files & data saved successfully");
        } catch (\Exception $e) {
            $transaction->rollBack();
            return self::message("error",$e->getMessage());
        }
    }

    /**
     *  Enable OnlinePayment on delivery
     */
    public function actionEnableOnlinePayment($id)
    {
        $model = $this->findModel($id);

        $transaction = Yii::$app->db->beginTransaction();

        $knet = $model->getRestaurantPaymentMethods()
            ->where(['payment_method_id' => 1])
            ->one();

        if (!$knet) {
            $knet = new RestaurantPaymentMethod();
            $knet->payment_method_id = 1; //K-net
            $knet->restaurant_uuid = $model->restaurant_uuid;

            if (!$knet->save()) {

                $transaction->rollBack();

                return self::message("error",$knet->getErrors());
            }
        }

        $creditCard = $model->getRestaurantPaymentMethods()->where(['payment_method_id' => 2])->one();

        if (!$creditCard) {
            $creditCard = new RestaurantPaymentMethod();
            $creditCard->payment_method_id = 2; //Credit Card
            $creditCard->restaurant_uuid = $model->restaurant_uuid;

            if (!$creditCard->save()) {

                $transaction->rollBack();

                return self::message("error",$creditCard->getErrors());
            }
        }

        $transaction->commit();
        return self::message("success","Online payments enabled successfully");
    }

    /**
     *  Disable OnlinePayment on delivery
     */
    public function actionDisableOnlinePayment($id)
    {
        $model = $this->findModel($id);

        $payments = $model->getRestaurantPaymentMethods()
            ->where(['<>', 'payment_method_id', 3])
            ->all();

        $transaction = Yii::$app->db->beginTransaction();

        foreach ($payments as $payment) {
            if (!$payment->delete()) {
                $transaction->rollBack();

                return self::message("error",'Error on disabling ' . $payment->payment_method_name);
            }
        }

        $transaction->commit();

        return self::message("success","Online payments disabled successfully");
    }

    /**
     *  Enable Cash on delivery
     */
    public function actionEnableCod($storeUuid)
    {
        $model = $this->findModel($storeUuid);

        $payment_method = $model->getRestaurantPaymentMethods()
            ->where(['payment_method_id' => 3])
            ->exists();

        if (!$payment_method) {
            return self::message("error",'Cash on delivery already enabled');
        }

        $payments_method = new RestaurantPaymentMethod();
        $payments_method->payment_method_id = 3; //Cash
        $payments_method->restaurant_uuid = $model->restaurant_uuid;

        if (!$payments_method->save()) {
            return self::message("error",$payments_method->getErrors());
        }

        return self::message("success","Cash on delivery enabled successfully");
    }

    /**
     *  Disable Cash on delivery
     */
    public function actionDisableCod($storeUuid)
    {

        $model = $this->findModel($storeUuid);

        $payment_method = $model->getRestaurantPaymentMethods()
            ->where(['payment_method_id' => 3])
            ->one();

        if (!$payment_method) {
            throw new NotFoundHttpException('The requested record does not exist.');
        }

        if (!$payment_method->delete()) {
            return self::message("error",$payment_method->getErrors());
        }

        return self::message("success","Cash on delivery disabled successfully");
    }

    /**
     * Finds the Restaurant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Restaurant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($store_uuid)
    {
        $model = Yii::$app->accountManager->getManagedAccount($store_uuid);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }

    /**
     * @param string $type
     * @param $message
     * @return array
     */
    public static function message($type = "success", $message) {
        return [
            "operation" => $type,
            "message" => $message
        ];
    }
}
