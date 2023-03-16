<?php

namespace agent\tests;

use agent\models\Agent;
use Codeception\Util\HttpCode;
use common\fixtures\AgentAssignmentFixture;
use common\fixtures\AgentFixture;
use common\fixtures\AgentTokenFixture;
use common\fixtures\AreaDeliveryZoneFixture;
use common\fixtures\AreaFixture;
use common\fixtures\CityFixture;
use common\fixtures\CountryFixture;
use common\fixtures\DeliveryZoneFixture;
use common\fixtures\RestaurantFixture;
use common\fixtures\BusinessLocationFixture;


class AreaDeliveryZoneCest
{
    public $token;
    public $agent;
    public $store;

    public function _fixtures() {
        return [
            'agents' => AgentFixture::className(),
            'areas' => AreaFixture::className(),
            'cities' => CityFixture::className(),
            'agent_assignments' => AgentAssignmentFixture::className(),
            'countries' => CountryFixture::className(),
            'areaDeliveryZone' => AreaDeliveryZoneFixture::className(),
            'deliveryZones' => DeliveryZoneFixture::className(),
            'businessLocations' => BusinessLocationFixture::className(),
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
        $I->wantTo('Validate area-delivery-zone > list api');
        $I->sendGET('v1/area-delivery-zone');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToDelete(FunctionalTester $I) {

        $model = $this->store->getAreaDeliveryZones()->one();

        $I->wantTo('Validate area-delivery-zone > delete api');
        //$I->haveHttpHeader('Store-Id', $model->restaurant_uuid);
        $I->sendDELETE('v1/area-delivery-zone/' . $model->area_delivery_zone);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdate(FunctionalTester $I) {

        $model = $this->store->getAreaDeliveryZones()->one();

        $dz = $this->store->getDeliveryZones()->one();

        $I->wantTo('Validate area-delivery-zone > delete api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPATCH('v1/area-delivery-zone/' . $model->area_delivery_zone, [
            'area_id' => 1,
            'delivery_zone_id' => $dz->delivery_zone_id
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToCreate(FunctionalTester $I) {

        $dz = $this->store->getDeliveryZones()->one();

        $I->wantTo('Validate area-delivery-zone > create api');
        $I->sendPOST('v1/area-delivery-zone/create', [
            'area_id' => 1,
            'delivery_zone_id' => $dz->delivery_zone_id
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToSave(FunctionalTester $I) {

        $dz = $this->store->getDeliveryZones()->one();

        $I->wantTo('Validate area-delivery-zone > save api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPATCH('v1/area-delivery-zone/save', [
            'areas' => [
                [
                    'city_id' => 1,
                    'area_id' => 1,
                ]
            ],
            'delivery_zone_id' => $dz->delivery_zone_id
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}