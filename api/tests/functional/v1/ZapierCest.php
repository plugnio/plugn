<?php
namespace api\tests\v1;

use agent\models\Agent;
use api\tests\FunctionalTester;
use api\models\Restaurant;
use Codeception\Util\HttpCode;


class ZapierCest
{
    public $store;

    public function _fixtures() {
        return [
            'agents' => \common\fixtures\AgentFixture::className(),
            'agentAssignments' => \common\fixtures\AgentAssignmentFixture::className(),
            'orders' => \common\fixtures\OrderFixture::className(),
            'restaurants' => \common\fixtures\RestaurantFixture::className()
        ];
    }

    /**
     * @param FunctionalTester $I
     */
    public function _before(FunctionalTester $I) {

        $agent = Agent::find()->one();

        $this->store = $agent->getAccountsManaged()->one();

        $I->haveHttpHeader('Store-Id', $this->store->restaurant_uuid);

        $I->amHttpAuthenticated($agent->agent_email, '12345');
    }

    public function _after(FunctionalTester $I) {

    }

    /**
     * try to list stores
     * @param FunctionalTester $I
     */
    public function tryToListStores(FunctionalTester $I) {
        $I->wantTo('Validate restaurant > get store list api');
        $I->sendGET('v1/zapier/get-store-list');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToListOrders(FunctionalTester $I) {
        $I->wantTo('Validate restaurant > get latest order listing api');
        $I->sendGET('v1/zapier/get-latest-order/'. $this->store->restaurant_uuid);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}
