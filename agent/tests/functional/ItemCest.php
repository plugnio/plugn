<?php

namespace agent\tests;

use agent\models\Agent;
use Codeception\Util\HttpCode;
use common\fixtures\AgentAssignmentFixture;
use common\fixtures\AgentFixture;
use common\fixtures\AgentTokenFixture;
use common\fixtures\ItemFixture;
use common\fixtures\RestaurantFixture;

class ItemCest
{
    public $token;
    public $agent;
    public $store;

    public function _fixtures() {
        return [
            'agents' => AgentFixture::class,
            'agent_assignments' => AgentAssignmentFixture::class,
            'items' => ItemFixture::class,
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
        $I->wantTo('Validate item > list api');
        $I->sendGET('v1/items');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToView(FunctionalTester $I) {
        $item = $this->store->getItems()->one();

        $I->wantTo('Validate item > view api');
        $I->sendGET('v1/items/' . $item->item_uuid);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToDelete(FunctionalTester $I) {
        $item = $this->store->getItems()->one();

        $I->wantTo('Validate item > delete api');
        $I->sendDELETE('v1/items/' . $item->item_uuid);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdate(FunctionalTester $I) {
        $item = $this->store->getItems()->one();

        $I->wantTo('Validate item > update api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPATCH('v1/items/' . $item->item_uuid, [
            "item_name" => 'lollipop',
            "item_name_ar" => 'lollipop',
            "item_description" => 'lollipop',
            "item_description_ar" => 'lollipop',
            "sort_number" => 1,
            "prep_time" => 1,
            "prep_time_unit" => 'hour',
            "item_price" => 112,
            "sku" => 'RT12',
            "barcode" => '1234141',
            "track_quantity" => true,
            "stock_qty" => 12,
            "itemCategories" => [],
            "itemImages" => [],
            "options" => [],
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToCreate(FunctionalTester $I) {

        $I->wantTo('Validate item > create api');
        $I->sendPOST('v1/items', [
            "item_name" => 'lollipop',
            "item_name_ar" => 'lollipop',
            "item_description" => 'lollipop',
            "item_description_ar" => 'lollipop',
            "sort_number" => 1,
            "prep_time" => 1,
            "prep_time_unit" => 'hour',
            "item_price" => 112,
            "sku" => 'RT12',
            "barcode" => '1234141',
            "track_quantity" => true,
            "stock_qty" => 12,
            "itemCategories" => [],
            "itemImages" => [],
            "options" => [],
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdateStock(FunctionalTester $I) {

        $item = $this->store->getItems()->one();

        $I->wantTo('Validate item > update stock api');
        //$I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPOST('v1/items/update-stock', [
            "item_uuid" => $item->item_uuid,
            "stock_qty" => 12
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdatePosition(FunctionalTester $I) {

        $item = $this->store->getItems()->one();
        $item2 = $this->store->getItems()->offset(1)->one();

        $I->wantTo('Validate item > update position api');
        $I->sendPOST('v1/items/update-position', [
            "items" => [
                $item->item_uuid,
                $item2->item_uuid
            ]
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdateStatus(FunctionalTester $I) {
        $item = $this->store->getItems()->one();

        $I->wantTo('Validate item > update status api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPATCH('v1/items/update-status/' . $item->item_uuid
            . '/' . $this->store->restaurant_uuid, [
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * todo: delete image test
     * @param FunctionalTester $I
     *
    public function tryToDeleteImage(FunctionalTester $I) {
        $item = $this->store->getItems()->one();

        $image = $item->getImages()->one();

        $I->wantTo('Validate item > delete image api');
        $I->sendDelete('v1/items/delete-image/' . $item->item_uuid
            . '/' . $image->product_file_name, [
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }*/

    /*
    public function tryToExportToExcel(FunctionalTester $I) {
        $I->wantTo('Validate item > export to excel api');
        $I->sendGET('v1/items/export-to-excel');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToGetItemReport(FunctionalTester $I) {
        $I->wantTo('Validate item > export item report api');
        $I->sendGET('v1/items/items-report');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }*/
}
