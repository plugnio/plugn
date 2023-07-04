<?php

namespace agent\tests;

use agent\models\Agent;
use agent\models\BankDiscount;
use Codeception\Util\HttpCode;
use common\fixtures\AgentAssignmentFixture;
use common\fixtures\AgentFixture;
use common\fixtures\AgentTokenFixture;
use common\fixtures\BankDiscountFixture;
use common\fixtures\BankFixture;
use common\fixtures\RestaurantFixture;


class BankDiscountCest
{
    public $token;
    public $agent;
    public $store;

    public function _fixtures() {
        return [
            'agents' => AgentFixture::className(),
            'agent_assignments' => AgentAssignmentFixture::className(),
            'banks' => BankFixture::className(),
            'bankDiscounts' => BankDiscountFixture::className(),
            'restaurants' => RestaurantFixture::className(),
            'agentToken' => AgentTokenFixture::className()
        ];
    }

    public function _before(FunctionalTester $I) {

        $this->agent = Agent::find()->one();//['agent_email_verification'=>1]

        $this->store = $this->agent->getAccountsManaged()->one();

        $this->token = $this->agent->getAccessToken()->token_value;

        $I->amBearerAuthenticated($this->token);

        $I->haveHttpHeader('Store-Id', $this->store->restaurant_uuid);
    }

    public function _after(FunctionalTester $I) {

    }

    public function tryToList(FunctionalTester $I) {
        $I->wantTo('Validate bank-discount > list api');
        $I->sendGET('v1/bank-discount');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToView(FunctionalTester $I) {
        $model = $this->store->getBankDiscounts()->one();

        $I->wantTo('Validate bank-discount > view api');
        $I->sendGET('v1/bank-discount/detail', [
            'bank_discount_id' => $model->bank_discount_id
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdate(FunctionalTester $I) {

        $model = $this->store->getBankDiscounts()->one();

        $I->wantTo('Validate bank-discount > update api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPATCH('v1/bank-discount/'. $model->bank_discount_id, [
            'bank_id' => 1,
            'discount_type' => BankDiscount::DISCOUNT_TYPE_PERCENTAGE,
            'discount_amount' => 3,
            'valid_from' => date('Y-m-d'),
            'valid_until'=> date('Y-m-d', strtotime('+1 month')),
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToCreate(FunctionalTester $I) {

        $I->wantTo('Validate bank-discount > create api');

        $I->sendPOST('v1/bank-discount', [
            'bank_id' => 1,
            'discount_type' => BankDiscount::DISCOUNT_TYPE_PERCENTAGE,
            'discount_amount' => 3,
            'valid_from' => date('Y-m-d'),
            'valid_until'=> date('Y-m-d', strtotime('+1 month')),
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdateStatus(FunctionalTester $I) {
        
        $model = $this->store->getBankDiscounts()->one();

        $I->wantTo('Validate bank-discount > update status api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPATCH('v1/bank-discount/update-status', [
            'bank_discount_id' => $model->bank_discount_id,
            'bankDiscountStatus' => BankDiscount::BANK_DISCOUNT_STATUS_ACTIVE,
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /* todo
    public function tryToDelete(FunctionalTester $I) {
        $model = $this->store->getBankDiscounts()->one();

        $I->wantTo('Validate bank-discount > delete api');
        $I->sendDelete('v1/bank-discount/'. $model->bank_discount_id);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }*/
}