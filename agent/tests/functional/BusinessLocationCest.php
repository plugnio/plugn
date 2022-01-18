<?php

namespace agent\tests;

use common\fixtures\AgentFixture;
use common\fixtures\AgentTokenFixture;
use common\fixtures\BusinessLocationFixture;
use common\fixtures\CountryFixture;
use common\fixtures\RestaurantFixture;

class BusinessLocationCest
{
    public $token;
    public $agent;
    public $store;

    public function _fixtures() {
        return [
            'agents' => AgentFixture::className(),
            'countries' => CountryFixture::className(),
            'locations' => BusinessLocationFixture::className(),
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
        $I->wantTo('Validate business-location > list api');
        $I->sendGET('v1/business-location');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToView(FunctionalTester $I) {

        $model = $this->store->getBusinessLocations()->one();

        $I->wantTo('Validate business-location > view api');
        $I->sendGET('v1/business-location/detail', [
            'business_location_id' => $model->business_location_id
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdate(FunctionalTester $I) {

        $model = $this->store->getBusinessLocations()->one();

        $I->wantTo('Validate business-location > view api');
        $I->sendPATCH('v1/business-location/' . $model->business_location_id, [
            'country_id' => 1,
            'business_location_name' => 'Lahor',
            'business_location_name_ar' => 'Lahor',
            'support_pick_up' => true,
            'business_location_tax' => 10,
            'mashkor_branch_id' => '1425687568',
            'armada_api_key' => '12313123123',
            'address' => 'Lahor main branch',
            'latitude' => 80,
            'longitude' => 80,
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToCreate(FunctionalTester $I) {
        $I->wantTo('Validate business-location > create api');
        $I->sendPOST('v1/business-location', [
            'country_id' => 1,
            'business_location_name' => 'Lahor',
            'business_location_name_ar' => 'Lahor',
            'support_pick_up' => true,
            'business_location_tax' => 10,
            'mashkor_branch_id' => '1425687568',
            'armada_api_key' => '12313123123',
            'address' => 'Lahor main branch',
            'latitude' => 80,
            'longitude' => 80,
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToDelete(FunctionalTester $I) {

        $model = $this->store->getBusinessLocations()->one();

        $I->wantTo('Validate business-location > delete api');
        $I->sendDELETE('v1/business-location/' . $model->business_location_id);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}
