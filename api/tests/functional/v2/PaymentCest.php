<?php
namespace api\tests\v2;

use api\models\Restaurant;
use Codeception\Util\HttpCode;
use api\tests\FunctionalTester;

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
        $I->sendGET('v2/payment/' . $this->store->restaurant_uuid);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * @param FunctionalTester $I
     *
    public function tryToStatusUpdateFatoorah(FunctionalTester $I) {
        $I->wantTo('Validate order > status update webhook api');
        $I->sendPOST('v2/payment/status-update-webhook', [
            "Data" => null,
            "EventType" => 1,
            "CountryIsoCode" => 'KWT'
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }*/
}