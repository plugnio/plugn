<?php
namespace api\tests\v2;

use api\models\Restaurant;
use Codeception\Util\HttpCode;
use common\fixtures\RestaurantDeliveryFixture;
use api\tests\FunctionalTester;

class StoreCest
{
    public $store;

    public function _fixtures() {
        return [
            'items' => \common\fixtures\ItemFixture::className(),
            'openingHours' => \common\fixtures\OpeningHourFixture::className(),
            'deliveryZones' => \common\fixtures\DeliveryZoneFixture::className(),
            "restaurantBranches" => \common\fixtures\RestaurantBranchFixture::className(),
            'restaurantDeliveryAreas' => RestaurantDeliveryFixture::className(),
            'restaurants' => \common\fixtures\RestaurantFixture::className(),
            'businessLocations' => \common\fixtures\BusinessLocationFixture::className()
        ];
    }

    /**
     * @param FunctionalTester $I
     */
    public function _before(FunctionalTester $I) {

        $this->store = Restaurant::find()->one();

        $I->haveHttpHeader('Store-Id', $this->store->restaurant_uuid);
    }

    public function _after(FunctionalTester $I) {

    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToListOpeningHours (FunctionalTester $I) {

        $model = $this->store->getRestaurantDeliveryAreas()->one();

        $I->wantTo('Validate restaurant > get opening hours api');
        $I->sendGET('v2/store/get-opening-hours', [
            "restaurant_uuid" => $this->store->restaurant_uuid,
            "area_id" => $model->area_id
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToListLocations (FunctionalTester $I) {
        $I->wantTo('Validate restaurant > get locations api');
        $I->sendGET('v2/store/locations/'. $this->store->restaurant_uuid);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToGetDetail (FunctionalTester $I) {
        $I->wantTo('Validate restaurant > get detail api');
        $I->sendGET('v2/store/get-restaurant-data/'. $this->store->store_branch_name);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToGetDeliveryTime (FunctionalTester $I) {

        $dz = $this->store->getDeliveryZones()->one();

        $I->wantTo('Validate restaurant > get delivery api');
        $I->sendPOST('v2/store/get-delivery-time', [
            "restaurant_uuid" => $this->store->restaurant_uuid,
            "delivery_zone_id" => $dz->delivery_zone_id,
            "cart" => [
                [
                    "prep_time_in_min" => 30
                ]
            ]
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}