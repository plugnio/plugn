<?php

namespace agent\tests;

use common\fixtures\RestaurantPaymentMethodFixture;
use Yii;
use agent\models\Agent;
use agent\models\PaymentMethod;
use agent\models\Restaurant;
use agent\models\RestaurantPaymentMethod;
use Codeception\Util\HttpCode;
use common\fixtures\AgentAssignmentFixture;
use common\fixtures\AgentFixture;
use common\fixtures\AgentTokenFixture;
use common\fixtures\RestaurantFixture;


class StoreCest
{
    public $token;
    public $agent;
    public $store;

    public function _fixtures() {
        return [
            'agents' => AgentFixture::className(),
            'agent_assignments' => AgentAssignmentFixture::className(),
            'restaurants' => RestaurantFixture::className(),
            'restaurants' => RestaurantPaymentMethodFixture::className(),
            'agentToken' => AgentTokenFixture::className()
        ];
    }

    public function _before(FunctionalTester $I) {

        $this->payment_method = RestaurantPaymentMethod::find()->one();

        $this->store = $this->payment_method->restaurant;

        $this->agent = $this->store->getAgents()->one();//['agent_email_verification'=>1]

        $this->token = $this->agent->getAccessToken()->token_value;

        $I->amBearerAuthenticated($this->token);

        $I->haveHttpHeader('Store-Id', $this->store->restaurant_uuid);
    }

    public function _after(FunctionalTester $I) {

    }

