<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\db\Expression;
use yii\rest\Controller;

class StatsController extends BaseController
{
    /**
     * only owner will have access
     */
    public function beforeAction($action)
    {
        parent::beforeAction ($action);

        if($action->id == 'options') {
            return true;
        }

        if(!Yii::$app->accountManager->isOwner()) {
            throw new \yii\web\BadRequestHttpException(
                Yii::t('agent', 'You are not allowed to view stats. Please contact with store owner')
            );

            return false;
        }

        //should have access to store

        Yii::$app->accountManager->getManagedAccount();

        return true;
    }

    /**
     * @return Array
     */
    public function actionView()
    {
        //todo: update queries and remove this 
        ini_set('memory_limit', '512M');

        $interval = Yii::$app->request->get('interval');
        $type = Yii::$app->request->get('type');

        $store = Yii::$app->accountManager->getManagedAccount();

        $numberOfOrders = $store->getOrders()
            ->activeOrders($store->restaurant_uuid)
            ->count();

        $itemsCount = $store->getItems()->count();

        $today_orders_received = $store->getOrders()
            ->activeOrders($store->restaurant_uuid)
            ->andWhere(new Expression("date(order_created_at) = date(NOW())"))
            ->count();

        $today_revenue_generated = $store->getOrders()
            ->activeOrders($store->restaurant_uuid)
            ->andWhere(new Expression("date(order_created_at) = date(NOW())"))
            ->sum('total_price');

        $today_customer_gained = $store->getCustomers()
            ->andWhere(new Expression("date(customer_created_at) = date(NOW())"))
            ->count();

        /*$today_sold_items = OrderItem::find()
            ->joinWith('order')
            ->activeOrders($store->restaurant_uuid)
            ->andWhere(new Expression("date(order_created_at) = date(NOW())"))
            ->sum('order_item.qty');*/

        $today_sold_items = $store->getSoldOrderItems()
            ->andWhere(new Expression("date(order_created_at) = date(NOW())"))
            ->sum('order_item.qty');

        switch ($interval) {
            case 'last-month':
                $customer_data = $store->getTotalCustomersByMonth();

                $revenue_data = $store->getTotalRevenueByMonth();

                $orders_data = $store->getTotalOrdersByMonth();

                $sold_item_data = $store->getTotalSoldItemsByMonth();

                break;

            case 'last-2-months':
                $customer_data = $store->getTotalCustomersByMonths(2);

                $revenue_data = $store->getTotalRevenueByMonths(2);

                $orders_data = $store->getTotalOrdersByMonths(2);

                $sold_item_data = $store->getTotalSoldItemsByMonths(2);

                break; 

            case 'last-3-months':
                $customer_data = $store->getTotalCustomersByMonths(3);

                $revenue_data = $store->getTotalRevenueByMonths(3);

                $orders_data = $store->getTotalOrdersByMonths(3);

                $sold_item_data = $store->getTotalSoldItemsByMonths(3);

                break;

            case 'last-5-months':
                #https://www.pivotaltracker.com/story/show/179023519
                $customer_data = $store->getTotalCustomersByMonths(5);

                $revenue_data = $store->getTotalRevenueByMonths(5);

                $orders_data = $store->getTotalOrdersByMonths(5);

                $sold_item_data = $store->getTotalSoldItemsByMonths(5);

                break;

            default:
                $customer_data = $store->getTotalCustomersByWeek();

                $revenue_data = $store->getTotalRevenueByWeek();

                $orders_data = $store->getTotalOrdersByWeek();

                $sold_item_data = $store->getTotalSoldItemsByWeek();
        }

        return array_merge (
            $customer_data,
            $revenue_data,
            $orders_data,
            $sold_item_data, [
                "most_sold_items" => $store->getMostSoldItems(),
                "currency_code" => $store->currency?$store->currency->code: null,
                "numberOfOrders" => (int) $numberOfOrders,
                "itemsCount" => (int) $itemsCount,
                "today_customer_gained" => (int) $today_customer_gained,
                "today_revenue_generated" => (float) $today_revenue_generated,//Yii::$app->formatter->asCurrency($today_revenue_generated, $currencyCode),//, [\NumberFormatter::MIN_FRACTION_DIGITS => 3, \NumberFormatter::MAX_FRACTION_DIGITS => 3]
                "today_sold_items" => (int) $today_sold_items,
                "today_orders_received" => (float)  $today_orders_received, //Yii::$app->formatter->asCurrency($today_orders_received, $currencyCode)//, [\NumberFormatter::MIN_FRACTION_DIGITS => 3, \NumberFormatter::MAX_FRACTION_DIGITS => 3]
            ]
        );
    }
}
