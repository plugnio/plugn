<?php

namespace agent\tests;

use agent\models\Agent;
use Codeception\Util\HttpCode;
use common\fixtures\AgentAssignmentFixture;
use common\fixtures\AgentFixture;
use common\fixtures\AgentTokenFixture;
use common\fixtures\AreaFixture;


class AreaCest
{
    public $token;
    public $agent;

    public function _fixtures() {
        return [
            'agent_assignments' => AgentAssignmentFixture::class,
            'area' => AreaFixture::class,
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
        $I->wantTo('Validate agent > area listing api');
        $I->sendGET('v1/areas');
        $I->seeResponseCodeIs(HttpCode::OK);
    }

    public function tryToGetDetail(FunctionalTester $I) {
        $I->wantTo('Validate agent > area detail api');
        $I->sendGET('v1/areas/1');
        $I->seeResponseCodeIs(HttpCode::OK);
    }
}