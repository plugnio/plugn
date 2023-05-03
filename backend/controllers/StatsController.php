<?php
namespace backend\controllers;

use agent\models\Country;
use common\models\Payment;
use common\models\Restaurant;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Exp;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Customer;
use common\models\Order;
use common\models\Item;
use yii\db\Expression;


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
                        'actions' => ['index', 'graph'],
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
        $numberOfOrders = Order::find()
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

        /*$today_sold_items = OrderItem::find()
            ->joinWith('order')
            ->activeOrders($store->restaurant_uuid)
            ->andWhere(new Expression("date(order_created_at) = date(NOW())"))
            ->sum('order_item.qty');

        $today_sold_items = $store->getSoldOrderItems()
            ->andWhere(new Expression("date(order_created_at) = date(NOW())"))
            ->sum('order_item.qty');*/

        //Yii::$app->response->format = \yii\web\Response::FORMAT_XML;

        $totalOrders = Order::find()
            ->activeOrders()
            ->count();

        $totalPremium = Restaurant::find()
            ->filterPremium()
            ->count();

        $totalStores = Restaurant::find()->count();

        $totalFreeStores = $totalStores - $totalPremium;

        $totalStoresWithPaymentGateway = Restaurant::find()
            ->filterStoresWithPaymentGateway()
            ->count();

        $totalPlugnDomain = Restaurant::find()
            ->filterPlugnDomain()
            ->count();

        $totalCustomDomain = Restaurant::find()
            ->filterCustomDomain()
            ->count();

        $payments = Payment::find()
            ->select(new Expression("currency_code, SUM(payment_gateway_fee) as payment_gateway_fees, 
                SUM(plugn_fee) as plugn_fees, SUM(partner_fee) as partner_fees"))
            ->joinWith(['order'])
            ->filterPaid()
            ->groupBy('order.currency_code')
            ->asArray()
            ->all();

        $revenues = Order::find()
            ->select(new Expression("currency_code, SUM(total_price) as total_price"))
            ->activeOrders()
            ->groupBy('order.currency_code')
            ->asArray()
            ->all();

        //Our profit margin and payment gateway margin separated from that revenue

        return $this->render('index', [
                 "payments" => $payments,
                "totalOrders" => $totalOrders,
                "revenues" => $revenues,
                "totalPremium" => $totalPremium,
                "totalFreeStores" => $totalFreeStores,
                "totalStoresWithPaymentGateway" => $totalStoresWithPaymentGateway,
                "totalPlugnDomain" => $totalPlugnDomain,
                "totalCustomDomain" => $totalCustomDomain,
                //"most_sold_items" => $store->getMostSoldItems(),
                "currency_code" => "KWD",
                "numberOfOrders" => (int) $numberOfOrders,
                "itemsCount" => (int) $itemsCount,
                "today_customer_gained" => (int) $today_customer_gained,
                "today_revenue_generated" => (float) $today_revenue_generated,//Yii::$app->formatter->asCurrency($today_revenue_generated, $currencyCode),//, [\NumberFormatter::MIN_FRACTION_DIGITS => 3, \NumberFormatter::MAX_FRACTION_DIGITS => 3]
                //"today_sold_items" => (int) $today_sold_items,
                "today_orders_received" => (float)  $today_orders_received, //Yii::$app->formatter->asCurrency($today_orders_received, $currencyCode)//, [\NumberFormatter::MIN_FRACTION_DIGITS => 3, \NumberFormatter::MAX_FRACTION_DIGITS => 3]
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
}