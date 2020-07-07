<?php

namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use common\models\Restaurant;
use common\models\OrderItem;
use common\models\Order;
use common\models\Item;
use common\models\Customer;
use yii\db\Expression;

/**
 * Site controller
 */
class SiteController extends Controller {

    public $enableCsrfValidation = false;

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'index', 'signup', 'check-for-new-orders', 'thank-you'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'promote-to-open', 'promote-to-close', 'pay', 'callback', 'vendor-dashboard', 'export-today-sold-items', 'export-this-week-sold-items', 'export-this-months-sold-items'],
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
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'layout' => 'login',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays landing page
     *
     * @return mixed
     */
    public function actionIndex() {
        $this->layout = 'landing';

        if (Yii::$app->user->isGuest)
            return $this->render('landing');
        else {
            foreach (Yii::$app->accountManager->getManagedAccounts() as $managedRestaurant) {

                return $this->redirect(['vendor-dashboard',
                            'id' => $managedRestaurant->restaurant_uuid
                ]);
            }
        }
    }

    /**
     * Displays vendor dashboard homepage.
     *
     * @return mixed
     */
    public function actionVendorDashboard($id) {

        if ($managedRestaurant = Yii::$app->accountManager->getManagedAccount($id)) {

            $incoming_orders = Order::find()->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid, 'order_status' => Order::STATUS_PENDING])
                    ->orderBy(['order_created_at' => SORT_DESC])
                    ->limit(5)
                    ->all();




            //Orders Recevied
            $orders_received_chart_data_this_week = [];
            $orders_received_chart_data_last_month = [];
            $orders_received_chart_data_last_three_months = [];


            // orders recevied
            $today_orders_received = Order::find()
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['DATE(order_created_at)' => new Expression('CURDATE()')])
                    ->count();

            $number_of_all_orders_received_last_month = Order::find()
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->count();

            $number_of_all_orders_received_this_week = Order::find()
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['>', 'order.order_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 7 DAY)')])
                    ->count();

            //order recevied chart
            $number_of_all_orders_received_last_7_days_only = Order::find()
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 6 DAY) ')
                    ->count();

            array_push($orders_received_chart_data_this_week, (int) ($number_of_all_orders_received_last_7_days_only));

            $number_of_all_orders_received_last_6_days_only = Order::find()
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 5 DAY) ')
                    ->count();

            array_push($orders_received_chart_data_this_week, (int) ($number_of_all_orders_received_last_6_days_only));

            $number_of_all_orders_received_last_5_days_only = Order::find()
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 4 DAY) ')
                    ->count();


            array_push($orders_received_chart_data_this_week, (int) ($number_of_all_orders_received_last_5_days_only));

            $number_of_all_orders_received_last_4_days_only = Order::find()
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 3 DAY) ')
                    ->count();


            array_push($orders_received_chart_data_this_week, (int) ($number_of_all_orders_received_last_4_days_only));

            $number_of_all_orders_received_last_3_days_only = Order::find()
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 2 DAY) ')
                    ->count();


            array_push($orders_received_chart_data_this_week, (int) ($number_of_all_orders_received_last_3_days_only));

            $number_of_all_orders_received_last_2_days_only = Order::find()
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 1 DAY) ')
                    ->count();


            array_push($orders_received_chart_data_this_week, (int) ($number_of_all_orders_received_last_2_days_only));


            $number_of_all_orders_received_today_only = Order::find()
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['DATE(order.order_created_at)' => new Expression('CURDATE()')])
                    ->count();

            array_push($orders_received_chart_data_this_week, (int) ($number_of_all_orders_received_today_only));

            //last month
            $number_of_all_orders_received_last_two_months_only = Order::find()
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 2 MONTH)')
                    ->count();

            array_push($orders_received_chart_data_last_month, (int) ($number_of_all_orders_received_last_two_months_only));

            $number_of_all_orders_received_last_month_only = Order::find()
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->count();

            array_push($orders_received_chart_data_last_month, (int) ($number_of_all_orders_received_last_month_only));



            array_push($orders_received_chart_data_last_three_months, (int) ($number_of_all_orders_received_last_two_months_only));

            array_push($orders_received_chart_data_last_three_months, (int) ($number_of_all_orders_received_last_month_only));

            $number_of_all_orders_received_current_month_only = Order::find()
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere('YEAR(order.order_created_at) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 0 MONTH)')
                    ->count();

            array_push($orders_received_chart_data_last_three_months, (int) ($number_of_all_orders_received_current_month_only));


            $number_of_all_orders_received_last_three_months = 0;

            foreach ($orders_received_chart_data_last_three_months as $orderReceived) {
                $number_of_all_orders_received_last_three_months += $orderReceived ? intval($orderReceived) : 0 ;
            }


            //Sold items
            $sold_item_chart_data_this_week = [];
            $sold_item_chart_data_last_month = [];
            $sold_item_chart_data_last_three_months = [];


            $today_sold_items = OrderItem::find()
                    ->joinWith('order')
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['order.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['DATE(order_created_at)' => new Expression('CURDATE()')])
                    ->sum('order_item.qty');



            $number_of_all_sold_item_last_month = OrderItem::find()
                    ->joinWith('order')
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['order.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->sum('order_item.qty');

            $number_of_all_sold_item_this_week = OrderItem::find()
                    ->joinWith('order')
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['order.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['>', 'order.order_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 7 DAY)')])
                    ->sum('order_item.qty');


            //Sold items chart
            $number_of_all_sold_item_last_7_days_only = OrderItem::find()
                    ->joinWith('order')
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['order.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 6 DAY) ')
                    ->sum('order_item.qty');

            array_push($sold_item_chart_data_this_week, (int) ($number_of_all_sold_item_last_7_days_only));

            $number_of_all_sold_item_last_6_days_only = OrderItem::find()
                    ->joinWith('order')
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['order.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 5 DAY) ')
                    ->sum('order_item.qty');

            array_push($sold_item_chart_data_this_week, (int) ($number_of_all_sold_item_last_6_days_only));

            $number_of_all_sold_item_last_5_days_only = OrderItem::find()
                    ->joinWith('order')
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['order.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 4 DAY) ')
                    ->sum('order_item.qty');

            array_push($sold_item_chart_data_this_week, (int) ($number_of_all_sold_item_last_5_days_only));

            $number_of_all_sold_item_last_4_days_only = OrderItem::find()
                    ->joinWith('order')
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['order.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 3 DAY) ')
                    ->sum('order_item.qty');

            array_push($sold_item_chart_data_this_week, (int) ($number_of_all_sold_item_last_4_days_only));

            $number_of_all_sold_item_last_3_days_only = OrderItem::find()
                    ->joinWith('order')
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['order.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 2 DAY) ')
                    ->sum('order_item.qty');

            array_push($sold_item_chart_data_this_week, (int) ($number_of_all_sold_item_last_3_days_only));

            $number_of_all_sold_item_last_2_days_only = OrderItem::find()
                    ->joinWith('order')
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['order.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 1 DAY) ')
                    ->sum('order_item.qty');

            array_push($sold_item_chart_data_this_week, (int) ($number_of_all_sold_item_last_2_days_only));


            $number_of_all_sold_item_today_only = OrderItem::find()
                    ->joinWith('order')
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['order.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['DATE(order.order_created_at)' => new Expression('CURDATE()')])
                    ->sum('order_item.qty');

            array_push($sold_item_chart_data_this_week, (int) ($number_of_all_sold_item_today_only));

            //last month
            $number_of_all_sold_item_last_two_months_only = OrderItem::find()
                    ->joinWith('order')
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['order.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 2 MONTH)')
                    ->sum('order_item.qty');

            array_push($sold_item_chart_data_last_month, (int) ($number_of_all_sold_item_last_two_months_only));

            $number_of_all_sold_item_last_month_only = OrderItem::find()
                    ->joinWith('order')
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['order.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->sum('order_item.qty');

            array_push($sold_item_chart_data_last_month, (int) ($number_of_all_sold_item_last_month_only));



            array_push($sold_item_chart_data_last_three_months, (int) ($number_of_all_sold_item_last_two_months_only));

            array_push($sold_item_chart_data_last_three_months, (int) ($number_of_all_sold_item_last_month_only));

            $number_of_all_sold_item_current_month_only = OrderItem::find()
                    ->joinWith('order')
                    ->where(['order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['order.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere('YEAR(order.order_created_at) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 0 MONTH)')
                    ->sum('order_item.qty');

            array_push($sold_item_chart_data_last_three_months, (int) ($number_of_all_sold_item_current_month_only));


            $number_of_all_sold_item_last_three_months = 0;

            foreach ($sold_item_chart_data_last_three_months as $soldItem) {
                $number_of_all_sold_item_last_three_months += $soldItem ? intval($soldItem)  : 0;
            }




            //Customers
            $customer_chart_data_this_week = [];
            $customer_chart_data_last_month = [];
            $customer_chart_data_last_three_months = [];




            $today_customer_gained = Customer::find()
                    ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['DATE(customer_created_at)' => new Expression('CURDATE()')])
                    ->count();



            $number_of_all_customer_gained_last_month = Customer::find()
                    ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere('YEAR(`customer`.`customer_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->andWhere('MONTH(`customer`.`customer_created_at`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->count(); //6


            $number_of_all_customer_gained_this_week = Customer::find()
                    ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['>', 'customer.customer_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 7 DAY)')])
                    ->count(); //3

            $number_of_all_customers_gained_last_7_days_only = Customer::find()
                    ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(' DATE(`customer_created_at`) = DATE(NOW() - INTERVAL 6 DAY) ')
                    ->count(); //

            array_push($customer_chart_data_this_week, (int) ($number_of_all_customers_gained_last_7_days_only));

            $number_of_all_customers_gained_last_6_days_only = Customer::find()
                    ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(' DATE(`customer_created_at`) = DATE(NOW() - INTERVAL 5 DAY) ')
                    ->count(); //

            array_push($customer_chart_data_this_week, (int) ($number_of_all_customers_gained_last_6_days_only));

            $number_of_all_customers_gained_last_5_days_only = Customer::find()
                    ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(' DATE(`customer_created_at`) = DATE(NOW() - INTERVAL 4 DAY) ')
                    ->count(); //

            array_push($customer_chart_data_this_week, (int) ($number_of_all_customers_gained_last_5_days_only));

            $number_of_all_customers_gained_last_4_days_only = Customer::find()
                    ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(' DATE(`customer_created_at`) = DATE(NOW() - INTERVAL 3 DAY) ')
                    ->count(); //

            array_push($customer_chart_data_this_week, (int) ($number_of_all_customers_gained_last_4_days_only));

            $number_of_all_customers_gained_last_3_days_only = Customer::find()
                    ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(' DATE(`customer_created_at`) = DATE(NOW() - INTERVAL 2 DAY) ')
                    ->count(); //

            array_push($customer_chart_data_this_week, (int) ($number_of_all_customers_gained_last_3_days_only));

            $number_of_all_customers_gained_last_2_days_only = Customer::find()
                    ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(' DATE(`customer_created_at`) = DATE(NOW() - INTERVAL 1 DAY) ')
                    ->count(); //

            array_push($customer_chart_data_this_week, (int) ($number_of_all_customers_gained_last_2_days_only));


            $number_of_all_customers_gained_today_only = Customer::find()
                    ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['DATE(customer_created_at)' => new Expression('CURDATE()')])
                    ->count(); //

            array_push($customer_chart_data_this_week, (int) ($number_of_all_customers_gained_today_only));

            //last month
            $number_of_all_customers_gained_last_two_months_only = Customer::find()
                    ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere('YEAR(`customer`.`customer_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->andWhere('MONTH(`customer`.`customer_created_at`) = MONTH(CURRENT_DATE - INTERVAL 2 MONTH)')
                    ->count();

            array_push($customer_chart_data_last_month, (int) ($number_of_all_customers_gained_last_two_months_only));

            $number_of_all_customers_gained_last_month_only = Customer::find()
                    ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere('YEAR(`customer`.`customer_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->andWhere('MONTH(`customer`.`customer_created_at`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->count();

            array_push($customer_chart_data_last_month, (int) ($number_of_all_customers_gained_last_month_only));

            //last three month
            array_push($customer_chart_data_last_three_months, (int) ($number_of_all_customers_gained_last_two_months_only));

            array_push($customer_chart_data_last_three_months, (int) ($number_of_all_customers_gained_last_month_only));

            $number_of_all_customers_gained_current_month_only = Customer::find()
                    ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere('YEAR(`customer`.`customer_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->andWhere('MONTH(`customer`.`customer_created_at`) = MONTH(CURRENT_DATE - INTERVAL 0 MONTH)')
                    ->count();

            array_push($customer_chart_data_last_three_months, (int) ($number_of_all_customers_gained_current_month_only));


            $number_of_all_customer_gained_last_three_months = 0;

            foreach ($customer_chart_data_last_three_months as $customerGained) {
                $number_of_all_customer_gained_last_three_months += $customerGained ? intval($customerGained)  : 0 ;
            }


            //Revenue

            $revenue_generated_chart_data_this_week = [];
            $revenue_generated_chart_data_last_month = [];
            $revenue_generated_chart_data_last_three_months = [];
            $revenue_generated_chart_data = [];


            $today_revenue_generated = Order::find()
                    ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                    ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                    ->andWhere(['DATE(order_created_at)' => new Expression('CURDATE()')])
                    ->sum('total_price');




            $number_of_all_revenue_generated_last_month = Order::find()
                    ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                    ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                    ->andWhere('YEAR(`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->andWhere('MONTH(`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->sum('total_price'); //434.5


            // $number_of_all_revenue_generated_this_week = Order::find()
            //         ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
            //         ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
            //         ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
            //         ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
            //         ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
            //         ->andWhere(['>', 'order_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 7 DAY)')])
            //         ->sum('total_price');


            //Chart
            $number_of_all_revenue_generated_last_7_days_only = Order::find()
                    ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                    ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                    ->andWhere(' DATE(`order_created_at`) = DATE(NOW() - INTERVAL 6 DAY) ')
                    ->sum('total_price');

            array_push($revenue_generated_chart_data_this_week,  number_format((float)$number_of_all_revenue_generated_last_7_days_only, 2, '.', ''));

            $number_of_all_revenue_generated_last_6_days_only = Order::find()
                    ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                    ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                    ->andWhere(' DATE(`order_created_at`) = DATE(NOW() - INTERVAL 5 DAY) ')
                    ->sum('total_price');

            array_push($revenue_generated_chart_data_this_week,  number_format((float)$number_of_all_revenue_generated_last_6_days_only, 2, '.', ''));

            $number_of_all_revenue_generated_last_5_days_only = Order::find()
                    ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                    ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                    ->andWhere(' DATE(`order_created_at`) = DATE(NOW() - INTERVAL 4 DAY) ')
                    ->sum('total_price');

            array_push($revenue_generated_chart_data_this_week,  number_format((float)$number_of_all_revenue_generated_last_5_days_only, 2, '.', ''));

            $number_of_all_revenue_generated_last_4_days_only = Order::find()
                    ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                    ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                    ->andWhere(' DATE(`order_created_at`) = DATE(NOW() - INTERVAL 3 DAY) ')
                    ->sum('total_price');

            array_push($revenue_generated_chart_data_this_week,  number_format((float)$number_of_all_revenue_generated_last_4_days_only, 2, '.', ''));


            $number_of_all_revenue_generated_last_3_days_only = Order::find()
                    ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                    ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                    ->andWhere(' DATE(`order_created_at`) = DATE(NOW() - INTERVAL 2 DAY) ')
                    ->sum('total_price');

            array_push($revenue_generated_chart_data_this_week,  number_format((float)$number_of_all_revenue_generated_last_3_days_only, 2, '.', ''));


            $number_of_all_revenue_generated_last_2_days_only = Order::find()
                    ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                    ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                    ->andWhere(' DATE(`order_created_at`) = DATE(NOW() - INTERVAL 1 DAY) ')
                    ->sum('total_price');

            array_push($revenue_generated_chart_data_this_week,  number_format((float)$number_of_all_revenue_generated_last_2_days_only, 2, '.', ''));

            $number_of_all_revenue_generated_today_only = Order::find()
                    ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                    ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                    ->andWhere(['DATE(order_created_at)' => new Expression('CURDATE()')])
                    ->sum('total_price');

            array_push($revenue_generated_chart_data_this_week,  number_format((float)$number_of_all_revenue_generated_today_only, 2, '.', ''));


            $number_of_all_revenue_generated_this_week = 0;

            foreach ($revenue_generated_chart_data_this_week as $revenueGenerated) {
                $number_of_all_revenue_generated_this_week += $revenueGenerated ? floatval($revenueGenerated) : 0;
            }


            //last month
            $number_of_all_revenue_generated_last_two_months_only = Order::find()
                    ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                    ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                    ->andWhere('YEAR(`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->andWhere('MONTH(`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 2 MONTH)')
                    ->sum('total_price');

            array_push($revenue_generated_chart_data_last_month,  number_format((float)$number_of_all_revenue_generated_last_two_months_only, 2, '.', ''));

            $number_of_all_revenue_generated_last_month_only = Order::find()
                    ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                    ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                    ->andWhere('YEAR(`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->andWhere('MONTH(`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->sum('total_price');

            array_push($revenue_generated_chart_data_last_month, number_format((float)$number_of_all_revenue_generated_last_month_only, 2, '.', ''));

            //last 3 months
            array_push($revenue_generated_chart_data_last_three_months,  number_format((float)$number_of_all_revenue_generated_last_two_months_only, 2, '.', ''));
            array_push($revenue_generated_chart_data_last_three_months,  number_format((float)$number_of_all_revenue_generated_last_month_only, 2, '.', ''));

            $number_of_all_revenue_generated_current_month_only = Order::find()
                    ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                    ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                    ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                    ->andWhere('YEAR(`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                    ->andWhere('MONTH(`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 0 MONTH)')
                    ->sum('total_price');

            array_push($revenue_generated_chart_data_last_three_months,  number_format((float)$number_of_all_revenue_generated_current_month_only, 2, '.', ''));



            $number_of_all_revenue_generated_last_three_months = 0;

            foreach ($revenue_generated_chart_data_last_three_months as $revenueGenerated) {
                $number_of_all_revenue_generated_last_three_months += $revenueGenerated ? floatval($revenueGenerated) : 0;
            }




            return $this->render('index', [
                        'restaurant_model' => $managedRestaurant,
                        'incoming_orders' => $incoming_orders,
                        //customer gained
                        'today_customer_gained' => $today_customer_gained ? $today_customer_gained : 0,
                        'number_of_all_customer_gained_last_three_months' => $number_of_all_customer_gained_last_three_months,
                        'number_of_all_customer_gained_last_month' => $number_of_all_customer_gained_last_month,
                        'number_of_all_customer_gained_this_week' => $number_of_all_customer_gained_this_week,
                        //customer gained charts
                        'customer_chart_data_this_week' => $customer_chart_data_this_week,
                        'customer_chart_data_last_month' => $customer_chart_data_last_month,
                        'customer_chart_data_last_three_months' => $customer_chart_data_last_three_months,
                        //revenue Generated
                        'today_revenue_generated' => $today_revenue_generated ? $today_revenue_generated : 0,
                        'number_of_all_revenue_generated_last_three_months' => $number_of_all_revenue_generated_last_three_months,
                        'number_of_all_revenue_generated_last_month' => $number_of_all_revenue_generated_last_month,
                        'number_of_all_revenue_generated_this_week' => $number_of_all_revenue_generated_this_week,
                        //revenue Generated charts
                        'revenue_generated_chart_data_this_week' => $revenue_generated_chart_data_this_week,
                        'revenue_generated_chart_data_last_month' => $revenue_generated_chart_data_last_month,
                        'revenue_generated_chart_data_last_three_months' => $revenue_generated_chart_data_last_three_months,
                        //sold_item
                        'today_sold_items' => $today_sold_items ? $today_sold_items : 0,
                        'number_of_all_sold_item_last_three_months' => $number_of_all_sold_item_last_three_months,
                        'number_of_all_sold_item_last_month' => $number_of_all_sold_item_last_month,
                        'number_of_all_sold_item_this_week' => $number_of_all_sold_item_this_week,
                        //sold_item chart
                        'sold_item_chart_data_this_week' => $sold_item_chart_data_this_week ? $sold_item_chart_data_this_week : 0,
                        'sold_item_chart_data_last_month' => $sold_item_chart_data_last_month,
                        'sold_item_chart_data_last_three_months' => $sold_item_chart_data_last_three_months,
                        //orders_received
                        'today_orders_received' => $today_orders_received ? $today_orders_received : 0,
                        'number_of_all_orders_received_last_three_months' => $number_of_all_orders_received_last_three_months,
                        'number_of_all_orders_received_last_month' => $number_of_all_orders_received_last_month,
                        'number_of_all_orders_received_this_week' => $number_of_all_orders_received_this_week,
                        //orders_received chart
                        'orders_received_chart_data_this_week' => $orders_received_chart_data_this_week,
                        'orders_received_chart_data_last_month' => $orders_received_chart_data_last_month,
                        'orders_received_chart_data_last_three_months' => $orders_received_chart_data_last_three_months,
            ]);
        }
    }

    public function actionExportTodaySoldItems($restaurantUuid) {
        if ($managedRestaurant = Yii::$app->accountManager->getManagedAccount($restaurantUuid)) {


            $today_sold_item = Item::find()
                    ->joinWith(['orderItems', 'orderItems.order'])
                    ->where(['order.order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order.order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order.order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order.order_status' => Order::STATUS_COMPLETE])
                    ->andWhere(['order.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['>', 'order.order_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 1 DAY)')])
                    ->all();

            header('Access-Control-Allow-Origin: *');
            header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
            header("Content-Disposition: attachment;filename=\"sold-items.xlsx\"");
            header("Cache-Control: max-age=0");


            \moonland\phpexcel\Excel::export([
                'isMultipleSheet' => false,
                'models' => $today_sold_item,
                'columns' => [
                    'item_name',
                    [
                        'label' => 'role',
                        'format' => 'html',
                        'value' => function ($data) {

                            return $data->getTodaySoldUnits();
                        },
                    ],
                ],
            ]);
        }
    }

    public function actionExportThisWeekSoldItems($restaurantUuid) {
        if ($managedRestaurant = Yii::$app->accountManager->getManagedAccount($restaurantUuid)) {


            $this_week_sold_item = Item::find()
                    ->joinWith(['orderItems', 'orderItems.order'])
                    ->where(['order.order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order.order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order.order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order.order_status' => Order::STATUS_COMPLETE])
                    ->andWhere(['order.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['>', 'order.order_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 7 DAY)')])
                    ->all();

            header('Access-Control-Allow-Origin: *');
            header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
            header("Content-Disposition: attachment;filename=\"sold-items.xlsx\"");
            header("Cache-Control: max-age=0");


            \moonland\phpexcel\Excel::export([
                'isMultipleSheet' => false,
                'models' => $this_week_sold_item,
                'columns' => [
                    'item_name',
                    [
                        'label' => 'role',
                        'format' => 'html',
                        'value' => function ($data) {
                            return $data->getThisWeekSoldUnits();
                        },
                    ],
                ],
            ]);
        }
    }

    public function actionExportThisMonthsSoldItems($restaurantUuid) {

        if ($managedRestaurant = Yii::$app->accountManager->getManagedAccount($restaurantUuid)) {


            $this_month_sold_item = Item::find()
                    ->joinWith(['orderItems', 'orderItems.order'])
                    ->where(['order.order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order.order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order.order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order.order_status' => Order::STATUS_COMPLETE])
                    ->andWhere(['order.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['>', 'order.order_created_at', new Expression('DATE_SUB(NOW(), INTERVAL 30 DAY)')])
                    ->all();

            header('Access-Control-Allow-Origin: *');
            header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
            header("Content-Disposition: attachment;filename=\"sold-items.xlsx\"");
            header("Cache-Control: max-age=0");


            \moonland\phpexcel\Excel::export([
                'isMultipleSheet' => false,
                'models' => $this_month_sold_item,
                'columns' => [
                    'item_name',
                    [
                        'label' => 'role',
                        'format' => 'html',
                        'value' => function ($data) {

                            return $data->getThisMonthSoldUnits();
                        },
                    ],
                ],
            ]);
        }
    }

    /**
     * Change restaurant status to become open
     * @param integer $id => restaurant_uuid
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPromoteToOpen($id) {

        $model = $this->findModel($id);
        $model->promoteToOpenRestaurant();

        return $this->redirect(['index', 'id' => $id]);
    }

    /**
     * Change restaurant status to become busy
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPromoteToBusy($id) {
        $model = $this->findModel($id);
        $model->promoteToBusyRestaurant();

        return $this->redirect(['index', 'id' => $id]);
    }

    /**
     * Change restaurant status to become close
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionPromoteToClose($id) {
        $model = $this->findModel($id);
        $model->promoteToCloseRestaurant();

        return $this->redirect(['index', 'id' => $id]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin() {

        $this->layout = 'login';

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $managedRestaurant = $model->login()) {
            return $this->redirect(['site/vendor-dashboard', 'id' => $managedRestaurant->restaurant_uuid]);
        } else {
            $model->password = '';

            return $this->render('login', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout() {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays signup page.
     *
     * @return mixed
     */
    public function actionSignup() {

        $this->layout = 'landing';

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->sendEmail()) {
            return $this->redirect(['thank-you']);
        }

        return $this->render('signup', [
                    'model' => $model,
        ]);
    }

    public function actionThankYou() {
        $this->layout = 'landing';
        return $this->render('thankYou');
    }

    /**
     * Check for new orders
     */
    public function actionCheckForNewOrders($restaurant_uuid) {

        $managedRestaurant = Yii::$app->accountManager->getManagedAccount($restaurant_uuid);

        $this->layout = false;

        $newOrders = Order::find()->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid, 'order_status' => Order::STATUS_PENDING])
                ->orderBy(['order_created_at' => SORT_DESC])
                ->limit(5)
                ->all();


        return $this->render('incoming-orders-table', [
                    'orders' => $newOrders
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
                    'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token) {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
                    'model' => $model,
        ]);
    }

    /**
     * Finds the Restaurant model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Restaurant the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Yii::$app->accountManager->getManagedAccount($id))) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
