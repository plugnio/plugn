<?php
namespace api\tests\v2;

use Yii;
use api\models\Customer;
use api\models\Restaurant;
use api\tests\FunctionalTester;
use Codeception\Util\HttpCode;
use common\fixtures\CustomerFixture;
use common\fixtures\CustomerTokenFixture;
use common\fixtures\CountryFixture;
use common\fixtures\CurrencyFixture;
use common\fixtures\RestaurantFixture;

class AuthCest
{
    public $token;
    public $customer;

    public function _fixtures() {
        return [
            'country' => CountryFixture::className(),
            'currencies' => CurrencyFixture::className(),
            'customers' => CustomerFixture::className(),
            'restaurants' => RestaurantFixture::className(),
            'customerToken' => CustomerTokenFixture::className()
        ];
    }

    public function _before(FunctionalTester $I) {

        $this->customer = Customer::find()->one();//['customer_email_verification'=>1]

        $this->token = $this->customer->getAccessToken()->token_value;

        $I->amBearerAuthenticated($this->token);

        $this->store = Restaurant::find()->one();

        $I->haveHttpHeader('Store-Id', $this->store->restaurant_uuid);

    }

    public function _after(FunctionalTester $I) {

    }

    /**
     * Login
     * @param FunctionalTester $I
     */
    public function tryToLogin(FunctionalTester $I) {

        $I->wantTo('Validate auth > login api');
        $I->amHttpAuthenticated($this->customer->customer_email, '12345');
        $I->sendGET('v2/auth/login');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            'id' => $this->customer->customer_id
        ]);
    }

    /**
     * Try to update password
     * @param FunctionalTester $I
     */
    public function tryToUpdatePassword(FunctionalTester $I) {

        $customer =  Customer::findOne(['customer_id'=>$this->customer->customer_id]);
        $customer->setScenario('changePassword');
        $customer->customer_password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
        $customer->save(false);

        $I->wantTo('Validate auth > update-password api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPATCH('v2/auth/update-password', [
            'newPassword' => 'demo1admin',
            'token' => $customer->customer_password_reset_token
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
    $I->sendPOST('v2/auth/email-check', [
    'email' => $this->customer->customer_email
    ]);
    $I->seeResponseCodeIs(HttpCode::OK); // 200
    $I->seeResponseContainsJson([
    "customer_id"=>$this->customer->customer_id
    ]);
    }*/

    /**
     * todo: Try to register
     * @param FunctionalTester $I
     *
    public function tryToRegister(FunctionalTester $I) {
        $I->wantTo('Validate auth > register api');
        $I->sendPOST('v2/auth/signup', [
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
    }*/

    /**
     * Try to reset password
     * @param FunctionalTester $I
     */
    public function tryToResetPassword(FunctionalTester $I) {
        Yii::$app->params['newDashboardAppUrl'] = 'localhost';

        $I->wantTo('Validate auth > request-reset-password api');
        $I->sendPOST('v2/auth/request-reset-password', [
            'email' => $this->customer->customer_email,
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

        $this->customer->customer_email_verification = 0;
        $this->customer->save(false);

        $customer = Customer::findOne([
            'customer_email_verification'=> 0,
            'deleted' => 0
        ]);
        $I->wantTo('Validate auth > is-email-verified api');
        $I->sendPOST('v2/auth/is-email-verified', [
            'email' => $customer->customer_email,
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
        $I->sendPOST('v2/auth/update-email', [
            'newEmail' => 'abc@test.com',
            'unVerifiedToken' => $this->token
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            'message' => 'Customer Account Info Updated Successfully, please check email to verify new email address'
        ]);
    }

    /**
     * Try to get verification email
     * @param FunctionalTester $I
     */
    public function tryToGetVerificationEmail(FunctionalTester $I) {

        $this->customer->customer_email_verification = 0;
        $this->customer->save(false);

        $I->wantTo('Validate auth > resend-verification-email api');
        $I->sendPOST('v2/auth/resend-verification-email', [
            'email' => $this->customer->customer_email
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

        $this->customer->customer_email_verification = 0;
        $this->customer->save(false);

        $I->wantTo('Validate auth > verify-email api');
        $I->sendPOST('v2/auth/verify-email', [
            'email' => $this->customer->customer_email,
            'code' => $this->customer->customer_auth_key
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            'email' => $this->customer->customer_email
        ]);
    }
}