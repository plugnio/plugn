<?php

namespace agent\modules\v1\controllers;

use agent\models\Currency;
use agent\models\PaymentMethod;
use agent\models\RestaurantTheme;
use common\models\RestaurantByCampaign;
use common\models\Setting;
use common\models\VendorCampaign;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use agent\models\Restaurant;
use agent\models\RestaurantPaymentMethod;
use common\models\PaymentGatewayQueue;
use yii\web\Response;


class StoreController extends BaseController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options', 'log-email-campaign'];

        return $behaviors;
    }

    /**
     * only owner will have access
     */
    public function beforeAction($action)
    {
        parent::beforeAction ($action);

        if(in_array($action->id, ['options', 'log-email-campaign', 'create'])) {
            return true;
        }

        if(!in_array($action->id, ['detail']) && !Yii::$app->accountManager->isOwner()) {

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
    public function actionDetail($store_uuid = null)
    {
        return $this->findModel($store_uuid);
    }

    /**
     * test tap connection
     * @return void
     */
    public function actionTestTap()
    {
        $store = $this->findModel();

        Yii::$app->tapPayments->setApiKeys(
            $store->live_api_key,
            $store->test_api_key
        );//$order->restaurant->is_sandbox

        $response = Yii::$app->tapPayments->createCharge(
            $store->currency->code,
            "Testing integration", // Description
            $store->name, //Statement Desc.
            time(), // Reference
            1,
            "test name",
            "test@localhost.com",
            "+91",
            "8758702738",
            0,
            Url::to(['order/callback'], true),
            Url::to(['order/payment-webhook'], true),
            "src_kw.knet",//src_all
            0,
            0,
            'Kuwait'
        );

        $responseContent = json_decode($response->content);

        if (isset($responseContent->errors)) {

            return [
                'operation' => 'error',
                "message" => "Error: " . $responseContent->errors[0]->code . " - " . $responseContent->errors[0]->description
            ];
        }

        return self::message("success", 'Integration working fine.');
    }

    /**
     * Return an overview of the store details
     * @param type $store_uuid
     */
    public function actionUpdate($store_uuid = null)
    {
        $store = $this->findModel($store_uuid);

        $store->setScenario(Restaurant::SCENARIO_UPDATE);

        $store->is_sandbox = Yii::$app->request->getBodyParam('is_sandbox');
        $store->country_id = Yii::$app->request->getBodyParam('country_id');
        $store->restaurant_email_notification = Yii::$app->request->getBodyParam('email_notification');
        $store->phone_number_country_code = (int) Yii::$app->request->getBodyParam('mobile_country_code');
        $store->phone_number = Yii::$app->request->getBodyParam('mobile');
        $store->name = Yii::$app->request->getBodyParam('name');
        $store->name_ar = Yii::$app->request->getBodyParam('name_ar');
        $store->schedule_interval = Yii::$app->request->getBodyParam('schedule_interval');
        $store->schedule_order = Yii::$app->request->getBodyParam('schedule_order');
        $store->restaurant_email = Yii::$app->request->getBodyParam('restaurant_email');
        $store->tagline = Yii::$app->request->getBodyParam('tagline');
        $store->tagline_ar = Yii::$app->request->getBodyParam('tagline_ar');
        $store->meta_description = Yii::$app->request->getBodyParam("meta_description");
        $store->meta_description_ar = Yii::$app->request->getBodyParam("meta_description_ar");
        $store->enable_gift_message = Yii::$app->request->getBodyParam('enable_gift_message');
        $store->accept_order_247 = Yii::$app->request->getBodyParam('accept_order_247');
        $store->is_public = Yii::$app->request->getBodyParam('is_public');

        $store->owner_first_name = Yii::$app->request->getBodyParam('owner_first_name');
        $store->owner_last_name = Yii::$app->request->getBodyParam('owner_last_name');
        $store->owner_email = Yii::$app->request->getBodyParam('owner_email');
        $store->owner_number = Yii::$app->request->getBodyParam('owner_number');
        $store->owner_phone_country_code = Yii::$app->request->getBodyParam('owner_phone_country_code');

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
     * add new store
     * @return array|string[]
     */
    public function actionCreate()
    {
        $store = new Restaurant();

        $totalStores = Yii::$app->user->identity
            ->getAccountsManaged()
            ->count();

        if ($totalStores > 4) {
            return self::message("error", Yii::t('app', "We limiting no of store per user to 5 for now!"));
        }

        if(YII_ENV == 'prod') {

            $token = Yii::$app->request->getBodyParam('token');

            $response = Yii::$app->reCaptcha->verify($token);

            if (!$response->data || !$response->data['success']) {
                return [
                    "operation" => "error",
                    "code" => 0,
                    "message" => Yii::t('candidate', "Invalid captcha validation")
                ];
            }
        }

        $utm_id = Yii::$app->request->getBodyParam('utm_uuid');

        $store->version = Yii::$app->params['storeVersion'];
        $store->setScenario(Restaurant::SCENARIO_CREATE_STORE_BY_AGENT);

        $store->is_sandbox = Yii::$app->request->getBodyParam('is_sandbox');
        $store->country_id = Yii::$app->request->getBodyParam('country_id');
        $store->restaurant_email_notification = Yii::$app->request->getBodyParam('email_notification');
        $store->phone_number_country_code = (int) Yii::$app->request->getBodyParam('mobile_country_code');
        $store->phone_number = Yii::$app->request->getBodyParam('mobile');
        $store->name = Yii::$app->request->getBodyParam('name');
        $store->name_ar = Yii::$app->request->getBodyParam('name_ar');
        $store->schedule_interval = Yii::$app->request->getBodyParam('schedule_interval');
        $store->schedule_order = Yii::$app->request->getBodyParam('schedule_order');
        $store->restaurant_email = Yii::$app->request->getBodyParam('restaurant_email');
        $store->tagline = Yii::$app->request->getBodyParam('tagline');
        $store->tagline_ar = Yii::$app->request->getBodyParam('tagline_ar');
        $store->meta_description = Yii::$app->request->getBodyParam("meta_description");
        $store->meta_description_ar = Yii::$app->request->getBodyParam("meta_description_ar");
        $store->enable_gift_message = Yii::$app->request->getBodyParam('enable_gift_message');
        $store->accept_order_247 = Yii::$app->request->getBodyParam('accept_order_247');
        $store->is_public = Yii::$app->request->getBodyParam('is_public');
        $store->restaurant_domain = Yii::$app->request->getBodyParam ('restaurant_domain');
        $store->owner_first_name = Yii::$app->request->getBodyParam('owner_first_name');
        $store->owner_last_name = Yii::$app->request->getBodyParam('owner_last_name');
        $store->owner_email = Yii::$app->request->getBodyParam('owner_email');
        $store->owner_number = Yii::$app->request->getBodyParam('owner_number');
        $store->owner_phone_country_code = Yii::$app->request->getBodyParam('owner_phone_country_code');

        /*if(strpos($store->restaurant_domain, ".plugn.store") == -1) {
            $store->restaurant_domain = $store->restaurant_domain . ".plugn.store";
        }

        if(strpos($store->restaurant_domain, "http") == -1) {
            $store->restaurant_domain = "https://" . $store->restaurant_domain;
        }*/

        if(!$store->restaurant_email) {
            $store->restaurant_email = Yii::$app->user->identity->agent_email;
        }

        if(!$store->name_ar) {
            $store->name_ar = $store->name;
        }

        $currencyCode = Yii::$app->request->getBodyParam('currency');

        $currency = Currency::findOne(['code' => $currencyCode]);

        if($currency) {
            $store->currency_id = $currency->currency_id;
        }

        if (!$store->save()) {
            return self::message("error",$store->getErrors());
        }

        if(!$utm_id) {
            $utm_id = Yii::$app->user->identity->utm_uuid;
        }

        if($utm_id) {
            $rbc = new RestaurantByCampaign();
            $rbc->restaurant_uuid = $store->restaurant_uuid;
            $rbc->utm_uuid = $utm_id;

            if (!$rbc->save()) {

                return [
                    "operation" => "error",
                    "message" => $rbc->errors
                ];
            }
        }

        //assign agent to store

        $response = $store->setupStore(Yii::$app->user->identity);

        if($response['operation'] != 'success') {
            return $response;
        }

        return [
            "operation" => "success",
            "restaurant_uuid" => $store->restaurant_uuid,
            "message" => Yii::t("agent",  'Store created successfully')
        ];
    }

    /**
     * Displays  Real time orders
     *
     * @return mixed
     */
    public function actionConnectDomain()
    {
        $domain = Yii::$app->request->getBodyParam('domain');
        $purchase = Yii::$app->request->getBodyParam('purchase');

        $store = Yii::$app->accountManager->getManagedAccount();

        if ($store->restaurant_domain == $domain) {
            return self::message("error",'New domain can not be same as old domain');
        }

        $old_domain = $store->restaurant_domain;

        $store->setScenario(Restaurant::SCENARIO_CONNECT_DOMAIN);

        $store->restaurant_domain = rtrim($domain, '/');

        //for purchase request not changing store domain until domain available

        if (!$purchase && !$store->save()) {
            return self::message("error",$store->getErrors());
        }

        //todo: response validation

        if($purchase) {//&& strpos($domain, '.plugn.') == -1
 
            if(YII_ENV == 'prod') {

                Yii::$app->eventManager->track('Domain Requests', [
                        "domain" =>  $domain
                    ],
                    null,
                    $store->restaurant_uuid);
            }

 
            return $store->notifyDomainRequest($old_domain);
        }

        return $store->notifyDomainUpdated($old_domain);
    }

    /**
     * Disable payment method
     * @return mixed
     */
    public function actionDisablePaymentMethod($id = null, $paymentMethodId)
    {
        $model = $this->findModel($id);

        RestaurantPaymentMethod::deleteAll([
            'restaurant_uuid' => $model->restaurant_uuid,
            'payment_method_id' => $paymentMethodId
        ]);

        return self::message("success",'Payment method disabled successfully');
    }

    /**
     * Update front store settings
     * @return mixed
     */
    public function actionUpdateStoreSettings()
    {
        $model = $this->findModel();

        $item_discount_label = Yii::$app->request->getBodyParam('item_discount_label');

        Setting::setConfig($model->restaurant_uuid, 'Store', 'item_discount_label', $item_discount_label);

        return self::message("success",'Mail settings updated successfully');
    }

    /**
     * Update mail settings
     * @return mixed
     */
    public function actionUpdateEmailSettings()
    {
        $model = $this->findModel();

        $host = Yii::$app->request->getBodyParam('host');
        $username = Yii::$app->request->getBodyParam('username');
        $password = Yii::$app->request->getBodyParam('password');
        $port = Yii::$app->request->getBodyParam('port');
        $encryption = Yii::$app->request->getBodyParam('encryption');

        Setting::setConfig($model->restaurant_uuid, 'mail', 'host', $host);
        Setting::setConfig($model->restaurant_uuid,'mail', 'username', $username);
        Setting::setConfig($model->restaurant_uuid,'mail', 'password', $password);
        Setting::setConfig($model->restaurant_uuid,'mail', 'port', $port);
        Setting::setConfig($model->restaurant_uuid,'mail', 'encryption', $encryption);

        return self::message("success",'Mail settings updated successfully');
    }

    /**
     * return settings for specific module
     * @param $code
     * @return array|\yii\db\ActiveRecord[]
     * @throws NotFoundHttpException
     */
    public function actionSettings($code)
    {
        $model = $this->findModel();

        $settings = $model->getSettings()
            ->andWhere(['code' => $code])
            ->all();

        return ArrayHelper::map($settings, 'key', 'value');
    }

    /**
     * upgrade store
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpgrade()
    {
        $store = $this->findModel();

        if(str_contains($store->restaurant_domain, ".plugn.site"))
        {
            return self::message("error", "Already using new design!");
        }
        else if(str_contains($store->restaurant_domain, ".plugn.store"))
        {
            $store->restaurant_domain = str_replace(".plugn.store", ".plugn.site", $store->restaurant_domain);
            $store->version = Yii::$app->params['storeVersion'];

            if(!$store->save()) {
                return self::message("error",$store->errors);
            }

            return self::message("success","Store migrated to ". $store->restaurant_domain);
        }

        //if custom domain

        if(!$store->site_id) {
            return [
                'operation' => 'error',
                'message' => "Site not published yet, can't update unpublished site!"
            ];
        }

        //getting conflict on this
        //$response = Yii::$app->githubComponent->mergeABranch('Merge branch master into ' . $store->store_branch_name, $store->store_branch_name,  'master');

        if($store->site_id)
            $response = Yii::$app->netlifyComponent->upgradeSite($store);
        else
            $response = Yii::$app->netlifyComponent->createSite($store);

        if ($response->isOk)
        {
            //$store->sitemap_require_update = 1;
            $store->version = Yii::$app->params['storeVersion'];

            if(!$store->site_id)
            {
                $store->site_id = $response->data['site_id'];
            }

            $store->save(false);

            //create new store? and delete current one

            return self::message("success","Store will be updated in 2-5 min!");
        }

        Yii::error('[Error while upgrading site]' . isset($response->data['message'])? json_encode($response->data['message']): json_encode($response->data) . ' RestaurantUuid: '. $store->restaurant_uuid, __METHOD__);

        return [
            'operation' => 'error',
            'message' => $response->data['message']
        ];
    }

    /**
     * Enable payment method
     * @return mixed
     */
    public function actionEnablePaymentMethod($id = null, $paymentMethodId)
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
    public function actionViewPaymentMethods($id = null)
    {
        $model = $this->findModel($id);

        $query = $model->getPaymentMethods();

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * View shipping settings page
     * @return mixed
     */
    public function actionViewShippingMethods()
    {
        $model = $this->findModel();

        $query = $model->getShippingMethods();

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Update bank account in tap account
     * @return array
     */
    public function actionUpdateBankAccount()
    {
        $model = $this->findModel();

        $model->setScenario(Restaurant::SCENARIO_UPDATE_BANK);

        $model->iban = Yii::$app->request->getBodyParam('iban');

        if(!$model->save()) {
            return [
                'operation' => 'success',
                'message' => $model->errors
            ];
        }

        Yii::$app->tapPayments->setApiKeys(
            $model->live_api_key,
            $model->test_api_key,
            false// $this->is_sandbox
        );

        $result = Yii::$app->tapPayments->updateBankAccount($model->wallet_id, $model->iban);

        //print_r($result->content);

        //Yii::debug($result);

        return [
            'operation' => 'error',
            'message' => "Bank detail updated successfully"
        ];
    }

    /**
     * Create tap account
     * @param type $id
     * @return array
     */
    public function actionCreateTapAccount($id = null)
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

        //file urls

        $identification_file_front_side = Yii::$app->request->getBodyParam('identification_file_front_side');
        $identification_file_back_side = Yii::$app->request->getBodyParam('identification_file_back_side');
        $commercial_license_file = Yii::$app->request->getBodyParam('commercial_license_file');
        $authorized_signature_file = Yii::$app->request->getBodyParam('authorized_signature_file');
        $iban_certificate_file = Yii::$app->request->getBodyParam('iban_certificate_file');

        if ($model->country && $model->country->iso != 'KW') {
            $model->business_type = 'corp';
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {

            if (!$model->save()) {
                $transaction->rollBack();
                return self::message("error",$model->errors);
            }

            /*-------- uploading documents-------*/

            //if provided + changed

            if (
                $identification_file_front_side &&
                $model->identification_file_front_side != $identification_file_front_side &&
                !$model->uploadFileFromAwsToCloudinary(
                    $identification_file_front_side,
                    'identification_file_front_side'
                )
            ) {
                $transaction->rollBack();
                return self::message("error",$model->errors);
            }

            if (
                $identification_file_back_side &&
                $model->identification_file_back_side != $identification_file_back_side &&
                !$model->uploadFileFromAwsToCloudinary(
                    $identification_file_back_side,
                    'identification_file_back_side'
                )
            ) {
                $transaction->rollBack();
                return self::message("error",$model->errors);
            }

            if (
                $commercial_license_file &&
                $model->commercial_license_file != $commercial_license_file &&
                !$model->uploadFileFromAwsToCloudinary(
                    $commercial_license_file,
                    'commercial_license_file'
                )
            ) {
                $transaction->rollBack();
                return self::message("error",$model->errors);
            }

            if (
                $authorized_signature_file &&
                $model->authorized_signature_file != $authorized_signature_file &&
                !$model->uploadFileFromAwsToCloudinary(
                    $authorized_signature_file,
                    'authorized_signature_file'
                )
            ) {
                $transaction->rollBack();
                return self::message("error",$model->errors);
            }

            if (
                $iban_certificate_file &&
                $model->iban_certificate_file != $iban_certificate_file &&
                !$model->uploadFileFromAwsToCloudinary(
                    $iban_certificate_file,
                    'iban_certificate_file'
                )
            ) {
                $transaction->rollBack();
                return self::message("error",$model->errors);
            }

            /*-------- uploading documents-------*/

            $payment_gateway_queue = new PaymentGatewayQueue;
            $payment_gateway_queue->queue_status = PaymentGatewayQueue::QUEUE_STATUS_PENDING;
            $payment_gateway_queue->restaurant_uuid = $model->restaurant_uuid;
            $payment_gateway_queue->payment_gateway =  'tap';

            if (!$payment_gateway_queue->save()) {
                $transaction->rollBack();
                return self::message("error",$model->errors);
            }

            $model->payment_gateway_queue_id = $payment_gateway_queue->payment_gateway_queue_id;
            $model->save(false);

            $transaction->commit();

            return self::message("success", "Your request has been successfully submitted");
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
    public function actionEnableOnlinePayment($id = null)
    {
        $model = $this->findModel($id);

        $transaction = Yii::$app->db->beginTransaction();

        $knet = $model->getRestaurantPaymentMethods()
            ->andWhere(['payment_method_id' => 1])
            ->one();

        if (!$knet) {

            $payment_method = PaymentMethod::find()
                ->andWhere(['payment_method_code' => PaymentMethod::CODE_KNET])
                ->one();

            if(!$payment_method) {
                return self::message("error", Yii::t('agent', 'Invalid payment method'));
            }

            $knet = new RestaurantPaymentMethod();
            $knet->payment_method_id = $payment_method->payment_method_id; //K-net
            $knet->restaurant_uuid = $model->restaurant_uuid;

            if (!$knet->save()) {

                $transaction->rollBack();

                return self::message("error",$knet->getErrors());
            }
        }

        $creditCard = $model->getRestaurantPaymentMethods()->where(['payment_method_id' => 2])->one();

        if (!$creditCard) {

            $payment_method = PaymentMethod::find()
                ->andWhere(['payment_method_code' => PaymentMethod::CODE_CREDIT_CARD])
                ->one();

            if(!$payment_method) {
                return self::message("error", Yii::t('agent', 'Invalid payment method'));
            }

            $creditCard = new RestaurantPaymentMethod();
            $creditCard->payment_method_id = $payment_method->payment_method_id; //Credit Card
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
    public function actionDisableOnlinePayment($id = null)
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
    public function actionEnableCod($id = null)
    {
        $model = $this->findModel($id);

        $payment_method = $model->getRestaurantPaymentMethods()
            ->andWhere(['payment_method_id' => 3])
            ->exists();

        if ($payment_method) {
            return self::message("error",'Cash on delivery already enabled');
        }

        $codPaymentMethod = PaymentMethod::find()
            ->andWhere(['payment_method_code' => PaymentMethod::CODE_CASH])
            ->one();

        if(!$codPaymentMethod) {
            return self::message("error", Yii::t('agent', 'Invalid payment method'));
        }

        $payments_method = new RestaurantPaymentMethod();
        $payments_method->payment_method_id = $codPaymentMethod->payment_method_id; //Cash
        $payments_method->restaurant_uuid = $model->restaurant_uuid;

        if (!$payments_method->save()) {
            return self::message("error",$payments_method->getErrors());
        }

        return self::message("success","Cash on delivery enabled successfully");
    }

    /**
     *  Disable Cash on delivery
     */
    public function actionDisableCod($id = null)
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
     *  Enable Moyasar
     */
    public function actionEnableMoyasar($id = null)
    {
        $model = $this->findModel($id);

        $payment_method = $model->getRestaurantPaymentMethods()
            ->joinWith('paymentMethod')
            ->andWhere(['payment_method_code' => PaymentMethod::CODE_MOYASAR])
            ->exists();

        if ($payment_method) {
            return self::message("error",'Moyasar already enabled');
        }

        $codPaymentMethod = PaymentMethod::find()
            ->andWhere(['payment_method_code' => PaymentMethod::CODE_MOYASAR])
            ->one();

        if(!$codPaymentMethod) {
            return self::message("error", Yii::t('agent', 'Invalid payment method'));
        }

        $payments_method = new RestaurantPaymentMethod();
        $payments_method->payment_method_id = $codPaymentMethod->payment_method_id;
        $payments_method->restaurant_uuid = $model->restaurant_uuid;

        if (!$payments_method->save()) {
            return self::message("error",$payments_method->getErrors());
        }

        return self::message("success","Moyasar enabled successfully");
    }

    /**
     *  Disable Moyasar
     */
    public function actionDisableMoyasar($id = null)
    {
        $model = $this->findModel($id);

        $payment_method = $model->getRestaurantPaymentMethods()
            ->joinWith('paymentMethod')
            ->andWhere(['payment_method_code' => PaymentMethod::CODE_MOYASAR])
            ->one();

        if (!$payment_method) {
            throw new BadRequestHttpException('The requested record does not exist.');
        }

        if (!$payment_method->delete()) {
            return self::message("error", $payment_method->getErrors());
        }

        Setting::deleteAll([
                'restaurant_uuid' => $model->restaurant_uuid,
                'code' => PaymentMethod::CODE_MOYASAR
            ]);

        return self::message("success", "Moyasar disabled successfully");
    }

    /**
     * enable stripe
     * @param $id
     * @return array
     */
    public function actionEnableStripe($id = null)
    {
        $model = $this->findModel($id);

        $payment_method = $model->getRestaurantPaymentMethods()
            ->joinWith('paymentMethod')
            ->andWhere(['payment_method_code' => PaymentMethod::CODE_STRIPE])
            ->exists();

        if ($payment_method) {
            return self::message("error",'Stripe already enabled');
        }

        $codPaymentMethod = PaymentMethod::find()
            ->andWhere(['payment_method_code' => PaymentMethod::CODE_STRIPE])
            ->one();

        if(!$codPaymentMethod) {
            return self::message("error", Yii::t('agent', 'Invalid payment method'));
        }

        $payments_method = new RestaurantPaymentMethod();
        $payments_method->payment_method_id = $codPaymentMethod->payment_method_id;
        $payments_method->restaurant_uuid = $model->restaurant_uuid;

        if (!$payments_method->save()) {
            return self::message("error", $payments_method->getErrors());
        }

        return self::message("success","Stripe enabled successfully");
    }

    /**
     *  Disable Stripe
     */
    public function actionDisableStripe($id = null)
    {
        $model = $this->findModel($id);

        $payment_method = $model->getRestaurantPaymentMethods()
            ->joinWith('paymentMethod')
            ->andWhere(['payment_method_code' => PaymentMethod::CODE_STRIPE])
            ->one();

        if (!$payment_method) {
            throw new BadRequestHttpException('The requested record does not exist.');
        }

        if (!$payment_method->delete()) {
            return self::message("error", $payment_method->getErrors());
        }

        Setting::deleteAll([
            'restaurant_uuid' => $model->restaurant_uuid,
            'code' => PaymentMethod::CODE_STRIPE
        ]);

        return self::message("success", "Stripe disabled successfully");
    }

    /**
     *  Enable Free Checkout
     */
    public function actionEnableFreeCheckout($id = null)
    {
        $model = $this->findModel($id);

        $freePaymentMethod = PaymentMethod::find()
            ->andWhere(['payment_method_code' => PaymentMethod::CODE_FREE_CHECKOUT])
            ->one();

        if(!$freePaymentMethod) {
            return self::message("error", Yii::t('agent', 'Invalid payment method'));
        }

        $restaurantPaymentMethod = RestaurantPaymentMethod::find()
            ->andWhere(['payment_method_id' => $freePaymentMethod->payment_method_id])
            ->andWhere(['restaurant_uuid' => $id])
            ->one();

        if (!$restaurantPaymentMethod) {
            $restaurantPaymentMethod = new RestaurantPaymentMethod();
            $restaurantPaymentMethod->payment_method_id = $freePaymentMethod->payment_method_id; //Free checkout
            $restaurantPaymentMethod->restaurant_uuid = $model->restaurant_uuid;
        }

        $restaurantPaymentMethod->status = RestaurantPaymentMethod::STATUS_ACTIVE;

        if (!$restaurantPaymentMethod->save()) {
            return self::message("error",$restaurantPaymentMethod->getErrors());
        }

        return self::message("success","Free checkout enabled successfully");
    }

    /**
     *  Disable Free Checkout
     */
    public function actionDisableFreeCheckout($id = null)
    {
        $model = $this->findModel($id);

        $payment_method = $model->getPaymentMethods()
            ->andWhere(['payment_method_code' => PaymentMethod::CODE_FREE_CHECKOUT])
            ->one();

        if (!$payment_method) {
            throw new BadRequestHttpException('The requested record does not exist.');
        }

        $restaurantPaymentMethod = $model->getRestaurantPaymentMethods()
            ->andWhere(['payment_method_id' => $payment_method->payment_method_id])
            ->one();

        if (!$restaurantPaymentMethod) {
            throw new BadRequestHttpException('The requested record does not exist.');
        }

        $restaurantPaymentMethod->status = RestaurantPaymentMethod::STATUS_INACTIVE;

        if (!$restaurantPaymentMethod->save(false)) {
            return self::message("error", $restaurantPaymentMethod->getErrors());
        }

        return self::message("success","Free checkout disabled successfully");
    }

    /**
     * Updates store layout
     */
    public function actionUpdateLayout() {

        $logo = Yii::$app->request->getBodyParam('logo');
        $thumbnail_image = Yii::$app->request->getBodyParam('thumbnail_image');

        $model = Yii::$app->accountManager->getManagedAccount();

        $model->setScenario(Restaurant::SCENARIO_UPDATE_LAYOUT);

        //restaurant

        $model->custom_css = Yii::$app->request->getBodyParam('custom_css');

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

        if($model->logo != $logo) {
            $model->uploadLogo($logo);
        }

        if($model->thumbnail_image != $thumbnail_image) {
            $model->uploadThumbnailImage($thumbnail_image);
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
            $restaurantTheme = new RestaurantTheme();
            $restaurantTheme->restaurant_uuid = $model->restaurant_uuid;
        }

        $themeData = Yii::$app->request->getBodyParam('restaurantTheme');

        if($themeData) {
            $restaurantTheme->primary = $themeData['primary'];
            $restaurantTheme->secondary = $themeData['secondary'];
            $restaurantTheme->tertiary = $themeData['tertiary'];
            $restaurantTheme->light = $themeData['light'];
            $restaurantTheme->medium = $themeData['medium'];
            $restaurantTheme->dark = $themeData['dark'];
        }

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
     * process payment gateway queue
     * @return void
     */
    public function actionProcessGatewayQueue($id = null)
    {
        $model = $this->findModel($id);

        if(
            !$model->paymentGatewayQueue ||
            $model->paymentGatewayQueue->queue_status == PaymentGatewayQueue::QUEUE_STATUS_COMPLETE
        )
        {
            return self::message("error", "Payment gateway already active");
        }

        return $model->paymentGatewayQueue->processQueue();
    }

    /**
     * remove payment gateway queue
     * @return void
     */
    public function actionRemoveGatewayQueue($id = null)
    {
        $store = $this->findModel($id);

        PaymentGatewayQueue::deleteAll(['restaurant_uuid' => $id]);

        $store->payment_gateway_queue_id = null; 
        $store->is_tap_enable = false;

        if(!$store->save(false)) {
            return self::message("error", $store->errors);
        }

        return self::message("success", "Payment gateway queue removed");
    }

    /**
     * update delivery API keys
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionUpdateDeliveryIntegration($id = null) {

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
    public function actionUpdateAnalyticsIntegration($id = null) {

        $model = $this->findModel($id);

        $model->setScenario (Restaurant::SCENARIO_UPDATE_ANALYTICS);

        $model->google_analytics_id = Yii::$app->request->getBodyParam ('google_analytics_id');
        $model->facebook_pixil_id = Yii::$app->request->getBodyParam ('facebook_pixil_id');
        $model->snapchat_pixil_id = Yii::$app->request->getBodyParam ('snapchat_pixil_id');
        $model->google_tag_id = Yii::$app->request->getBodyParam ('google_tag_id');
        $model->google_tag_manager_id = Yii::$app->request->getBodyParam ('google_tag_manager_id');
        $model->tiktok_pixel_id= Yii::$app->request->getBodyParam ('tiktok_pixel_id');

        $model->sitemap_require_update = 1;

        if (!$model->save()) {
            return [
                'operation' => 'error',
                'message' => $model->getErrors ()
            ];
        }

        //update site

        if($model->site_id)
            Yii::$app->netlifyComponent->upgradeSite($model);

        return self::message("success","Analytics integration updated successfully");
    }

    /**
     * @param $store_uuid
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionStatus($store_uuid = null) {

        $model = $this->findModel($store_uuid);

        return [
            'itemQuantity'=>count($model->items),
            'payment'=>count($model->paymentMethods),
            'shipping'=>count($model->areaDeliveryZones)
        ];
    }

    /**
     * @param $store_uuid
     * @param $status
     * @return array
     * @throws NotFoundHttpException
     * update store status
     */
    public function actionUpdateStoreStatus($id = null, $status) {

        $model = $this->findModel($id);

        if ($status == Restaurant::RESTAURANT_STATUS_OPEN) {
            $model->markAsOpen();
        } else if ($status == Restaurant::RESTAURANT_STATUS_BUSY) {
            $model->markAsBusy();
        }
        else
        {
            $model->setScenario(Restaurant::SCENARIO_UPDATE_STATUS);
            $model->restaurant_status = Restaurant::RESTAURANT_STATUS_CLOSED;
            $model->save(false);
        }

        return self::message("success","Status changed successfully");
    }

    /**
     * delete store
     * @return array
     */
    public function actionDelete()
    {
        $model = $this->findModel();

        return $model->deleteSite ();
    }

    public function actionDeactivate()
    {
        $model = $this->findModel();

        $model->setScenario(Restaurant::SCENARIO_UPDATE_STATUS);

        $model->restaurant_status = Restaurant::RESTAURANT_STATUS_CLOSED;

        if(!$model->save()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        return self::message("success", "Status changed successfully");
    }

    /**
     * log to invitation when it was seen
     * @param $id
     * @throws \yii\web\ServerErrorHttpException
     */
    public function actionLogEmailCampaign($id)
    {
        $model = VendorCampaign::find()
            ->andWhere (['campaign_uuid' => $id])
            ->one();

        if($model) {
            $model->no_of_email_opened += 1;
            $model->save(false);
        }

        $response = Yii::$app->getResponse();
        $response->headers->set('Content-Type', 'image/png');
        $response->format = Response::FORMAT_RAW;

        $imgFullPath = Url::to('@web/images/NFFFFFF-0.png', true);

        if ( !is_resource($response->stream = fopen($imgFullPath, 'r')) ) {
            throw new \yii\web\ServerErrorHttpException('file access failed: permission deny');
        }

        if (YII_ENV == 'prod') {
            Yii::$app->eventManager->track('Email Opened', $model->attributes);
        }

        return $response->send();
    }

    /**
     * Finds the Restaurant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Restaurant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($store_uuid =  null)
    {
        $model = Yii::$app->accountManager->getManagedAccount($store_uuid, false);

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
            "message" => is_string ($message)? Yii::t('agent', $message): $message
        ];
    }
}
