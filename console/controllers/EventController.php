<?php
namespace console\controllers;

use common\models\Refund;
use common\models\RestaurantDomainRequest;
use Yii;
use common\models\Restaurant;
use yii\db\Expression;
use yii\helpers\Console;
use \DateTime;

/**
 * Description of EventController
 *
 * @author krishna
 */
class EventController extends \yii\console\Controller {
    //put your code here
    
    public $event;
    
    public function options($actionID)
    {
        return array_merge(parent::options($actionID), ['event']);
    }

    public function syncAddonPurchase() {
        // \common\models\SubscriptionPayment
    }
    
    public function syncPlanPurchase() {
        // \common\models\SubscriptionPayment
    }

    public function syncAgentSignup() {

        $query = Agent::find();

        $count = 0;

        $total = Agent::find()
            ->count();

        Console::startProgress(0, $total);

        foreach($query->batch(100) as $agents) {

            $count += sizeof($agents);

            foreach ($agents as $agent) {

                $store = $agent->getAccountsManaged()->one();

                if(!$store)
                    continue;

                $datetime = new \DateTime($store->restaurant_created_at);

                $full_name = explode(' ', $agent->agent_name);
                $firstname = $full_name[0];
                $lastname = array_key_exists(1, $full_name) ? $full_name[1] : null;

                Yii::$app->eventManager->setUser($agent->agent_id, [
                    'name' => trim($agent->agent_name),
                    'email' => $agent->agent_email,
                ]);

                Yii::$app->eventManager->track('Agent Signup', [
                    'first_name' => trim ($firstname),
                    'last_name' => trim ($lastname),
                    'store_name' => $store->name,
                    'phone_number' => $store->owner_number,
                    'email' => $agent->agent_email,
                    'store_url' => $store->restaurant_domain,
                    "country" => $store->country? $store->country->country_name: null,
                    "campaign" => $store->sourceCampaign ? $store->sourceCampaign->utm_campaign: null,
                    "utm_medium" => $store->sourceCampaign ? $store->sourceCampaign->utm_medium: null,
                ],
                    $datetime->format('c'),
                    $agent->agent_id
                );
            }

            Console::updateProgress($count, $total);
        }

        Yii::$app->eventManager->flush();
    }

    public function syncDomainRequests() {

        $query = RestaurantDomainRequest::find();

        $count = 0;

        $total = RestaurantDomainRequest::find()
            ->count();

        Console::startProgress(0, $total);

        foreach($query->batch(100) as $restaurantDomainRequests) {

            $count += sizeof($restaurantDomainRequests);

            foreach ($restaurantDomainRequests as $restaurantDomainRequest) {

                $datetime = new \DateTime($restaurantDomainRequest->created_at);

                Yii::$app->eventManager->track('Domain Requests', [
                        "domain" => $restaurantDomainRequest->domain,
                    ],
                    $datetime->format('c'),
                    $restaurantDomainRequest->restaurant_uuid
                );
            }

            Console::updateProgress($count, $total);
        }

        Yii::$app->eventManager->flush();
    }

    public function syncStoreCreated() {
        
        $query = Restaurant::find();
            //->joinWith(['agents']);

        $count = 0;

        $total = Restaurant::find()
            ->count();

        Console::startProgress(0, $total);

        foreach($query->batch(100) as $stores) {

            $count += sizeof($stores);

            foreach ($stores as $store) {

                $agent = $store->getAgents()->one();
                
                if(!$agent)
                    continue;
                
                $datetime = new \DateTime($store->restaurant_created_at);

                $full_name = explode(' ', $agent->agent_name);
                $firstname = $full_name[0];
                $lastname = array_key_exists(1, $full_name) ? $full_name[1] : null;

                Yii::$app->eventManager->setUser($agent->agent_id, [
                    'name' => trim($agent->agent_name),
                    'email' => $agent->agent_email,                    
                ]);
                
                Yii::$app->eventManager->track('Store Created', [
                        'first_name' => trim($firstname),
                        'last_name' => trim($lastname),
                        'store_name' => $store->name,
                        'phone_number' => $store->owner_phone_country_code . $store->owner_number,
                        'email' => $agent->agent_email,
                        'store_url' => $store->restaurant_domain
                    ],
                    $datetime->format('c'),
                    $agent->agent_id
                );                
            }

            Console::updateProgress($count, $total);
        }

        Yii::$app->eventManager->flush();
    }
    
