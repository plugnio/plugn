<?php

namespace agent\tests;

use common\fixtures\AgentFixture;
use common\fixtures\AgentTokenFixture;
use common\fixtures\BankFixture;
use common\fixtures\CurrencyFixture;
use common\fixtures\RestaurantFixture;

class CurrencyCest
{
    public $token;
    public $agent;
    public $store;

    public function _fixtures() {
        return [
            'agents' => AgentFixture::className(),
            'currency' => CurrencyFixture::className(),
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
        $I->wantTo('Validate currency > list api');
        $I->sendGET('v1/currencies');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToListStoreCurrencies(FunctionalTester $I) {
        $I->wantTo('Validate currency > list store currencies api');
        $I->sendGET('v1/currencies/store-currencies');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdate(FunctionalTester $I) {
        $I->wantTo('Validate currency > update store currencies api');
        $I->sendPOST('v1/currencies', [
            'currencies' => [1,2],//store currency
            'currency_id' => 1//default
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}