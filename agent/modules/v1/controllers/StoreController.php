<?php

namespace agent\modules\v1\controllers;

use agent\models\Currency;
use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
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
     * only owner will have access
     */
    public function beforeAction($action)
    {
        parent::beforeAction ($action);

        if($action->id == 'options') {
            return true;
        }

        if(!Yii::$app->accountManager->isOwner() && !in_array ($action->id, ['detail'])) {
            throw new \yii\web\BadRequestHttpException(
                Yii::t('agent', 'You are not allowed to manage store. Please contact with store owner')
            );

            return false;
        }

        //should have access to store

        Yii::$app->accountManager->getManagedAccount();

        return true;
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

        $store->setScenario(Restaurant::SCENARIO_UPDATE);

        $store->country_id = Yii::$app->request->getBodyParam('country_id');
        $store->restaurant_email_notification = Yii::$app->request->getBodyParam('email_notification');
        $store->phone_number_country_code = (int) Yii::$app->request->getBodyParam('mobile_country_code');
        $store->phone_number = Yii::$app->request->getBodyParam('mobile');
        $store->name = Yii::$app->request->getBodyParam('name');
        $store->name_ar = Yii::$app->request->getBodyParam('name_ar');
        $store->schedule_interval = Yii::$app->request->getBodyParam('schedule_interval');
        $store->schedule_order = Yii::$app->request->getBodyParam('schedule_order');
        $store->restaurant_email = Yii::$app->request->getBodyParam('store_email');
        $store->tagline = Yii::$app->request->getBodyParam('tagline');
        $store->tagline_ar = Yii::$app->request->getBodyParam('tagline_ar');
        $store->enable_gift_message = Yii::$app->request->getBodyParam('enable_gift_message');

        $currencyCode = Yii::$app->request->getBodyParam('currency');

        $currency = Currency::findOne(['code' => $currencyCode]);

        if($currency) {
            $store->currency_id = $currency->currency_id;
        }

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
        $model->owner_phone_country_code = Yii::$app->request->getBodyParam('owner_phone_country_code');
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
                $model->identification_file_front_side &&
                !$model->uploadFileToCloudinary(
                    $model->identification_file_front_side,
                    'identification_file_front_side'
                )
            ) {
                $transaction->rollBack();
                return self::message("error",$model->errors);
            }

            if (
                $model->identification_file_back_side &&
                !$model->uploadFileToCloudinary(
                    $model->identification_file_back_side,
                    'identification_file_back_side'
                )
            ) {
                $transaction->rollBack();
                return self::message("error",$model->errors);
            }

            if (
                $model->commercial_license_file &&
                !$model->uploadFileToCloudinary(
                    $model->commercial_license_file,
                    'commercial_license_file'
                )
            ) {
                $transaction->rollBack();
                return self::message("error",$model->errors);
            }

            if (
                $model->authorized_signature_file &&
                !$model->uploadFileToCloudinary(
                    $model->authorized_signature_file,
                    'authorized_signature_file'
                )
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
        }
        catch (\Exception $e)
        {
            $transaction->rollBack();
            return self::message("error",$e->getMessage());
        }
    }

    /**
     *  Enable OnlinePayment
     */
    public function actionEnableOnlinePayment($id)
    {
        $model = $this->findModel($id);

        $transaction = Yii::$app->db->beginTransaction();

        $knet = $model->getRestaurantPaymentMethods()
            ->andWhere(['payment_method_id' => 1])
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
     *  Disable OnlinePayment
     */
    public function actionDisableOnlinePayment($id)
    {
        $model = $this->findModel($id);

        $payments = $model->getRestaurantPaymentMethods()
            ->andWhere(['<>', 'payment_method_id', 3])
            ->all();

        $transaction = Yii::$app->db->beginTransaction();

        foreach ($payments as $payment) {
            if (!$payment->delete()) {
                $transaction->rollBack();

                return self::message("error",'Error on disabling payment method');
            }
        }

        $transaction->commit();

        return self::message("success","Online payments disabled successfully");
    }

    /**
     *  Enable Cash on delivery
     */
    public function actionEnableCod($id)
    {
        $model = $this->findModel($id);

        $payment_method = $model->getRestaurantPaymentMethods()
            ->andWhere(['payment_method_id' => 3])
            ->exists();

        if ($payment_method) {
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
    public function actionDisableCod($id)
    {
        $model = $this->findModel($id);

        $payment_method = $model->getRestaurantPaymentMethods()
            ->andWhere(['payment_method_id' => 3])
            ->one();

        if (!$payment_method) {
            throw new BadRequestHttpException('The requested record does not exist.');
        }

        if (!$payment_method->delete()) {
            return self::message("error", $payment_method->getErrors());
        }

        return self::message("success","Cash on delivery disabled successfully");
    }

    /**
     * Updates store layout
     */
    public function actionUpdateLayout() {

        $model = Yii::$app->accountManager->getManagedAccount();

        $model->setScenario(Restaurant::SCENARIO_UPDATE_LAYOUT);

        //restaurant

        $model->default_language = Yii::$app->request->getBodyParam('default_language');
        $model->store_layout = Yii::$app->request->getBodyParam('store_layout');
        $model->phone_number_display = Yii::$app->request->getBodyParam('phone_number_display');

        if(!$model->validate()) {
            return [
                'operation' => 'error',
                'message' => $model->getErrors()
            ];
        }

        $transaction = Yii::$app->db->beginTransaction ();

        if($model->logo != Yii::$app->request->getBodyParam('logo')) {
            $model->uploadLogo(Yii::$app->request->getBodyParam('logo'));
        }

        if($model->thumbnail_image != Yii::$app->request->getBodyParam('thumbnail_image')) {
            $model->uploadThumbnailImage(Yii::$app->request->getBodyParam('thumbnail_image'));
        }

        if(!$model->save()) {

            $transaction->rollBack ();

            return [
                'operation' => 'error',
                'message' => $model->getErrors()
            ];
        }

        //theme 

        $restaurantTheme = $model->restaurantTheme;

        if(!$restaurantTheme) {
            $restaurantTheme = new RestaurantTheme;
            $restaurantTheme->restaurant_uuid = $model->restaurant_uuid;
        }

        $themeData = Yii::$app->request->getBodyParam('restaurantTheme');

        $restaurantTheme->primary = $themeData['primary'];
        $restaurantTheme->secondary = $themeData['secondary'];
        $restaurantTheme->tertiary = $themeData['tertiary'];
        $restaurantTheme->light = $themeData['light'];
        $restaurantTheme->medium = $themeData['medium'];
        $restaurantTheme->dark = $themeData['dark'];

        if(!$restaurantTheme->save()) {
            $transaction->rollBack ();

            return [
                'operation' => 'error',
                'message' => $restaurantTheme->getErrors()
            ];
        }

        $transaction->commit ();

        return self::message("success","Layout updated successfully");
    }

    /**
     * update delivery API keys
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionUpdateDeliveryIntegration($id) {

        $model = $this->findModel($id);

        $model->setScenario (Restaurant::SCENARIO_UPDATE_DELIVERY);

        $model->armada_api_key = Yii::$app->request->getBodyParam ('armada_api_key');
        $model->mashkor_branch_id = Yii::$app->request->getBodyParam ('mashkor_branch_id');

        if (!$model->save()) {
            return [
                'operation' => 'error',
                'message' => $model->getErrors ()
            ];
        }

        return self::message("success","Delivery integration updated successfully");
    }

    /**
     * Updates an existing Analytics integration.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateAnalyticsIntegration($id) {

        $model = $this->findModel($id);

        $model->setScenario (Restaurant::SCENARIO_UPDATE_ANALYTICS);

        $model->google_analytics_id = Yii::$app->request->getBodyParam ('google_analytics_id');
        $model->facebook_pixil_id = Yii::$app->request->getBodyParam ('facebook_pixil_id');
        $model->snapchat_pixil_id = Yii::$app->request->getBodyParam ('snapchat_pixil_id');

        $model->sitemap_require_update = 1;

        if (!$model->save()) {
            return [
                'operation' => 'error',
                'message' => $model->getErrors ()
            ];
        }

        return self::message("success","Analytics integration updated successfully");
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
            "message" => Yii::t('agent', $message)
        ];
    }
}
