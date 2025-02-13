<?php

namespace agent\tests;

use agent\models\Agent;
use Codeception\Util\HttpCode;
use common\fixtures\AgentAssignmentFixture;
use common\fixtures\AgentFixture;
use common\fixtures\AgentTokenFixture;
use common\fixtures\BankFixture;
use common\fixtures\RestaurantFixture;

class BankCest
{
    public $token;
    public $agent;
    public $store;

    public function _fixtures() {
        return [
            'agents' => AgentFixture::class,
            'banks' => BankFixture::class,
            'agent_assignments' => AgentAssignmentFixture::class,
            'restaurants' => RestaurantFixture::class,
            'agentToken' => AgentTokenFixture::class
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
        $I->wantTo('Validate bank > list api');
        $I->sendGET('v1/bank');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}