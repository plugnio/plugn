<?php
namespace backend\controllers;

use Yii;
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
                        'actions' => ['index'],
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
        $interval = Yii::$app->request->get('interval');
        $type = Yii::$app->request->get('type');
 
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

        switch ($interval) {
            case 'last-month':
                $customer_data = Customer::getTotalCustomersByMonth();

                $revenue_data = Order::getTotalRevenueByMonth();

                $orders_data = Order::getTotalOrdersByMonth();
 
                break;

            case 'last-2-months':
                $customer_data = Customer::getTotalCustomersByMonths(2);

                $revenue_data = Order::getTotalRevenueByMonths(2);

                $orders_data = Order::getTotalOrdersByMonths(2);
 
                break; 

            case 'last-3-months':
                $customer_data = Customer::getTotalCustomersByMonths(3);

                $revenue_data = Order::getTotalRevenueByMonths(3);

                $orders_data = Order::getTotalOrdersByMonths(3);
 
                break;

            case 'last-5-months':
                #https://www.pivotaltracker.com/story/show/179023519
                $customer_data = Customer::getTotalCustomersByMonths(5);

                $revenue_data = Order::getTotalRevenueByMonths(5);

                $orders_data = Order::getTotalOrdersByMonths(5);
 
                break;

            case 'last-12-months':
                #https://www.pivotaltracker.com/story/show/179023519
                $customer_data = Customer::getTotalCustomersByMonths(12);

                $revenue_data = Order::getTotalRevenueByMonths(12);

                $orders_data = Order::getTotalOrdersByMonths(12);
 
                break;

            default:
                $customer_data = Customer::getTotalCustomersByWeek();

                $revenue_data = Order::getTotalRevenueByWeek();

                $orders_data = Order::getTotalOrdersByWeek();
        }

        //Yii::$app->response->format = \yii\web\Response::FORMAT_XML;

        return $this->render('index', array_merge (
            $customer_data,
            $revenue_data,
            $orders_data, [
                //"most_sold_items" => $store->getMostSoldItems(),
                "currency_code" => "KWD",
                "numberOfOrders" => (int) $numberOfOrders,
                "itemsCount" => (int) $itemsCount,
                "today_customer_gained" => (int) $today_customer_gained,
                "today_revenue_generated" => (float) $today_revenue_generated,//Yii::$app->formatter->asCurrency($today_revenue_generated, $currencyCode),//, [\NumberFormatter::MIN_FRACTION_DIGITS => 3, \NumberFormatter::MAX_FRACTION_DIGITS => 3]
                //"today_sold_items" => (int) $today_sold_items,
                "today_orders_received" => (float)  $today_orders_received, //Yii::$app->formatter->asCurrency($today_orders_received, $currencyCode)//, [\NumberFormatter::MIN_FRACTION_DIGITS => 3, \NumberFormatter::MAX_FRACTION_DIGITS => 3]
            ]
        ));
    }
}