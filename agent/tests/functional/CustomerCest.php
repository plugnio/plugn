<?php

namespace agent\tests;

use common\fixtures\AgentFixture;
use common\fixtures\AgentTokenFixture;
use common\fixtures\BankFixture;
use common\fixtures\RestaurantFixture;

class CustomerCest
{
    public $token;
    public $agent;
    public $store;

    public function _fixtures() {
        return [
            'agents' => AgentFixture::className(),
            'banks' => BankFixture::className(),
            'restaurants' => RestaurantFixture::className(),
            'agentToken' => AgentTokenFixture::className()
        ];
    }

    public function _before(FunctionalTester $I) {

        $this->agent = Agent::find()->one();//['agent_email_verification'=>1]

        $this->store = $this->agent->getStores()->one();

        $this->token = $this->agent->getAccessToken()->token_value;

        $I->amBearerAuthenticated($this->token);

        $I->haveHttpHeader('Store-Id', $this->store->restaurant_uuid);
    }

    public function _after(FunctionalTester $I) {

    }

    public function tryToList(FunctionalTester $I) {
        $I->wantTo('Validate customer > list api');
        $I->sendGET('v1/customer');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToDetail(FunctionalTester $I) {
        $I->wantTo('Validate customer > detail api');
        $I->sendGET('v1/customer/detail', [
            'customer_id' => 1
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToListOrders(FunctionalTester $I) {
        $I->wantTo('Validate customer > list orders api');
        $I->sendGET('v1/customer/orders');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToExportToExcel(FunctionalTester $I) {
        $I->wantTo('Validate customer > export to excel api');
        $I->sendGET('v1/customer/export-to-excel');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToCreate(FunctionalTester $I) {
        $I->wantTo('Validate customer > create api');
        $I->sendPOST('v1/customer', [
            'customer_name' => 'demo user',
            'customer_phone_number' => 23234234,
            'country_code' => 91,
            'customer_email' => 'demo@localhost.com'
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}