    public function syncOrderCompleted() {

        $query = Order::find()
            ->checkoutCompleted();

        $count = 0;

        $total = Order::find()
            ->checkoutCompleted()
            ->count();

        Console::startProgress(0, $total);

        foreach($query->batch(100) as $orders) {

            $count += sizeof($orders);

            foreach ($orders as $order) {

                $productsList = [];

                foreach ($order->orderItems as $orderedItem) {
                    $productsList[] = [
                        'product_id' => $orderedItem->item_uuid,
                        'sku' => $orderedItem->item->sku ? $orderedItem->item->sku : null,
                        'name' => $orderedItem->item_name,
                        'price' => $orderedItem->item_price,
                        'quantity' => $orderedItem->qty,
                        'url' => $order->restaurant->restaurant_domain . '/product/' . $orderedItem->item_uuid,
                    ];
                }

                $plugn_fee = 0;
                $payment_gateway_fee = 0;
                $plugn_fee_kwd = 0;

                //$total_price = $order->total_price;
                //$delivery_fee = $order->delivery_fee;
                //$subtotal = $order->subtotal;
                //$currency = $order->currency_code;

                $kwdCurrency = Currency::findOne(['code' => 'KWD']);

                //using store currency instead of user as user can have any currency but totals will be in store currency

                $rateKWD = $kwdCurrency->rate / $order->restaurant->currency->rate;

                $rate = 1 / $order->restaurant->currency->rate;// to USD

                if ($order->payment_uuid) {

                    $plugn_fee_kwd = ($order->payment->plugn_fee + $order->payment->partner_fee) * $rateKWD;

                    $plugn_fee = ($order->payment->plugn_fee + $order->payment->partner_fee) * $rate;

                    //$total_price = $total_price * $rate;
                    //$delivery_fee = $delivery_fee * $rate;
                    //$subtotal = $subtotal * $rate;
                    $payment_gateway_fee = $order->payment->payment_gateway_fee * $rate;
                }

                $datetime = new \DateTime($order->order_created_at);

                $order_total = $order->total_price * $rate;

                Yii::$app->eventManager->track('Order Completed', [
                    "restaurant_uuid" => $order->restaurant_uuid,
                    "store" => $order->restaurant->name,
                    "customer_name" => $order->customer_name,
                    "customer_email" => $order->customer_email,
                    "customer_id" => $order->customer_id,
                    "country" => $order->country_name,
                    'checkout_id' => $order->order_uuid,
                    'order_id' => $order->order_uuid,
                    'total' => $order_total,
                    'revenue' => $plugn_fee,
                    "store_revenue" => $order_total - $plugn_fee,
                    'gateway_fee' => $payment_gateway_fee,
                    'payment_method' => $order->payment_method_name,
                    'gateway' => $order->payment_method_name,// $order->payment_uuid ? 'Tap' : null,
                    'shipping' => ($order->delivery_fee * $rate),
                    'subtotal' => ($order->subtotal * $rate),
                    'currency' => $order->currency_code,
                    "cash" => $order->paymentMethod && $order->paymentMethod->payment_method_code == PaymentMethod::CODE_CASH?
                        ($order->total_price * $rate): 0,
                    'coupon' => $order->voucher && $order->voucher->code ? $order->voucher->code : null,
                    'products' => $productsList ? $productsList : null
                ],
                    $datetime,
                    $order->restaurant_uuid);
            }
        }
    }

    public function syncRefundProcessed() {

        $query = Refund::find()
            ->joinWith(['order'])
            ->where(new Expression('refund.refund_reference IS NOT NULL'));

        $count = 0;

        $total = Refund::find()
            ->joinWith(['order'])
            ->where(new Expression('refund.refund_reference IS NOT NULL'))
            ->count();

        Console::startProgress(0, $total);

        foreach($query->batch(100) as $refunds) {

            $count += sizeof($refunds);

            foreach ($refunds as $refund) {

                $datetime = new \DateTime($refund->refund_updated_at);

                $rate = 1;//default rate

                if (isset($refund->order->currency)) {
                    $rate = 1 / $refund->order->currency->rate;// to USD
                }

                Yii::$app->eventManager->track('Refunds Processed', array_merge($refund, [
                        'refund_amount' => $refund->refund_amount,
                        'value' => $refund->refund_amount * $rate,
                        'currency' => 'USD'
                    ]),
                    $datetime,
                    $refund->restaurant_uuid);
            }
        }
    }

    public function syncOrderInitiated() {

        $query = Order::find();

        $count = 0;

        $total = Order::find()
            ->count();

        Console::startProgress(0, $total);

        foreach($query->batch(100) as $orders) {

            $count += sizeof($orders);

            foreach ($orders as $order) {

                $datetime = new \DateTime($order->order_created_at);

                Yii::$app->eventManager->track('Order Initiated', $order,
                    $datetime,
                    $order->restaurant_uuid
                );
            }
        }
    }

    /**
     * sync suggestion with segment
     */
    public function actionEmulate() {

        switch ($this->event) {
            case "Addon Purchase":
                $this->syncAddonPurchase();
                break;
            case "Premium Plan Purchase":
                $this->syncPlanPurchase();
                break;
            case "Store Created":
                $this->syncStoreCreated();
                break;
            case "Order Initiated":
                $this->syncOrderInitiated();
                break;
            case "Order Completed":
                $this->syncOrderCompleted();
                break;
            case "Agent Signup": 
                $this->syncAgentSignup();
                break;
            case "Domain Requests":
                $this->syncDomainRequests();
                break;
            case "Refunds Processed":
                $this->syncRefundProcessed();
                break;
            default:
                $this->stdout("Missing event name \n", Console::FG_RED, Console::BOLD);
                //throwException("Missing event name");
        }
    }
}
//to sync
/*
- Order Initiated
- Agent Signup
- Domain Requests
- Refunds Processed
- */