<?php

namespace crm\tests;

use crm\models\Staff;
use Codeception\Util\HttpCode;
use common\fixtures\StaffAssignmentFixture;
use common\fixtures\StaffFixture;
use common\fixtures\StaffTokenFixture;
use common\fixtures\RestaurantFixture;

class StaffCest
{
    public $token;
    public $staff;

    public function _fixtures() {
        return [
            'staff_assignments' => StaffAssignmentFixture::className(),
            'stores' => RestaurantFixture::className(),
            'staffs' => StaffFixture::className(),
            'staffToken' => StaffTokenFixture::className()
        ];
    }

    public function _before(FunctionalTester $I) {

        $this->staff = Staff::find()->one();//['staff_email_verification'=>1]

        $this->token = $this->staff->getAccessToken()->token_value;

        $this->store = $this->staff->getAccountsManaged()->one();

        $I->amBearerAuthenticated($this->token);

        $I->haveHttpHeader('Store-Id', $this->store->restaurant_uuid);

    }

    public function _after(FunctionalTester $I) {

    }

    public function tryToGetDetail(FunctionalTester $I) {
        $I->wantTo('Validate staff > detail api');
        $I->sendGET('v1/staff');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            'staff_id' => $this->staff->staff_id
        ]);
    }

    public function tryToGetStoreDetail(FunctionalTester $I) {
        $I->wantTo('Validate staff > store detail api');
        $I->sendGET('v1/staff/store-profile');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToGetStoreList(FunctionalTester $I) {
        $I->wantTo('Validate staff > store listing api');
        $I->sendGET('v1/staff/stores');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdateLanguagePref(FunctionalTester $I) {
        $I->wantTo('Validate staff > update language preference api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPATCH('v1/staff/language-pref', [
            'language_pref' => 'en'
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }


    public function tryToUpdate(FunctionalTester $I) {
        $I->wantTo('Validate staff > update api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPUT('v1/staff/update', [
            'staff_name' => 'Demo staff',
            'staff_email' => 'demo@localhost.com',
            'email_notification' => 1,
            'reminder_email' => 1,
            'receive_weekly_stats' => 1
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToChangePassword(FunctionalTester $I) {
        $I->wantTo('Validate staff > change password api');
        $I->sendPOST('v1/staff/change-password', [
            'oldPassword' => 'demo1admin',
            'newPassword' => 'demo1admin',
            'confirmPassword' => 'demo1admin',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}
