<?php
namespace api\tests\v1;

use api\models\Restaurant;
use api\tests\FunctionalTester;
use Codeception\Util\HttpCode;


class ItemCest
{
    public $store;

    public function _fixtures() {
        return [
            'categories' => \common\fixtures\CategoryFixture::className(),
            'items' => \common\fixtures\ItemFixture::className(),
            'restaurants' => \common\fixtures\RestaurantFixture::className()
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
    public function tryToListMenu(FunctionalTester $I) {
        $model = $this->store->getItems()->one();

        $I->wantTo('Validate item menu api');
        $I->sendGET('v1/item', [
            'restaurant_uuid' => $this->store->restaurant_uuid
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToViewDetail(FunctionalTester $I) {
        $model = $this->store->getItems()->one();

        $I->wantTo('Validate item > detail api');
        $I->sendGET('v1/item/detail', [
            'restaurant_uuid' => $this->store->restaurant_uuid,
            'item_uuid' => $model->item_uuid
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToListCategoryProducts(FunctionalTester $I) {
        $category = $this->store->getCategories()->one();

        $I->wantTo('Validate list category products api');
        $I->sendGET('v1/item/' . $category->category_id, [
            'restaurant_uuid' => $this->store->restaurant_uuid
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}
