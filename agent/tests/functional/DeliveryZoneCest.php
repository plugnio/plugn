<?php

namespace agent\tests;

use agent\models\Agent;
use agent\models\DeliveryZone;
use Codeception\Util\HttpCode;
use common\fixtures\AgentAssignmentFixture;
use common\fixtures\AgentFixture;
use common\fixtures\AgentTokenFixture;
use common\fixtures\BusinessLocationFixture;
use common\fixtures\CurrencyFixture;
use common\fixtures\DeliveryZoneFixture;
use common\fixtures\RestaurantFixture;

class DeliveryZoneCest
{
    public $token;
    public $agent;
    public $store;

    public function _fixtures() {
        return [
            'agents' => AgentFixture::class,
            'agent_assignments' => AgentAssignmentFixture::class,
            'delivery-zone' => DeliveryZoneFixture::class,
            'businessLocations' => BusinessLocationFixture::class,
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

        $model = $this->store->getBusinessLocations()->one();

        $I->wantTo('Validate delivery-zone > list api');
        $I->sendGET('v1/delivery-zone', [
            'business_location_id' => $model->business_location_id
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToDetail(FunctionalTester $I) {
        $model = $this->store->getDeliveryZones()->one();

        $I->wantTo('Validate delivery-zone > detail api');
        $I->sendGET('v1/delivery-zone/detail', [
            'delivery_zone_id' => $model->delivery_zone_id
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToCreate(FunctionalTester $I) {

        $model = $this->store->getBusinessLocations()->one();

        $I->wantTo('Validate delivery-zone > create api');
        $I->sendPOST('v1/delivery-zone/create', [
            'business_location_id' => $model->business_location_id,
            'country_id' => 1,
            'delivery_time' => '10am to 11pm',
            'time_unit' => 'hour',
            'delivery_fee' => 10,
            'min_charge' => 5,
            'delivery_zone_tax' => 5
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdate(FunctionalTester $I) {

        $dz = $this->store->getDeliveryZones()->one();

        $model = $this->store->getBusinessLocations()->one();

        $I->wantTo('Validate delivery-zone > update api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPATCH('v1/delivery-zone/' . $dz->delivery_zone_id, [
           // 'store_id'
            'business_location_id' => $model->business_location_id,
            'country_id' => 1,
            'delivery_time' => '10am to 11pm',
            'time_unit' => 'hour',
            'delivery_fee' => 10,
            'min_charge' => 5,
            'delivery_zone_tax' => 5
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToDelete(FunctionalTester $I) {

        $dz = $this->store->getDeliveryZones()->one();

        $I->wantTo('Validate delivery-zone > delete api');
        $I->sendDELETE('v1/delivery-zone/' . $dz->delivery_zone_id);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToCancelOverride(FunctionalTester $I) {

        $dz = $this->store->getDeliveryZones()->one();

        $I->wantTo('Validate delivery-zone > cancel override api');
        $I->sendDELETE('v1/delivery-zone/cancel-override/' . $dz->delivery_zone_id);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}