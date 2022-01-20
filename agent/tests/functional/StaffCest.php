<?php

namespace agent\tests;

use agent\models\Agent;
use Codeception\Util\HttpCode;
use common\fixtures\AgentAssignmentFixture;
use common\fixtures\AgentFixture;
use common\fixtures\AgentTokenFixture;
use common\fixtures\BusinessLocationFixture;
use common\fixtures\RestaurantFixture;
use common\models\AgentAssignment;

class StaffCest
{
    public $token;
    public $agent;
    public $store;

    public function _fixtures() {
        return [
            'locations' => BusinessLocationFixture::className(),
            'agents' => AgentFixture::className(),
            'agentAssignments' => AgentAssignmentFixture::className(),
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
        $I->wantTo('Validate staff > list api');
        $I->sendGET('v1/staff');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToDetail(FunctionalTester $I) {

        $agent = $this->store->getAgentAssignments()->one();

        $I->wantTo('Validate staff > detail api');
        $I->sendGET('v1/staff/detail', [
            'assignment_id' => $agent->assignment_id,
            'store_uuid' => $this->store->restaurant_uuid
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToAdd(FunctionalTester $I) {
        $I->wantTo('Validate staff > create api');
        $I->sendPOST('v1/staff/create', [
            'store_uuid' => $this->store->restaurant_uuid,
            'business_location_id' => 1,
            'role' => AgentAssignment::AGENT_ROLE_OWNER,
            'agent_email' => 'demo@localhost.com',
            'agent_name' => 'demo user'
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            "operation" => "success"
        ]);
    }

    public function tryToUpdate(FunctionalTester $I) {

        $model = AgentAssignment::find()
            ->andWhere([
                'restaurant_uuid' => $this->store->restaurant_uuid,
                'agent_id' => $this->agent->agent_id
            ])
            ->one();

        $I->wantTo('Validate staff > update api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPATCH('v1/staff/'. $model->assignment_id . '/' .$this->store->restaurant_uuid, [
            'business_location_id' => 1,
            'role' => AgentAssignment::AGENT_ROLE_OWNER,
            'agent_email' => 'demo@localhost.com'
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            "operation" => "success"
        ]);
    }

    public function tryToDelete(FunctionalTester $I) {

        $model = AgentAssignment::find()
            ->andWhere([
                'restaurant_uuid' => $this->store->restaurant_uuid,
                'agent_id' => $this->agent->agent_id
            ])
            ->one();

        $I->wantTo('Validate staff > delete api');
        $I->sendDELETE('v1/staff/'. $model->assignment_id . '/' .$this->store->restaurant_uuid);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            "operation" => "success"
        ]);
    }
}