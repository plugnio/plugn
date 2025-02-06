<?php

namespace agent\tests;

use agent\models\Agent;
use agent\models\OpeningHour;
use Codeception\Util\HttpCode;
use common\fixtures\AgentAssignmentFixture;
use common\fixtures\AgentFixture;
use common\fixtures\AgentTokenFixture;
use common\fixtures\OpeningHourFixture;
use common\fixtures\RestaurantFixture;


class OpeningHoursCest
{
    public $token;
    public $agent;
    public $store;

    public function _fixtures() {
        return [
            'agents' => AgentFixture::class,
            'agent_assignments' => AgentAssignmentFixture::class,
            'hours' => OpeningHourFixture::class,
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
        $I->wantTo('Validate opening hours > list api');
        $I->sendGET('v1/opening-hours');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToDetail(FunctionalTester $I) {
        $I->wantTo('Validate opening hours > view day api');
        $I->sendGET('v1/opening-hours/1');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToAdd(FunctionalTester $I) {
        $I->wantTo('Validate order > add hours api');
        $I->sendPOST('v1/opening-hours', [
            'opening_hours' => [
                [
                    'day_of_week' => 1,
                    'open_at' => '11:00am',
                    'close_at' => '11:00pm',
                ]
            ]
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdate(FunctionalTester $I) {
        $I->wantTo('Validate order > update hours api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPATCH('v1/opening-hours/1', [
            'opening_hours' => [
                [
                    'open_at' => '11:00am',
                    'close_at' => '11:00pm',
                ]
            ]
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToDelete(FunctionalTester $I) {
        $model = $this->store->getOpeningHours()->one();

        $I->wantTo('Validate order > delete hours api');
        $I->sendDELETE('v1/opening-hours/' . $model->opening_hour_id);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}