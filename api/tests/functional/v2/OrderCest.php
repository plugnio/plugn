<?php
namespace api\tests\v2;

use agent\models\PaymentMethod;
use api\models\Order;
use api\models\Restaurant;
use Codeception\Util\HttpCode;
use api\tests\FunctionalTester;
use common\models\Payment;


class OrderCest
{
    public $store;

    public function _fixtures() {
        return [
            'items' => \common\fixtures\ItemFixture::className(),
            'orders' => \common\fixtures\OrderFixture::className(),
            'restaurants' => \common\fixtures\RestaurantFixture::className(),
            'restaurantPaymentMethods' => \common\fixtures\RestaurantPaymentMethodFixture::className(),
            'restaurantBranches' => \common\fixtures\RestaurantBranchFixture::className(),
            'restaurantLocations' => \common\fixtures\BusinessLocationFixture::className(),
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
    public function tryToOrderDetail(FunctionalTester $I) {
        $model = $this->store->getOrders()->one();

        $I->wantTo('Validate order detail api');
        $I->sendGET('v2/order/order-details/' . $model->order_uuid .'/'. $this->store->restaurant_uuid);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToApplyBankDiscount(FunctionalTester $I) {

        $I->wantTo('Validate order apply bank discount api');

        $I->sendGET('v2/order/apply-bank-discount', [
            "restaurant_uuid" => $this->store->restaurant_uuid,
            "phone_number" => 345345345,
            "bank_name" => 'BOB'
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToApplyPromoCode(FunctionalTester $I) {

        $I->wantTo('Validate order apply promo code api');

        $I->sendGET('v2/order/apply-promo-code', [
            "restaurant_uuid" => $this->store->restaurant_uuid,
            "phone_number" => 345345345,
            "code" => 'BOB'
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToCheckPendingOrder(FunctionalTester $I)
    {
        $I->wantTo('Validate order > check for pending orders api');
        $I->sendGET('v2/order/check-for-pending-orders/' . $this->store->restaurant_uuid);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToStatusUpdateWebhook(FunctionalTester $I) {
        $order = $this->store->getOrders()->one();

        $I->wantTo('Validate order > status update webhook api');
        $I->sendPOST('v2/order/status-update-webhook', [
            "webhook_token" => "2125bf59e5af2b8c8b5e8b3b19f13e1221",
            "order_number" => $order->mashkor_order_number
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToPlaceOrder(FunctionalTester $I) {

        $item = $this->store->getItems()->one();

        $branch = $this->store->getRestaurantBranches()->one();

        $I->wantTo('Validate order > place an order api');
        $I->sendPOST('v2/order/' . $this->store->restaurant_uuid, [
            "customer_name" => "Bedardi khan",
            "phone_number" => 2342342342,
            "email" => 'demo@localhost.com',
            "payment_method_id" => 3,
            "order_mode" => Order::ORDER_MODE_PICK_UP,
            "is_order_scheduled" => false,
            "voucher_id" => null,
            "restaurant_branch_id" => $branch->restaurant_branch_id,
            "items" => [
                [
                    "item_uuid" => $item->item_uuid,
                    "qty" => 1,
                    "customer_instructions" => "sugar free"
                ]
            ]
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        /*$I->seeResponseContainsJson([
            "operation" => "success"
        ]);*/
    }

    /**
     * @param FunctionalTester $I
     *  TODO: find reason for knet source
     *  {"operation":"error","message":"Error: 1125 - We were unable to process your payment. Please verify your payment method or card details and try again.","code":15,"errors":[{"code":"1125","description":"We were unable to process your payment. Please verify your payment method or card details and try again."}]}
     */
    public function tryToPlaceOrderByKnet(FunctionalTester $I) {

        $item = $this->store->getItems()->one();

        $branch = $this->store->getRestaurantBranches()->one();

        $paymentMethod = $this->store->getPaymentMethods()
            ->andWhere(['payment_method_code' => \common\models\PaymentMethod::CODE_KNET])
            ->one();

        $businessLocation = $this->store->getBusinessLocations()->one();

        $I->wantTo('Validate order > place an order api');
        $I->sendPOST('v2/order/' . $this->store->restaurant_uuid, [
            "customer_name" => "Bedardi khan",
            "phone_number" => 2342342342,
            "email" => 'demo@localhost.com',
            "order_mode" => Order::ORDER_MODE_PICK_UP,
            "is_order_scheduled" => false,
            "voucher_id" => null,
            "currency" => "KWD",
            "restaurant_branch_id" => $branch->restaurant_branch_id,
            'business_location_id' => $businessLocation->business_location_id,
            "items" => [
                [
                    "item_uuid" => $item->item_uuid,
                    "qty" => 1,
                    "customer_instructions" => "sugar free"
                ]
            ],
            "payment_method_id" => $paymentMethod->payment_method_id,
            "source" => "src_all"
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            'operation' => 'redirecting',
        ]);
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToPlaceOrderByCOD(FunctionalTester $I) {

        $item = $this->store->getItems()->one();

        $branch = $this->store->getRestaurantBranches()->one();

        $paymentMethod = $this->store->getPaymentMethods()
            ->andWhere(['payment_method_code' => \common\models\PaymentMethod::CODE_CASH])
            ->one();

        $businessLocation = $this->store->getBusinessLocations()->one();

        $I->wantTo('Validate order > place an order api');
        $I->sendPOST('v2/order/' . $this->store->restaurant_uuid, [
            "customer_name" => "Bedardi khan",
            "phone_number" => 2342342342,
            "email" => 'demo@localhost.com',
            "order_mode" => Order::ORDER_MODE_PICK_UP,
            'business_location_id' => $businessLocation->business_location_id,
            "is_order_scheduled" => false,
            "voucher_id" => null,
            "restaurant_branch_id" => $branch->restaurant_branch_id,
            "items" => [
                [
                    "item_uuid" => $item->item_uuid,
                    "qty" => 1,
                    "customer_instructions" => "sugar free"
                ]
            ],
            "payment_method_id" => $paymentMethod->payment_method_id
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            "operation" => "success"
        ]);
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToPlaceOrderByFreecheckout(FunctionalTester $I) {

        $item = $this->store->getItems()->one();

        $branch = $this->store->getRestaurantBranches()->one();

        $item->item_price = 0;
        $item->save(false);

        $paymentMethod = $this->store->getPaymentMethods()
            ->andWhere(['payment_method_code' => \common\models\PaymentMethod::CODE_FREE_CHECKOUT])
            ->one();

        $businessLocation = $this->store->getBusinessLocations()->one();

        $I->wantTo('Validate order > place an order api');
        $I->sendPOST('v2/order/' . $this->store->restaurant_uuid, [
            "customer_name" => "Bedardi khan",
            "phone_number" => 2342342342,
            "email" => 'demo@localhost.com',
            "order_mode" => Order::ORDER_MODE_PICK_UP,
            "is_order_scheduled" => false,
            "voucher_id" => null,
            'payment_method_id' => $paymentMethod->payment_method_id,
            "restaurant_branch_id" => $branch->restaurant_branch_id,
            'business_location_id' => $businessLocation->business_location_id,
            "items" => [
                [
                    "item_uuid" => $item->item_uuid,
                    "qty" => 1,
                    "customer_instructions" => "sugar free"
                ]
            ]
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseContainsJson([
            "operation" => "success"
        ]);
    }

    /**
     * @param FunctionalTester $I
     */
    public function tryToStatusUpdateArmada(FunctionalTester $I) {
        $order = $this->store->getOrders()->one();

        $I->wantTo('Validate order > status update webhook api');
        $I->sendPOST('v2/order/update-armada-order-status', [
            "code" => $order->armada_delivery_code
        ]);
        $I->seeResponseCodeIs(HttpCode::OK); // 200
    }

    //'GET callback' => 'callback',
    //'GET my-fatoorah-callback' => 'my-fatoorah-callback',
}