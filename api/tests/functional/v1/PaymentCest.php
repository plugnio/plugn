<?php
namespace api\tests\v1;

use api\models\Restaurant;
use api\tests\FunctionalTester;
use Codeception\Util\HttpCode;


class PaymentCest
{
    public $store;

    public function _fixtures() {
        return [
            'items' => \common\fixtures\ItemFixture::class,
            'paymentMethods' => \common\fixtures\RestaurantPaymentMethodFixture::class,
            'restaurants' => \common\fixtures\RestaurantFixture::class
        ];
    }

    public function _before(FunctionalTester $I) {

        $this->store = Restaurant::find()->one();

        $I->haveHttpHeader('Store-Id', $this->store->restaurant_uuid);
    }

    public function _after(FunctionalTester $I) {

    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToListPaymentOption(FunctionalTester $I) {
        $I->wantTo('Validate order > payment listing api');
        $I->sendGET('v1/payment/' . $this->store->restaurant_uuid);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}