    public function tryToDetail(FunctionalTester $I) {
        $I->wantTo('Validate store > detail api');
        $I->sendGET('v1/store', [
            'store_uuid' => $this->store->restaurant_uuid
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToGetStatus(FunctionalTester $I) {
        $I->wantTo('Validate store > status api');
        $I->sendGET('v1/store/status', [
            'store_uuid' => $this->store->restaurant_uuid
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdate(FunctionalTester $I) {
        $I->wantTo('Validate store > update api');
        $I->sendPOST('v1/store', [
            'store_uuid' => $this->store->restaurant_uuid,
            'email_notification' => 1,
            'mobile_country_code' => 91,
            'phone_number' => 8758702738,
            'name' => 'Lollipop',
            'name_ar' => 'Lollipop',
            'schedule_interval' => 1,
            'schedule_order' => 1,
            'restaurant_email' => 'lollipop@plugn.io',
            'tagline' => 'Lollipop',
            'tagline_ar' => 'Lollipop',
            'enable_gift_message' => 1,
            'currency' => 'KWD',
            'country_id' => 1
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdateDomain(FunctionalTester $I) {
        $I->wantTo('Validate store > update domain api');
        $I->sendPOST('v1/store/connect-domain', [
            'domain' => 'demo.com'
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToDisablePaymentMethod(FunctionalTester $I) {

        $payment_method = $this->store->getPaymentMethods()->one();

        $I->wantTo('Validate store > update payment method api');
        $I->sendPOST('v1/store/disable-payment-method/'. $this->store->restaurant_uuid
            . '/' . $payment_method->payment_method_id, [
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToEnablePaymentMethod(FunctionalTester $I) {
        $payment_method = $this->store->getPaymentMethods()->one();

        $I->wantTo('Validate store > enable payment method api');
        $I->sendPOST('v1/store/enable-payment-method/'. $this->store->restaurant_uuid
            . '/' . $payment_method->payment_method_id, [
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToViewPaymentMethod(FunctionalTester $I) {
        $payment_method = $this->store->getPaymentMethods()->one();

        $I->wantTo('Validate store > view payment method api');
        $I->sendGET('v1/store/view-payment-methods/'. $payment_method->payment_method_id, [
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToEnableOnlinePaymentMethod(FunctionalTester $I) {
        $I->wantTo('Validate store > enable online payment method api');
        $I->sendPOST('v1/store/enable-online-payment/'. $this->store->restaurant_uuid, [
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToDisableOnlinePaymentMethod(FunctionalTester $I) {
        $I->wantTo('Validate store > disable online payment method api');
        $I->sendPOST('v1/store/disable-online-payment/'. $this->store->restaurant_uuid, [
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToEnableCODPaymentMethod(FunctionalTester $I) {
        $I->wantTo('Validate store > enable cash on delivery payment method api');
        $I->sendPOST('v1/store/enable-cod/'. $this->store->restaurant_uuid, [
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToDisableCODPaymentMethod(FunctionalTester $I) {

        $pm = new RestaurantPaymentMethod();
        $pm->restaurant_uuid = $this->store->restaurant_uuid;
        $pm->payment_method_id = 3;
        $pm->save();

        $I->wantTo('Validate store > disable cash on delivery payment method api');
        $I->sendPOST('v1/store/disable-cod/'. $this->store->restaurant_uuid, [
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdateLayout(FunctionalTester $I)
    {
        $I->wantTo('Validate store > update layout api');
        $I->sendPOST('v1/store/update-layout', [
            'default_language' => 'en',
            'store_layout' => Restaurant::STORE_LAYOUT_GRID_FULLWIDTH,
            'phone_number_display' => 1
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            "operation" => "success"
        ]);
    }

    public function tryToUpdateAnalyticsIntegration(FunctionalTester $I) {
        $I->wantTo('Validate store > update analytics integration api');
        $I->sendPOST('v1/store/update-analytics-integration', [
            'google_analytics_id' => 'aasfauiwenjksvdb',
            'facebook_pixil_id' => 'aasfauiwenjksvdb',
            'snapchat_pixil_id' => 'asdasdfatert'
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            "operation" => "success"
        ]);
    }

    public function tryToUpdateDeliveryIntegration(FunctionalTester $I) {
        $I->wantTo('Validate store > update delivery integration api');
        $I->sendPOST('v1/store/update-delivery-integration', [
            'armada_api_key' => 'aasfauiwenjksvdb',
            'mashkor_branch_id' => 'aasfauiwenjksvdb'
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            "operation" => "success"
        ]);
    }

    public function tryToUpdateStatus(FunctionalTester $I) {
        $I->wantTo('Validate store > update store status api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPATCH('v1/store/update-status/' . $this->store->restaurant_uuid
            . '/' . Restaurant::RESTAURANT_STATUS_BUSY, [
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            "operation" => "success"
        ]);
    }

    public function tryToCreateTapAccount(FunctionalTester $I) {
        $identification_file_upload = Yii::$app->temporaryBucketResourceManager->save(
            null,
            'sample.jpg',
            [],
            codecept_data_dir() . 'files/sample.jpg',
            'image/jpg'
        );

        $identification_file_back_upload = Yii::$app->temporaryBucketResourceManager->save(
            null,
            'sample.jpg',
            [],
            codecept_data_dir() . 'files/sample.jpg',
            'image/jpg'
        );

        $commercial_license_file_upload = Yii::$app->temporaryBucketResourceManager->save(
            null,
            'sample.jpg',
            [],
            codecept_data_dir() . 'files/sample.jpg',
            'image/jpg'
        );

        $authorized_signature_file_upload = Yii::$app->temporaryBucketResourceManager->save(
            null,
            'sample.jpg',
            [],
            codecept_data_dir() . 'files/sample.jpg',
            'image/jpg'
        );

        $I->wantTo('Validate store > create tap account api');
        //$I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPOST('v1/store/create-tap-account/' . $this->store->restaurant_uuid, [
            'owner_first_name' => 'Chhaganbhai',
            'owner_last_name' => 'Maganbhai',
            'owner_email' => 'demo@plugn.io',
            'owner_number' => 123123123,
            'owner_phone_country_code' => 91,
            'company_name' => 'Plugn',
            'vendor_sector' => 'Fintech',
            'business_type' => 'Individual',
            'license_number' => 'asdaseq23412',
            'iban' => 'IBANA1231231231',
            'identification_file_front_side' => basename($identification_file_upload['ObjectURL']),
            'identification_file_back_side' => basename($identification_file_back_upload['ObjectURL']),
            'commercial_license_file' => basename($commercial_license_file_upload['ObjectURL']),
            'authorized_signature_file' => basename($authorized_signature_file_upload['ObjectURL']),
            'country_id' => 1
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            "operation" => "success"
        ]);
    }
}