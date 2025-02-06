<?php

namespace agent\tests;

use agent\tests\FunctionalTester;
use common\fixtures\AgentAssignmentFixture;
use common\fixtures\CountryFixture;
use common\fixtures\CurrencyFixture;
use yii;
use agent\models\Agent;
use common\fixtures\AgentTokenFixture;
use common\fixtures\AgentFixture;
use Codeception\Util\HttpCode;


class AuthCest {

    public $token;
    public $agent;

    public function _fixtures() {
        return [
            'country' => CountryFixture::class,
            'currencies' => CurrencyFixture::class,
            'agents' => AgentFixture::class,
            'agent_assignments' => AgentAssignmentFixture::class,
            'agentToken' => AgentTokenFixture::class
        ];
    }

    public function _before(FunctionalTester $I) {

        $this->agent = Agent::find()->one();//['agent_email_verification'=>1]

        $this->token = $this->agent->getAccessToken()->token_value;

        $I->amBearerAuthenticated($this->token);

        $this->store = $this->agent->getAccountsManaged()->one();

        $I->haveHttpHeader('Store-Id', $this->store->restaurant_uuid);

    }

    public function _after(FunctionalTester $I) {

    }

    /**
     * Login
     * @param FunctionalTester $I
     */
    public function tryToLogin(FunctionalTester $I) {
        $agent = Agent::find()->one();//['agent_email_verification'=>1]

        $I->wantTo('Validate auth > login api');
        $I->amHttpAuthenticated($agent->agent_email, '12345');
        $I->sendGET('v1/auth/login');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            'id' => $agent->agent_id
        ]);
    }

    /**
     * Try to update password
     * @param FunctionalTester $I
     */
    public function tryToUpdatePassword(FunctionalTester $I) {

        $agent =  Agent::findOne(['agent_id'=>$this->agent->agent_id]);
        $agent->setScenario('changePassword');
        $agent->agent_password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
        $agent->save(false);

        $I->wantTo('Validate auth > update-password api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPATCH('v1/auth/update-password', [
            'newPassword' => 'demo1admin',
            'token' => $agent->agent_password_reset_token
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            "operation" => "success"
        ]);
    }

    /**
     * Try to validate email
     * @param FunctionalTester $I
     *
    public function tryToValidateEmail(FunctionalTester $I) {
        $I->wantTo('Validate auth > email-check api');
        $I->sendPOST('v1/auth/email-check', [
            'email' => $this->agent->agent_email
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            "agent_id"=>$this->agent->agent_id
        ]);
    }*/

    /**
     * Try to register
     * @param FunctionalTester $I
     */
    public function tryToRegister(FunctionalTester $I) {
        $I->wantTo('Validate auth > register api');
        $I->sendPOST('v1/auth/signup', [
            'currency' => 1,
            'name' => 'demo com',
            'email' => 'demo@demo.com',
            'password' => 'demo1admin',
            'owner_number' => 12345678,
            'owner_phone_country_code' => 91,
            'restaurant_name' => 'demo',
            'account_type' => 'individual',
            'restaurant_domain' => 'demo-store',
            'country_id' => 1,
            'annual_revenue' => '10,000 KWD'
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            'operation' => 'success'
        ]);
    }

    public function tryToSignupStep1(FunctionalTester $I) {
        $I->wantTo('Validate auth > register api');
        $I->sendPOST('v1/auth/signup-step-one', [
            'currency' => 1,
            'name' => 'demo com',
            'email' => 'demo@demo.com',
            'password' => 'demo1admin',
            'owner_number' => 12345678,
            'owner_phone_country_code' => 91
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            'operation' => 'success'
        ]);
    }

    /**
     * Try to reset password
     * @param FunctionalTester $I
     */
    public function tryToResetPassword(FunctionalTester $I) {
        Yii::$app->params['newDashboardAppUrl'] = 'localhost';

        $I->wantTo('Validate auth > request-reset-password api');
        $I->sendPOST('v1/auth/request-reset-password', [
            'email' => $this->agent->agent_email,
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            'operation' => 'success'
        ]);
    }

    /**
     * Try to check if email verified
     * @param FunctionalTester $I
     */
    public function tryToCheckIfEmailVerified(FunctionalTester $I) {
        
        $agent = Agent::findOne([
            'agent_email_verification'=>0,
            'agent_status' => Agent::STATUS_ACTIVE
        ]);

        $I->wantTo('Validate auth > is-email-verified api');
        $I->sendPOST('v1/auth/is-email-verified', [
            'email' => $agent->agent_email,
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            'status' => 0
        ]);
    }

    /**
     * Try to update email
     * @param FunctionalTester $I
     */
    public function tryToUpdateEmail(FunctionalTester $I) {
        $I->wantTo('Validate auth > update-email api');
        $I->sendPOST('v1/auth/update-email', [
            'newEmail' => 'abc@test.com',
            'unVerifiedToken' => $this->token
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            'message' => 'Agent Account Info Updated Successfully, please check email to verify new email address'
        ]);
    }

    /**
     * Try to get verification email
     * @param FunctionalTester $I
     */
    public function tryToGetVerificationEmail(FunctionalTester $I) {


        $agent = Agent::findOne([
            'agent_email_verification'=>0,
            'agent_status' => Agent::STATUS_ACTIVE
        ]);

        $I->wantTo('Validate auth > resend-verification-email api');
        $I->sendPOST('v1/auth/resend-verification-email', [
            'email' => $agent->agent_email
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200

        $I->seeResponseContainsJson([
            "operation"=>"success"
        ]);

        /*$I->seeResponseContainsJson([
            "operation"=>"error",
            "errorCode"=>1,
            "message"=>"You have verified your email"
        ]);*/
    }

    /**
     * Try to verify email
     * @param FunctionalTester $I
     */
    public function tryToVerifyEmail(FunctionalTester $I) {

        $agent = Agent::findOne([
            'agent_email_verification'=>0,
            'agent_status' => Agent::STATUS_ACTIVE
        ]);

        $I->wantTo('Validate auth > verify-email api');
        $I->sendPOST('v1/auth/verify-email', [
            'email' => $agent->agent_email,
            'code' => $agent->agent_auth_key
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            'agent_email' => $agent->agent_email
        ]);
    }
}

