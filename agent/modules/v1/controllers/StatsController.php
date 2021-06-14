<?php

namespace agent\modules\v1\controllers;

use agent\models\Item;
use common\models\Customer;
use common\models\Order;
use common\models\OrderItem;
use Yii;
use yii\db\Expression;
use yii\rest\Controller;

class StatsController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // remove authentication filter for cors to work
        unset($behaviors['authenticator']);

        // Allow XHR Requests from our different subdomains and dev machines
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
            'cors' => [
                'Origin' => Yii::$app->params['allowedOrigins'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => null,
                'Access-Control-Max-Age' => 86400,
                'Access-Control-Expose-Headers' => [
                    'X-Pagination-Current-Page',
                    'X-Pagination-Page-Count',
                    'X-Pagination-Per-Page',
                    'X-Pagination-Total-Count'
                ],
            ],
        ];

        // Bearer Auth checks for Authorize: Bearer <Token> header to login the user
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBearerAuth::className(),
        ];
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
            // optional:
            'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }

    /**
     * @return Array
     */
    public function actionView()
    {
        $interval = Yii::$app->request->get('interval');
        $type = Yii::$app->request->get('type');

        $store = Yii::$app->accountManager->getManagedAccount();

        $numberOfOrders = $store->getOrders()
            ->checkoutCompleted()
            ->count();

        //todo: check relation exists
        $itemsCount = $store->getItems()->count();

        $today_orders_received = $store->getOrders()
            ->checkoutCompleted()
            ->andWhere(new Expression("date(order_created_at) = date(NOW())"))
            ->count();

        $today_revenue_generated = $store->getOrders()
            ->checkoutCompleted()
            ->andWhere(new Expression("date(order_created_at) = date(NOW())"))
            ->sum('total_price');

        $today_customer_gained = $store->getCustomers()
            ->andWhere(new Expression("date(customer_created_at) = date(NOW())"))
            ->count();

        //todo: add model relation?
        $today_sold_items = $store->getOrderItems()
            ->joinWith('order')
            ->checkoutCompleted()
            ->andWhere(new Expression("date(order_created_at) = date(NOW())"))
            ->sum('order_item.qty');

        //last week

        $number_of_all_customer_gained = $store->getCustomers()
            ->andWhere(new Expression("date(customer_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
            ->count();

        $number_of_all_revenue_generated = $store->getOrders()
            ->checkoutCompleted()
            ->andWhere(new Expression("DATE(order.order_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
            ->sum('total_price');

        $number_of_all_sold_item = $store->getOrderItems()
            ->joinWith('order')
            ->checkoutCompleted()
            ->andWhere(new Expression("DATE(order.order_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
            ->sum('order_item.qty');

        $number_of_all_orders_received = $store->getOrders()
            ->checkoutCompleted()
            ->andWhere(new Expression("DATE(order.order_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
            ->count();

        //last-week - day
        //last-month - day
        //last-3-month - month

        $customer_chart_data = $store->getCustomers()
            ->select('count(*) as total')
            ->andWhere(new Expression("date(customer_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
            ->groupBy(new Expression('DAY(customer_created_at)'))
            ->asArray()
            ->all();

        $revenue_generated_chart_data = $store->getOrders()
            ->checkoutCompleted()
            ->select('total_price')
            ->andWhere(new Expression("DATE(order.order_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
            ->groupBy(new Expression('DAY(order.order_created_at)'))
            ->asArray()
            ->all();

        $orders_received_chart_data = $store->getOrders()
            ->checkoutCompleted()
            ->select('count(*) as total')
            ->andWhere(new Expression("DATE(order.order_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
            ->groupBy(new Expression('DAY(order.order_created_at)'))
            ->asArray()
            ->all();

        $sold_item_chart_data = $store->getOrderItems()
            ->joinWith('order')
            ->checkoutCompleted()
            ->select('order_item.qty')
            ->andWhere(new Expression("DATE(order.order_created_at) >= DATE(NOW() - INTERVAL 6 DAY)"))
            ->groupBy(new Expression('DAY(order.order_created_at)'))
            ->asArray()
            ->all();

        return [
            "number_of_all_customer_gained" => $number_of_all_customer_gained,
            "number_of_all_revenue_generated" => $number_of_all_revenue_generated,
            "number_of_all_sold_item" => $number_of_all_sold_item,
            "number_of_all_orders_received" => $number_of_all_orders_received,
            "customer_chart_data" => $customer_chart_data,
            "revenue_generated_chart_data" => $revenue_generated_chart_data,
            "orders_received_chart_data" => $orders_received_chart_data,
            "sold_item_chart_data" => $sold_item_chart_data,
            "numberOfOrders" => $numberOfOrders,
            "itemsCount" => $itemsCount,
            "today_customer_gained" => $today_customer_gained,
            "today_revenue_generated" => $today_revenue_generated,
            "today_sold_items" => $today_sold_items,
            "today_orders_received" => $today_orders_received
        ];
    }
}
