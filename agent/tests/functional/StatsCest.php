<?php

namespace agent\tests;

use agent\models\Agent;
use Codeception\Util\HttpCode;
use common\fixtures\AgentAssignmentFixture;
use common\fixtures\AgentFixture;
use common\fixtures\AgentTokenFixture;
use common\fixtures\CustomerFixture;
use common\fixtures\ItemFixture;
use common\fixtures\OrderFixture;
use common\fixtures\RestaurantFixture;

class StatsCest
{
    public $token;
    public $agent;
    public $store;

    public function _fixtures() {
        return [
            'agents' => AgentFixture::className(),
            'items' => ItemFixture::className(),
            'agent_assignments' => AgentAssignmentFixture::className(),
            'orders' => OrderFixture::className(),
            'customers' => CustomerFixture::className(),
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

    public function tryToDetail(FunctionalTester $I) {
        $I->wantTo('Validate stats > view api');
        $I->sendGET('v1/stats');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}
