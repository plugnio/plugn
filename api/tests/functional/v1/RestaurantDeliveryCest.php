<?php
namespace api\tests\v1;

use api\models\Restaurant;
use api\tests\FunctionalTester;
use Codeception\Util\HttpCode;


class RestaurantDeliveryCest
{
    public $store;

    public function _fixtures() {
        return [
            'areas' => \common\fixtures\AreaFixture::className(),
            "restaurantDeliveries" => \common\fixtures\RestaurantDeliveryFixture::className(),
            'restaurants' => \common\fixtures\RestaurantFixture::className()
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
     * try to list all cities
     * @param FunctionalTester $I
     */
    public function tryToListDeliveryCities(FunctionalTester $I) {
        $I->wantTo('Validate restaurant > get delivery cities api');
        $I->sendGET('v1/restaurant-delivery/delivery-area/'. $this->store->restaurant_uuid);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToListDeliveryAreas(FunctionalTester $I) {

        $model = $this->store->getRestaurantDeliveryAreas()->one();

        $I->wantTo('Validate restaurant > get delivery area api');
        $I->sendGET('v1/restaurant-delivery/'.$model->area_id .'/'. $this->store->restaurant_uuid);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}