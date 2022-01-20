<?php

namespace agent\tests;

use agent\models\Agent;
use agent\models\Order;
use Codeception\Util\HttpCode;
use common\fixtures\AgentAssignmentFixture;
use common\fixtures\AgentFixture;
use common\fixtures\AgentTokenFixture;
use common\fixtures\AreaFixture;
use common\fixtures\CountryFixture;
use common\fixtures\CurrencyFixture;
use common\fixtures\DeliveryZoneFixture;
use common\fixtures\OpeningHourFixture;
use common\fixtures\OrderFixture;
use common\fixtures\OrderItemFixture;
use common\fixtures\RestaurantFixture;

class OrderCest
{
    public $token;
    public $agent;
    public $store;

    public function _fixtures() {
        return [
            'agents' => AgentFixture::className(),
            'orders' => OrderFixture::className(),
            'orderItems' => OrderItemFixture::className(),
            'currencies' => CurrencyFixture::className(),
            'agent_assignments' => AgentAssignmentFixture::className(),
            'areas' => AreaFixture::className(),
            'countries' => CountryFixture::className(),
            'deliveryZones' => DeliveryZoneFixture::className(),
            'restaurants' => RestaurantFixture::className(),
            'agentToken' => AgentTokenFixture::className()
        ];
    }

    public function _before(FunctionalTester $I) {

        $this->agent = Agent::find()->one();//['agent_email_verification'=>1]

        $this->store = $this->agent->getAccountsManaged()->one();

        $this->token = $this->agent->getAccessToken()->token_value;

        $I->amBearerAuthenticated($this->token);

        $I->haveHttpHeader('Store-Id', $this->store->restaurant_uuid);
    }

    public function _after(FunctionalTester $I) {

    }

    public function tryToList(FunctionalTester $I) {
        $I->wantTo('Validate order > list api');
        $I->sendGET('v1/order');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToDetail(FunctionalTester $I) {
        $model = Order::find()->one();

        $I->wantTo('Validate order > detail api');
        $I->sendGET('v1/order/detail', [
            'order_uuid' => $model->order_uuid
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToStats(FunctionalTester $I) {
        $I->wantTo('Validate order > stats api');
        $I->sendGET('v1/order/stats');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /*public function tryToOrderReport(FunctionalTester $I) {
        $I->wantTo('Validate order > orders-report api');
        $I->sendGET('v1/order/orders-report', [
            'from' => date('Y-m-d', strtotime('-1 month')),
            'to' => date('Y-m-d')
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }*/

    public function tryToGetTotalPending(FunctionalTester $I) {
        $I->wantTo('Validate order > total pending api');
        $I->sendGET('v1/order/total-pending');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToDownloadInvoice(FunctionalTester $I) {
        $model = $this->store->getOrders()->one();

        $I->wantTo('Validate order > download invoice api');
        $I->sendGET('v1/order/download-invoice/'. $model->order_uuid);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToArchiveOrders(FunctionalTester $I) {
        $I->wantTo('Validate order > archive orders api');
        $I->sendGET('v1/order/archive-orders');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToLiveOrders(FunctionalTester $I) {
        $I->wantTo('Validate order > live orders api');
        $I->sendGET('v1/order/live-orders');
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * 1) kuwait
     * 2) non kuwait
     * 3) pickup from store
     * @param FunctionalTester $I
     */
    public function tryToCreate(FunctionalTester $I) {
        $dz = $this->store->getDeliveryZones()->one();

        $item = $this->store->getItems()->one();

        $I->wantTo('Validate order > place order api');
        $I->sendPOST('v1/order', [
            'customer_name' => 'Don zoe',
            'phone_number' => 8172893123,
            'country_code' => 91,
            'email' => 'demo@ocalhost.com',
            'currency_code' => 'INR',
            'order_mode' => 1,
            'is_order_scheduled' => 0,
            'country_id' => 1,
            'area_id' => 1,
            'delivery_zone_id' => $dz->delivery_zone_id,
            'shipping_country_id' => 1,
            'address_1' => 'address 1',
            'address_2' => 'address 2',
            'postalcode' => 124124,
            'city' => 'vadodara',
            'items' => [
                "item_uuid" => $item->item_uuid,
                "qty" => 1
            ]
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdate(FunctionalTester $I) {
        $dz = $this->store->getDeliveryZones()->one();

        $item = $this->store->getItems()->one();

        $order = $this->store->getOrders()->one();

        $I->wantTo('Validate order > update order api');
        $I->sendPOST('v1/order/' . $order->order_uuid, [
            'customer_name' => 'Don zoe',
            'phone_number' => 8172893123,
            'country_code' => 91,
            'email' => 'demo@ocalhost.com',
            'currency_code' => 'INR',
            'order_mode' => 1,
            'is_order_scheduled' => 0,
            'country_id' => 1,
            'area_id' => 1,
            'delivery_zone_id' => $dz->delivery_zone_id,
            'shipping_country_id' => 1,
            'address_1' => 'address 1',
            'address_2' => 'address 2',
            'postalcode' => 124124,
            'city' => 'vadodara',
            'items' => [
                "item_uuid" => $item->item_uuid,
                "qty" => 1
            ]
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToDelete(FunctionalTester $I) {
        $order = $this->store->getOrders()->one();

        $I->wantTo('Validate order > delete order api');
        $I->sendDELETE('v1/order/' . $order->order_uuid);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToSoftDelete(FunctionalTester $I) {
        $order = $this->store->getOrders()->one();

        $I->wantTo('Validate order > soft delete order api');
        $I->sendDELETE('v1/order/soft-delete/' . $order->order_uuid);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToRequestDriverFromMashkor(FunctionalTester $I) {
        $order = $this->store->getOrders()->one();

        $I->wantTo('Validate order > request driver from mashkor api');
        $I->sendPOST('v1/order/request-driver-from-mashkor/' . $order->order_uuid);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToRequestDriverFromArmada(FunctionalTester $I) {
        $order = $this->store->getOrders()->one();

        $I->wantTo('Validate order > request driver from armada api');
        $I->sendPOST('v1/order/request-driver-from-armada/' . $order->order_uuid);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToUpdateStatus(FunctionalTester $I) {
        $order = $this->store->getOrders()->one();

        $I->wantTo('Validate order > update order status api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPATCH('v1/order/update-order-status/' . $order->order_uuid, [
            'order_status' => Order::STATUS_ACCEPTED
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    public function tryToRefund(FunctionalTester $I) {
        $order = $this->store->getOrders()->one();

        $orderItem = $order->getOrderItems()->one();

        $I->wantTo('Validate order > refund order api');
        $I->haveHttpHeader('Content-Type', 'application/x-www-form-urlencoded');
        $I->sendPATCH('v1/order/refund/' . $order->order_uuid, [
            'refund_amount' => 1,
            'itemsToRefund' => [
                [
                    'order_item_id' => $orderItem->order_item_id,
                    'qty' => 1
                ]
            ]
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }
}