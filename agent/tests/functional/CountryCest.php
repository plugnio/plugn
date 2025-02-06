<?php

namespace agent\tests;

use agent\models\Agent;
use Codeception\Util\HttpCode;
use common\fixtures\AgentAssignmentFixture;
use common\fixtures\AgentFixture;
use common\fixtures\AgentTokenFixture;
use common\fixtures\CountryFixture;

class CountryCest
{
    public $token;
    public $agent;

    public function _fixtures() {
        return [
            'country' => CountryFixture::class,
            'agent_assignments' => AgentAssignmentFixture::class,
            'agents' => AgentFixture::class,
            'agentToken' => AgentTokenFixture::class
        ];
    }

    public function _before(FunctionalTester $I) {

        $this->agent = Agent::find()->one();//['agent_email_verification'=>1]

        $this->store = $this->agent->getAccountsManaged()->one();

        $I->haveHttpHeader('Store-Id', $this->store->restaurant_uuid);

        $this->token = $this->agent->getAccessToken()->token_value;

        $I->amBearerAuthenticated($this->token);
    }

    public function _after(FunctionalTester $I) {

    }

    public function tryToGetList(FunctionalTester $I) {
        $I->wantTo('Validate agent > country listing api');
        $I->sendGET('v1/country');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function tryToGetDetail(FunctionalTester $I) {
        $I->wantTo('Validate agent > country detail api');
        $I->sendGET('v1/country/detail', [
            'country_id' => 1
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }
}