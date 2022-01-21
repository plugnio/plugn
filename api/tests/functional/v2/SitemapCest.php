<?php
namespace api\tests\v2;

use api\models\Restaurant;
use Codeception\Util\HttpCode;
use api\tests\FunctionalTester;

class SitemapCest
{
    public $store;

    public function _fixtures() {
        return [
            'items' => \common\fixtures\ItemFixture::className(),
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
     * @param FunctionalTester $I
     */
    public function tryToListOpeningHours (FunctionalTester $I) {
        $I->wantTo('Validate restaurant > get opening hours api');
        $I->sendGET('v2/sitemap/' . $this->store->restaurant_uuid);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}
