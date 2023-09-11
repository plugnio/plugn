<?php
namespace backend\controllers;

use agent\models\Country;
use agent\models\RestaurantPaymentMethod;
use backend\components\ChartWidget;
use backend\models\Admin;
use backend\models\CountrySearch;
use common\models\Payment;
use common\models\Restaurant;
use common\models\RestaurantDomainRequest;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Exp;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Customer;
use common\models\Order;
use common\models\Item;
use yii\db\Expression;
use yii\web\Response;


/**
 * Stats controller
 */
class StatsController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'graph', 'store-retention', 'graph-fees', 'graph-stores', 'graph-orders',
                            'customer-funnel', 'sales', 'payment-gateways', 'graph-customers', 'countries', 'domain', 'clear-cache'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays stats
     *
     * @return string
     */
    public function actionIndex()
    {
        $date_start = Yii::$app->request->get('date_start');
        $date_end = Yii::$app->request->get('date_end');
        $country_id = Yii::$app->request->get('country_id');

        $cacheDuration = 60 * 60 * 24;// 1 day then delete from cache

        $orderCacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM `order`',
        ]);

        $storeCacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM restaurant',
        ]);

        /*$numberOfOrders = Order::find()
            ->activeOrders()
            ->count();

        $itemsCount = Item::find()->count();

        $today_orders_received = Order::find()
            ->activeOrders()
            ->andWhere(new Expression("date(order_created_at) = date(NOW())"))
            ->count();

        $today_revenue_generated = Order::find()
            ->activeOrders()
            ->andWhere(new Expression("date(order_created_at) = date(NOW())"))
            ->sum('total_price');

        $today_customer_gained = Customer::find()
            ->andWhere(new Expression("date(customer_created_at) = date(NOW())"))
            ->count();

        $today_sold_items = OrderItem::find()
            ->joinWith('order')
            ->activeOrders($store->restaurant_uuid)
            ->andWhere(new Expression("date(order_created_at) = date(NOW())"))
            ->sum('order_item.qty');

        $today_sold_items = $store->getSoldOrderItems()
            ->andWhere(new Expression("date(order_created_at) = date(NOW())"))
            ->sum('order_item.qty');*/

        //Yii::$app->response->format = \yii\web\Response::FORMAT_XML;

        $totalOrders = Order::getDb()->cache(function($db) use($country_id, $date_start, $date_end) {

            return Order::find()
                ->filterByCountry($country_id)
                ->activeOrders()
                ->filterByDateRange($date_start, $date_end)
                ->count();

        }, $cacheDuration, $orderCacheDependency);

        $totalPremium = Restaurant::getDb()->cache(function($db) use($country_id, $date_start, $date_end) {

            return Restaurant::find()
                ->filterByCountry($country_id)
                ->filterByDateRange($date_start, $date_end)
                ->filterPremium()
                ->count();

        }, $cacheDuration, $storeCacheDependency);

        $totalStores = Restaurant::getDb()->cache(function($db) use($country_id, $date_start, $date_end) {

            return Restaurant::find()
                ->filterByCountry($country_id)
                ->filterByDateRange($date_start, $date_end)
                ->count();

        }, $cacheDuration, $storeCacheDependency);

        $totalFreeStores = $totalStores - $totalPremium;

        $inActiveStores = Restaurant::getDb()->cache(function($db) use($country_id, $date_start, $date_end) {

            return Restaurant::find()
                ->filterByCountry($country_id)
                ->filterByNoOrderIn30Days()
                ->filterByDateRange($date_start, $date_end)
                ->count();

        }, $cacheDuration, $storeCacheDependency);

        $activeStores = $totalStores - $inActiveStores;

        $totalStoresWithPaymentGateway = Restaurant::getDb()->cache(function($db) use($country_id, $date_start, $date_end) {

            return Restaurant::find()
                ->filterByCountry($country_id)
                ->filterByDateRange($date_start, $date_end)
                ->filterStoresWithPaymentGateway()
                ->count();

        }, $cacheDuration, $storeCacheDependency);

        $revenues = Order::getDb()->cache(function($db) use($country_id, $date_start, $date_end) {

            return Order::find()
                ->filterByDateRange($date_start, $date_end)
                ->select(new Expression("currency_code, SUM(total_price) as total_price"))
                ->filterByCountry($country_id)
                ->activeOrders()
                ->groupBy('order.currency_code')
                ->asArray()
                ->all();

        }, $cacheDuration, $orderCacheDependency);

        //Our profit margin and payment gateway margin separated from that revenue

        $countries = ArrayHelper::map(Country::find()->all(), 'country_id', 'country_name');
        $countries = ["0" => "All"] + $countries;

       // array_unshift($countries, "All");//

        return $this->render('index', [
            "date_start" => $date_start,
            "date_end" => $date_end,
                 "inActiveStores" => $inActiveStores,
                 "activeStores" => $activeStores,
                 "country_id" => $country_id,
                 "countries" => $countries,
                "totalOrders" => $totalOrders,
                "revenues" => $revenues,
                "totalStores" => $totalStores,
                "totalPremium" => $totalPremium,
                "totalFreeStores" => $totalFreeStores,
                "totalStoresWithPaymentGateway" => $totalStoresWithPaymentGateway,
                //"most_sold_items" => $store->getMostSoldItems(),
                "currency_code" => "KWD",
        ]);
    }

    public function actionDomain() {

        $date_start = Yii::$app->request->get('date_start');
        $date_end = Yii::$app->request->get('date_end');
        $country_id = Yii::$app->request->get('country_id');

        $cacheDuration = 60 * 60 * 24;// 1 day then delete from cache

        $storeCacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM restaurant',
        ]);

        $totalPlugnDomain = Restaurant::getDb()->cache(function($db) use($country_id, $date_start, $date_end) {

            return Restaurant::find()
                ->filterByCountry($country_id)
                ->filterByDateRange($date_start, $date_end)
                ->filterPlugnDomain()
                ->count();

        }, $cacheDuration, $storeCacheDependency);

        $totalCustomDomain = Restaurant::getDb()->cache(function($db) use($country_id, $date_start, $date_end) {

            return Restaurant::find()
                ->filterByCountry($country_id)
                ->filterByDateRange($date_start, $date_end)
                ->filterCustomDomain()
                ->count();

        }, $cacheDuration, $storeCacheDependency);

        $totalDomainRequests = RestaurantDomainRequest::find()
            //->filterByCountry($country_id)
            ->filterByDateRange($date_start, $date_end)
            ->count();

        $pendingDomainRequests = RestaurantDomainRequest::find()
            //->filterByCountry($country_id)
            ->filterByDateRange($date_start, $date_end)
            ->andWhere(['status' => RestaurantDomainRequest::STATUS_PENDING])
            ->count();

        //Our profit margin and payment gateway margin separated from that revenue

        $countries = ArrayHelper::map(Country::find()->all(), 'country_id', 'country_name');
        $countries = ["0" => "All"] + $countries;

        // array_unshift($countries, "All");//

        return $this->render('domain', [
            "date_start" => $date_start,
            "date_end" => $date_end,
            "country_id" => $country_id,
            "countries" => $countries,
            "totalPlugnDomain" => $totalPlugnDomain,
            "totalCustomDomain" => $totalCustomDomain,
            'totalDomainRequests' => $totalDomainRequests,
            'pendingDomainRequests' => $pendingDomainRequests,
            "currency_code" => "KWD",
        ]);
    }

    public function actionPaymentGateways()
    {
        $date_start = Yii::$app->request->get('date_start');
        $date_end = Yii::$app->request->get('date_end');
        $country_id = Yii::$app->request->get('country_id');

        $cacheDuration = 60 * 60 * 24;// 1 day then delete from cache

        $storeCacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM restaurant',
        ]);

        $storesByPaymentMethods = Restaurant::getDb()->cache(function($db) use($country_id, $date_start, $date_end) {

            return  RestaurantPaymentMethod::find()
                ->joinWith(['paymentMethod'])
                ->select(new Expression('payment_method.payment_method_name, COUNT(*) as total'))
                ->filterByCountry($country_id)
                ->filterActive()
                ->groupBy('restaurant_payment_method.payment_method_id')
                ->asArray()
                ->all();

        }, $cacheDuration, $storeCacheDependency);

        $totalTapStores = Restaurant::getDb()->cache(function($db) use($country_id, $date_start, $date_end) {

            return Restaurant::find()
                ->filterByDateRange($date_start, $date_end)
                ->filterByCountry($country_id)
                ->andWhere(['is_tap_enable' => 1])
                ->count();

        }, $cacheDuration, $storeCacheDependency);

        $countries = ArrayHelper::map(Country::find()->all(), 'country_id', 'country_name');
        $countries = ["0" => "All"] + $countries;

        return $this->render('payment-gateways', [
            "totalTapStores" => $totalTapStores,
            "storesByPaymentMethods" => $storesByPaymentMethods,
            "date_start" => $date_start,
            "date_end" => $date_end,
            "country_id" => $country_id,
            "countries" => $countries
        ]);
    }

    public function actionCountries()
    {
        $date_start = Yii::$app->request->get('date_start');
        $date_end = Yii::$app->request->get('date_end');

        $searchModel = new CountrySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('countries', [
            "date_start" => $date_start,
            "date_end" => $date_end,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    public function actionSales()
    {
        $date_start = Yii::$app->request->get('date_start');
        $date_end = Yii::$app->request->get('date_end');
        $country_id = Yii::$app->request->get('country_id');

        $cacheDuration = 60 * 60 * 24;// 1 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM payment',
        ]);

        $payments = Payment::getDb()->cache(function($db) use($country_id, $date_start, $date_end) {

            return Payment::find()
                ->filterByDateRange($date_start, $date_end)
                ->select(new Expression("currency_code, SUM(payment_net_amount) as payment_net_amount, 
                SUM(payment_gateway_fee) as payment_gateway_fees, 
                SUM(plugn_fee) as plugn_fees, SUM(partner_fee) as partner_fees"))
                ->joinWith(['order'])
                ->filterByCountry($country_id)
                ->filterPaid()
                ->groupBy('order.currency_code')
                ->asArray()
                ->all();

        }, $cacheDuration, $cacheDependency);

        $countries = ArrayHelper::map(Country::find()->all(), 'country_id', 'country_name');
        $countries = ["0" => "All"] + $countries;

        return $this->render('sales', [
            "payments" => $payments,
            "date_start" => $date_start,
            "date_end" => $date_end,
            "country_id" => $country_id,
            "countries" => $countries
        ]);
    }

    public function actionStoreRetention()
    {
        $date_start = Yii::$app->request->get('date_start');
        $date_end = Yii::$app->request->get('date_end');
        $country_id = Yii::$app->request->get('country_id');

        $cacheDuration = 60 * 60 * 24;// 1 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM restaurant',
        ]);

        $totalStores = Restaurant::getDb()->cache(function($db) use($country_id, $date_start, $date_end) {

            return Restaurant::find()
                ->filterByCountry($country_id)
                ->filterByDateRange($date_start, $date_end)
                ->count();

        }, $cacheDuration, $cacheDependency);

        $inActiveStores = Restaurant::getDb()->cache(function($db) use($country_id, $date_start, $date_end) {

            return Restaurant::find()
                ->filterByCountry($country_id)
                ->filterByNoOrderInDays(15)
                ->filterByDateRange($date_start, $date_end)
                ->count();

        }, $cacheDuration, $cacheDependency);

        $countries = ArrayHelper::map(Country::find()->all(), 'country_id', 'country_name');
        $countries = ["0" => "All"] + $countries;

        return $this->render('store-retention', [
            "totalStores" => $totalStores,
            "inActiveStores" => $inActiveStores,
            "date_start" => $date_start,
            "date_end" => $date_end,
            "country_id" => $country_id,
            "countries" => $countries
        ]);
    }

    public function actionCustomerFunnel()
    {
        $date_start = Yii::$app->request->get('date_start');
        $date_end = Yii::$app->request->get('date_end');
        $country_id = Yii::$app->request->get('country_id');

        $cacheDuration = 60 * 60 * 24;// 1 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM `order`',
        ]);

        $totalOrders = Order::getDb()->cache(function($db) use($country_id, $date_start, $date_end) {

            return Order::find()
                ->filterByCountry($country_id)
                ->checkoutCompleted()
                ->filterByDateRange($date_start, $date_end)
                ->count();

        }, $cacheDuration, $cacheDependency);

        $completedOrders = Order::getDb()->cache(function($db) use($country_id, $date_start, $date_end) {

            return Order::find()
                ->filterByCountry($country_id)
                ->filterCompleted()
                ->filterByDateRange($date_start, $date_end)
                ->count();

        }, $cacheDuration, $cacheDependency);

        $countries = ArrayHelper::map(Country::find()->all(), 'country_id', 'country_name');
        $countries = ["0" => "All"] + $countries;

        return $this->render('customer-funnel', [
            "totalOrders" => $totalOrders,
            "completedOrders" => $completedOrders,
            "date_start" => $date_start,
            "date_end" => $date_end,
            "country_id" => $country_id,
            "countries" => $countries
        ]);
    }

    public function actionGraph()
    {
        $interval = Yii::$app->request->get('interval');
        $type = Yii::$app->request->get('type');

        switch ($interval) {
            case 'last-month':
                $store_data = Restaurant::getTotalStoresByMonth();

                $customer_data = Customer::getTotalCustomersByMonth();

                $revenue_data = Order::getTotalRevenueByMonth();

                $orders_data = Order::getTotalOrdersByMonth();

                $fees_data = Payment::getTotalFeesByMonth();

                break;

            case 'last-2-months':
                $store_data = Restaurant::getTotalStoresByMonths(2);

                $customer_data = Customer::getTotalCustomersByMonths(2);

                $revenue_data = Order::getTotalRevenueByMonths(2);

                $orders_data = Order::getTotalOrdersByMonths(2);

                $fees_data = Payment::getTotalFeesByMonths(2);

                break;

            case 'last-3-months':
                $store_data = Restaurant::getTotalStoresByMonths(3);

                $customer_data = Customer::getTotalCustomersByMonths(3);

                $revenue_data = Order::getTotalRevenueByMonths(3);

                $orders_data = Order::getTotalOrdersByMonths(3);

                $fees_data = Payment::getTotalFeesByMonths(3);

                break;

            case 'last-5-months':
                $store_data = Restaurant::getTotalStoresByMonths(5);

                #https://www.pivotaltracker.com/story/show/179023519
                $customer_data = Customer::getTotalCustomersByMonths(5);

                $revenue_data = Order::getTotalRevenueByMonths(5);

                $orders_data = Order::getTotalOrdersByMonths(5);

                $fees_data = Payment::getTotalFeesByMonths(5);

                break;

            case 'last-12-months':
                $store_data = Restaurant::getTotalStoresByMonths(12);

                #https://www.pivotaltracker.com/story/show/179023519
                $customer_data = Customer::getTotalCustomersByMonths(12);

                $revenue_data = Order::getTotalRevenueByMonths(12);

                $orders_data = Order::getTotalOrdersByMonths(12);

                $fees_data = Payment::getTotalFeesByMonths(12);
                break;

            default:
                $store_data = Restaurant::getTotalStoresByWeek(5);

                $customer_data = Customer::getTotalCustomersByWeek();

                $revenue_data = Order::getTotalRevenueByWeek();

                $orders_data = Order::getTotalOrdersByWeek();

                $fees_data = Payment::getTotalFeesByWeek();
        }

        //Yii::$app->response->format = \yii\web\Response::FORMAT_XML;

        $countries = Country::find()
            ->select('iso, count(*) as total')
            ->joinWith(['restaurants'])
            ->groupBy('country.country_id')
            ->asArray()
            ->all();

        $storeByCountry = ArrayHelper::map($countries, 'iso', 'total');

        return $this->render('graphs', array_merge (
            $store_data,
            $customer_data,
            $revenue_data,
            $fees_data,
            $orders_data, [
                "storeByCountry" => $storeByCountry,
                "currency_code" => "KWD"
             ]
        ));
    }

    public function actionGraphFees() {

        $interval = Yii::$app->request->post('interval');

        $fees_data = Payment::getTotalFeesByInterval($interval);

        Yii::$app->response->format = Response::FORMAT_JSON;

        $categories = [];

        $seriesData = [];

        foreach ($fees_data["plugn_fee_chart_data"] as $row) {
            if (isset($row['month'])) {
                $categories[] = $row['month'];
            } else if (isset($row['day'])) {
                $categories[] = $row['day'];
            } else if (isset($row['item_name'])) {
                $categories = $row['item_name'];
            }

            $seriesData[] = $row['total'];
        }

        return [
            "categories" => $categories,
            "seriesData" => $seriesData
        ];
    }

    public function actionGraphCustomers() {

        $interval = Yii::$app->request->post('interval');

        $fees_data = Customer::getTotalCustomersByInterval($interval);

        Yii::$app->response->format = Response::FORMAT_JSON;

        $categories = [];

        $seriesData = [];

        foreach ($fees_data["customer_chart_data"] as $row) {
            if (isset($row['month'])) {
                $categories[] = $row['month'];
            } else if (isset($row['day'])) {
                $categories[] = $row['day'];
            } else if (isset($row['item_name'])) {
                $categories = $row['item_name'];
            }

            $seriesData[] = $row['total'];
        }

        return [
            "categories" => $categories,
            "seriesData" => $seriesData
        ];
    }

    public function actionGraphStores() {

        $interval = Yii::$app->request->post('interval');

        $fees_data = Restaurant::getTotalStoresByInterval($interval);

        Yii::$app->response->format = Response::FORMAT_JSON;

        $categories = [];

        $seriesData = [];

        foreach ($fees_data["store_created_chart_data"] as $row) {
            if (isset($row['month'])) {
                $categories[] = $row['month'];
            } else if (isset($row['day'])) {
                $categories[] = $row['day'];
            } else if (isset($row['item_name'])) {
                $categories = $row['item_name'];
            }

            $seriesData[] = $row['total'];
        }

        return [
            "categories" => $categories,
            "seriesData" => $seriesData
        ];
    }

    public function actionGraphOrders() {

        $interval = Yii::$app->request->post('interval');

        $fees_data = Order::getTotalOrdersByInterval($interval);

        Yii::$app->response->format = Response::FORMAT_JSON;

        $categories = [];

        $seriesData = [];

        foreach ($fees_data["orders_received_chart_data"] as $row) {
            if (isset($row['month'])) {
                $categories[] = $row['month'];
            } else if (isset($row['day'])) {
                $categories[] = $row['day'];
            } else if (isset($row['item_name'])) {
                $categories = $row['item_name'];
            }

            $seriesData[] = $row['total'];
        }

        return [
            "categories" => $categories,
            "seriesData" => $seriesData
        ];
    }

    public function actionClearCache() {

        if(Yii::$app->cache)
            Yii::$app->cache->flush();

        Yii::$app->session->addFlash("success", "All cache cleared!");

        $this->goBack(['site/index']);
    }
}