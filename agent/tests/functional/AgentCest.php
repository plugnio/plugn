<?php

namespace agent\tests;

use agent\models\Agent;
use Codeception\Util\HttpCode;
use common\fixtures\AgentAssignmentFixture;
use common\fixtures\AgentFixture;
use common\fixtures\AgentTokenFixture;
use common\fixtures\RestaurantFixture;

class AgentCest
{
    public $token;
    public $agent;

    public function _fixtures() {
        return [
            'agent_assignments' => AgentAssignmentFixture::className(),
            'stores' => RestaurantFixture::className(),
            'agents' => AgentFixture::className(),
            'agentToken' => AgentTokenFixture::className()
        ];
    }

    public function _before(FunctionalTester $I) {

        $this->agent = Agent::find()->one();//['agent_email_verification'=>1]

        $this->token = $this->agent->getAccessToken()->token_value;

        $this->store = $this->agent->getAccountsManaged()->one();

        $I->amBearerAuthenticated($this->token);

        $I->haveHttpHeader('Store-Id', $this->store->restaurant_uuid);

    }

    public function _after(FunctionalTester $I) {

    }

    public function tryToGetDetail(FunctionalTester $I) {
        $I->wantTo('Validate agent > detail api');
        $I->sendGET('v1/agent');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            'agent_id' => $this->agent->agent_id
        ]);
    }

    public function tryToGetStoreDetail(FunctionalTester $I) {
        $I->wantTo('Validate agent > store detail api');
        $I->sendGET('v1/agent/store-profile');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToGetStoreList(FunctionalTester $I) {
        $I->wantTo('Validate agent > store listing api');
        $I->sendGET('v1/agent/stores');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdate(FunctionalTester $I) {
        $I->wantTo('Validate agent > update api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPUT('v1/agent/update', [
            'agent_name' => 'Demo agent',
            'agent_email' => 'demo@localhost.com',
            'email_notification' => 1,
            'reminder_email' => 1,
            'receive_weekly_stats' => 1
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToChangePassword(FunctionalTester $I) {
        $I->wantTo('Validate agent > change password api');
        $I->sendPOST('v1/agent/change-password', [
            'oldPassword' => 'demo1admin',
            'newPassword' => 'demo1admin',
            'confirmPassword' => 'demo1admin',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}
