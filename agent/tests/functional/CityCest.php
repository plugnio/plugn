<?php

namespace agent\tests;

use agent\models\Agent;
use Codeception\Util\HttpCode;
use common\fixtures\AgentAssignmentFixture;
use common\fixtures\AgentFixture;
use common\fixtures\AgentTokenFixture;
use common\fixtures\CityFixture;


class CityCest
{
    public $token;
    public $agent;

    public function _fixtures() {
        return [
            'city' => CityFixture::class,
            'agent_assignments' => AgentAssignmentFixture::class,
            'agents' => AgentFixture::class,
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

    public function tryToGetList(FunctionalTester $I) {
        $I->wantTo('Validate agent > city listing api');
        $I->sendGET('v1/cities');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function tryToGetDetail(FunctionalTester $I) {
        $I->wantTo('Validate agent > city detail api');
        $I->sendGET('v1/cities/detail', [
            'city_id' => 1
        ]);
        $I->seeResponseCodeIs(HttpCode::OK);
    }
}