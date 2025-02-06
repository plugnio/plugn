<?php
namespace api\tests\v2;

use api\models\Restaurant;
use Codeception\Util\HttpCode;
use api\tests\FunctionalTester;

class DeliveryZoneCest
{
    public $store;

    public function _fixtures()
    {
        return [
            'areas' => \common\fixtures\AreaFixture::class,
            'countries' => \common\fixtures\CountryFixture::class,
            'deliveryZones' => \common\fixtures\DeliveryZoneFixture::class,
            'restaurants' => \common\fixtures\RestaurantFixture::class,
            'businessLocations' => \common\fixtures\BusinessLocationFixture::class
        ];
    }

    public function _before(FunctionalTester $I)
    {

        $this->store = Restaurant::find()->one();

        $I->haveHttpHeader('Store-Id', $this->store->restaurant_uuid);
    }

    public function _after(FunctionalTester $I)
    {

    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToListCountries(FunctionalTester $I)
    {
        $I->wantTo('Validate delivery-zone > list countries api');
        $I->sendGET('v2/delivery-zone/list-of-countries/' . $this->store->restaurant_uuid);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToListPickupLocations(FunctionalTester $I)
    {
        $I->wantTo('Validate delivery-zone > list pickup locations api');
        $I->sendGET('v2/delivery-zone/list-pickup-locations/' . $this->store->restaurant_uuid);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToListAreas(FunctionalTester $I)
    {
        $I->wantTo('Validate delivery-zone > list areas api');
        $I->sendGET('v2/delivery-zone/list-of-areas/' . $this->store->restaurant_uuid . '/' . $this->store->country_id);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToGetPickupLocation(FunctionalTester $I)
    {
        $location = $this->store->getPickupBusinessLocations()->one();

        $I->wantTo('Validate delivery-zone > view pickup location api');
        $I->sendGET('v2/delivery-zone/pickup-location/' . $this->store->restaurant_uuid . '/' . $location->business_location_id);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToGetDeliveryZoneDetails(FunctionalTester $I)
    {
        $dz = $this->store->getDeliveryZones()->one();

        $I->wantTo('Validate delivery-zone > get delivery zone api');
        $I->sendGET('v2/delivery-zone/' . $this->store->restaurant_uuid . '/' . $dz->delivery_zone_id);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}