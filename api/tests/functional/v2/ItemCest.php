<?php
namespace api\tests\v2;

use api\models\Restaurant;
use Codeception\Util\HttpCode;
use api\tests\FunctionalTester;

class ItemCest
{
    public $store;

    public function _fixtures()
    {
        return [
            'items' => \common\fixtures\ItemFixture::class,
            'categories' => \common\fixtures\CategoryFixture::class,
            'categoryItems' => \common\fixtures\CategoryItemFixture::class,
            'restaurants' => \common\fixtures\RestaurantFixture::class
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
    public function tryToListMenu(FunctionalTester $I)
    {
        $model = $this->store->getItems()->one();

        $I->wantTo('Validate item menu api');
        $I->sendGET('v2/item', [
            'restaurant_uuid' => $this->store->restaurant_uuid
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToViewDetail(FunctionalTester $I)
    {
        $model = $this->store->getItems()->one();

        $I->wantTo('Validate item > detail api');
        $I->sendGET('v2/item/detail', [
            'restaurant_uuid' => $this->store->restaurant_uuid,
            'item_uuid' => $model->item_uuid
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToListCategoryProducts(FunctionalTester $I)
    {
        $category = $this->store->getCategories()->one();

        $I->wantTo('Validate list category products api');
        $I->sendGET('v2/item/' . $category->category_id, [
            'restaurant_uuid' => $this->store->restaurant_uuid
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToListItems(FunctionalTester $I)
    {
        $I->wantTo('Validate list products api');
        $I->sendGET('v2/item/items', [
            'restaurant_uuid' => $this->store->restaurant_uuid
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}