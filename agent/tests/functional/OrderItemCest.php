<?php

namespace agent\tests;

use common\fixtures\AgentFixture;
use common\fixtures\AgentTokenFixture;
use common\fixtures\BankFixture;
use common\fixtures\OrderFixture;
use common\fixtures\OrderItemFixture;
use common\fixtures\RestaurantFixture;

class OrderItemCest
{
    public $token;
    public $agent;
    public $store;

    public function _fixtures() {
        return [
            'agents' => AgentFixture::className(),
            'orderItems' => OrderItemFixture::className(),
            'orders' => OrderFixture::className(),
            'restaurants' => RestaurantFixture::className(),
            'agentToken' => AgentTokenFixture::className()
        ];
    }

    public function _before(FunctionalTester $I) {

        $this->agent = Agent::find()->one();//['agent_email_verification'=>1]

        $this->store = $this->agent->getStores()->one();

        $this->token = $this->agent->getAccessToken()->token_value;

        $I->amBearerAuthenticated($this->token);

        $I->haveHttpHeader('Store-Id', $this->store->restaurant_uuid);
    }

    public function _after(FunctionalTester $I) {

    }

    public function tryToUpdate(FunctionalTester $I) {
        $model = $this->store->getOrderItems()->one();

        $I->wantTo('Validate order > update api');
        $I->sendPATCH('v1/order-item', [
            'order_uuid' => $model->order_uuid,
            'order_item_id' => $model->order_item_id,
            'qty' => 2,
            'customer_instructions' => 'test',
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToDelete(FunctionalTester $I) {
        $model = $this->store->getOrderItems()->one();

        $I->wantTo('Validate order > delete api');
        $I->sendDELETE('v1/order-item', [
            'order_uuid' => $model->order_uuid,
            'order_item_id' => $model->order_item_id
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}
