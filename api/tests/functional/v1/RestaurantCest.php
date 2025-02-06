<?php
namespace api\tests\v1;

use api\models\Restaurant;
use api\tests\FunctionalTester;
use Codeception\Util\HttpCode;
use common\fixtures\RestaurantDeliveryFixture;
use common\models\RestaurantDelivery;

class RestaurantCest
{
    public $store;

    public function _fixtures() {
        return [
            'items' => \common\fixtures\ItemFixture::class,
            'openingHours' => \common\fixtures\OpeningHourFixture::class,
            "restaurantBranches" => \common\fixtures\RestaurantBranchFixture::class,
            'restaurantDeliveryAreas' => RestaurantDeliveryFixture::class,
            'businessLocations' => \common\fixtures\BusinessLocationFixture::class,
            'restaurants' => \common\fixtures\RestaurantFixture::class
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
     *
    public function tryToListOpeningHours (FunctionalTester $I) {

        $model = $this->store->getRestaurantDeliveryAreas()->one();

        $I->wantTo('Validate restaurant > get opening hours api');
        $I->sendGET('v1/restaurant/get-opening-hours', [
            "restaurant_uuid" => $this->store->restaurant_uuid,
            "area_id" => $model->area_id
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }*/

    /**
     * @param FunctionalTester $I
     */
    public function tryToListBrances (FunctionalTester $I) {
        $I->wantTo('Validate restaurant > get branches api');
        $I->sendGET('v1/restaurant/branches/'. $this->store->restaurant_uuid);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToGetDetail (FunctionalTester $I) {
        $I->wantTo('Validate restaurant > get detail api');
        $I->sendGET('v1/restaurant/get-restaurant-data/'. $this->store->store_branch_name);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}