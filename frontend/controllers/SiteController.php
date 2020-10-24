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
use frontend\models\OrderSearch;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use common\models\Agent;
use common\models\Restaurant;
use common\models\OrderItem;
use common\models\Order;
use common\models\Subscription;
use common\models\AgentAssignment;
use common\models\Item;
use common\models\Customer;
use common\models\SubscriptionPayment;
use yii\db\Expression;
use yii\helpers\Url;
use common\components\TapPayments;

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
                        'actions' => ['login', 'error', 'current-plan', 'compare-plan', 'index', 'signup', 'check-for-new-orders', 'thank-you', 'request-password-reset', 'reset-password'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'promote-to-open', 'connect-domain', 'promote-to-close', 'pay', 'callback', 'vendor-dashboard', 'real-time-orders', 'mark-as-busy', 'mark-as-open'],
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

        if (Yii::$app->user->isGuest)
            return $this->redirect(['login']);
        else {
            foreach (Yii::$app->accountManager->getManagedAccounts() as $managedRestaurant) {

                if (AgentAssignment::isOwner($managedRestaurant->restaurant_uuid)) {
                    return $this->redirect(['home',
                                'id' => $managedRestaurant->restaurant_uuid
                    ]);
                } else {
                    return $this->redirect(['real-time-orders',
                                'restaurant_uuid' => $managedRestaurant->restaurant_uuid
                    ]);
                }
            }
        }
    }

    /**
     * Check for new orders
     */
    public function actionCheckForNewOrders($restaurant_uuid) {

        $this->layout = false;
        $managedRestaurant = Yii::$app->accountManager->getManagedAccount($restaurant_uuid);

        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->searchPendingOrders(Yii::$app->request->queryParams, $restaurant_uuid);

        return $this->render('incoming-orders-table', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays  Real time orders
     *
     * @return mixed
     */
    public function actionConnectDomain($id) {
        if ($model = Yii::$app->accountManager->getManagedAccount($id)) {


            return $this->render('connect-domain', [
                        'restaurant_model' => $managedRestaurant
            ]);
        }
    }

    /**
     * Comapre plan page
     *
     * @return mixed
     */
    public function actionPay($id) {

        if ($managedRestaurant = Yii::$app->accountManager->getManagedAccount($id)) {

            $plugn_store = Restaurant::findOne('rest_1d40a718-beac-11ea-808a-0673128d0c9c');

            $subscription = new Subscription();
            $subscription->restaurant_uuid = $managedRestaurant->restaurant_uuid;
            $subscription->plan_id = 2;
            $subscription->save(false);

            $payment = new SubscriptionPayment;
            $payment->restaurant_uuid = $managedRestaurant->restaurant_uuid;
            // $payment->payment_mode = $order->payment_method_id == 1 ? TapPayments::GATEWAY_KNET : TapPayments::GATEWAY_VISA_MASTERCARD; TODO
            $payment->subscription_uuid = $subscription->subscription_uuid; //subscription_uuid
            $payment->payment_amount_charged = $subscription->plan->price;
            $payment->payment_current_status = "Redirected to payment gateway";

            if ($payment->save()) {

                //Update payment_uuid in order
                $subscription->payment_uuid = $payment->payment_uuid;
                $subscription->save(false);


                // Redirect to payment gateway
                Yii::$app->tapPayments->setApiKeys($plugn_store->test_api_key, $plugn_store->test_api_key);

                // if ($order->payment_method_id == 1) {
                //     $source_id = TapPayments::GATEWAY_KNET;
                // } else {
                //     if ($payment->payment_token)
                //         $source_id = $payment->payment_token;
                //     else
                //         $source_id = TapPayments::GATEWAY_VISA_MASTERCARD;
                // }
                // $source_id
                $response = Yii::$app->tapPayments->createCharge(
                        "Order placed from: " . $managedRestaurant->name, // Description
                        'Plugn', //Statement Desc.
                        $payment->payment_uuid, // Reference
                        $subscription->plan->price, $managedRestaurant->owner_first_name, $managedRestaurant->owner_email, $managedRestaurant->owner_number, 0, Url::to(['site/callback'], true), TapPayments::GATEWAY_KNET
                        // $order->payment_method_id == 1 ? TapPayments::GATEWAY_KNET :  TapPayments::GATEWAY_VISA_MASTERCARD
                        // $source_id
                );

                $responseContent = json_decode($response->content);

                try {

                    // Validate that theres no error from TAP gateway
                    if (isset($responseContent->errors)) {
                        $errorMessage = "Error: " . $responseContent->errors[0]->code . " - " . $responseContent->errors[0]->description;
                        \Yii::error($errorMessage, __METHOD__); // Log error faced by user

                        return [
                            'operation' => 'error',
                            'message' => $errorMessage
                        ];
                    }

                    if ($responseContent->id) {

                        $chargeId = $responseContent->id;
                        $redirectUrl = $responseContent->transaction->url;

                        $payment->payment_gateway_transaction_id = $chargeId;

                        if (!$payment->save(false)) {

                            \Yii::error($payment->errors, __METHOD__); // Log error faced by user

                            return [
                                'operation' => 'error',
                                'message' => $payment->getErrors()
                            ];
                        }
                    } else {
                        \Yii::error('[Payment Issue > Charge id is missing ]' . $responseContent, __METHOD__); // Log error faced by user
                    }

                    return $this->redirect($redirectUrl);
                } catch (\Exception $e) {

                    if ($payment)
                        Yii::error('[TAP Payment Issue > ]' . json_encode($payment->getErrors()), __METHOD__);

                    Yii::error('[TAP Payment Issue > Charge id is missing]' . json_encode($responseContent), __METHOD__);

                    $response = [
                        'operation' => 'error',
                        'message' => $responseContent
                    ];
                }
            }else {
                die(json_encode($payment->errors));
            }
        }
    }

    /**
     * Process callback from TAP payment gateway
     * @param string $tap_id
     * @return mixed
     */
    public function actionCallback($tap_id) {
        try {

            $paymentRecord = SubscriptionPayment::updatePaymentStatusFromTap($tap_id);
            $paymentRecord->received_callback = true;
            $paymentRecord->save(false);

            // Redirect back to app
            if ($paymentRecord->payment_current_status != 'CAPTURED') {  //Failed Payment
                return $this->redirect($paymentRecord->restaurant->restaurant_domain . '/payment-failed/' . $paymentRecord->order_uuid);
            }

            // Redirect back to app
            // $paymentRecord->order->changeOrderStatusToPending();
            return $this->redirect($paymentRecord->restaurant->restaurant_domain . '/payment-success/' . $paymentRecord->order_uuid . '/' . $paymentRecord->payment_uuid);
        } catch (\Exception $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    /**
     * Comapre plan page
     *
     * @return mixed
     */
    public function actionComparePlan($id) {
        if ($managedRestaurant = Yii::$app->accountManager->getManagedAccount($id)) {

            $subscription = $managedRestaurant->getSubscriptions()->where(['subscription_status' => Subscription::STATUS_ACTIVE])->one();


            return $this->render('compare-plan', [
                        'restaurant_model' => $managedRestaurant,
                        'plan_id' => $subscription->plan_id
            ]);
        }
    }

    /**
     * Current plan
     *
     * @return mixed
     */
    public function actionCurrentPlan($id) {
        if ($managedRestaurant = Yii::$app->accountManager->getManagedAccount($id)) {

            $subscription = $managedRestaurant->getSubscriptions()->where(['subscription_status' => Subscription::STATUS_ACTIVE])->with('plan')->one();

            return $this->render('current-plan', [
                        'restaurant_model' => $managedRestaurant,
                        'subscription' => $subscription
            ]);
        }
    }

    /**
     * Displays  Real time orders
     *
     * @return mixed
     */
    public function actionRealTimeOrders($restaurant_uuid) {

        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->searchPendingOrders(Yii::$app->request->queryParams, $restaurant_uuid);

        return $this->render('real-time-orders', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'restaurant_uuid' => $restaurant_uuid
        ]);
    }

    /**
     * Displays vendor dashboard homepage.
     *
     * @return mixed
     */
    public function actionVendorDashboard($id) {

        if ($managedRestaurant = Yii::$app->accountManager->getManagedAccount($id)) {
            if (AgentAssignment::isOwner($managedRestaurant->restaurant_uuid)) {

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

                array_push($orders_received_chart_data_this_week, $number_of_all_orders_received_last_7_days_only ? (int) ($number_of_all_orders_received_last_7_days_only) : 0);

                $number_of_all_orders_received_last_6_days_only = Order::find()
                        ->where(['order_status' => Order::STATUS_PENDING])
                        ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                        ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                        ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                        ->orWhere(['order_status' => Order::STATUS_CANCELED])
                        ->andWhere(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 5 DAY) ')
                        ->count();

                array_push($orders_received_chart_data_this_week, $number_of_all_orders_received_last_6_days_only ? (int) ($number_of_all_orders_received_last_6_days_only) : 0);

                $number_of_all_orders_received_last_5_days_only = Order::find()
                        ->where(['order_status' => Order::STATUS_PENDING])
                        ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                        ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                        ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                        ->orWhere(['order_status' => Order::STATUS_CANCELED])
                        ->andWhere(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 4 DAY) ')
                        ->count();

                array_push($orders_received_chart_data_this_week, $number_of_all_orders_received_last_5_days_only ? (int) ($number_of_all_orders_received_last_5_days_only) : 0);

                $number_of_all_orders_received_last_4_days_only = Order::find()
                        ->where(['order_status' => Order::STATUS_PENDING])
                        ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                        ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                        ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                        ->orWhere(['order_status' => Order::STATUS_CANCELED])
                        ->andWhere(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 3 DAY) ')
                        ->count();


                array_push($orders_received_chart_data_this_week, $number_of_all_orders_received_last_4_days_only ? (int) ($number_of_all_orders_received_last_4_days_only) : 0);

                $number_of_all_orders_received_last_3_days_only = Order::find()
                        ->where(['order_status' => Order::STATUS_PENDING])
                        ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                        ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                        ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                        ->orWhere(['order_status' => Order::STATUS_CANCELED])
                        ->andWhere(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 2 DAY) ')
                        ->count();


                array_push($orders_received_chart_data_this_week, $number_of_all_orders_received_last_3_days_only ? (int) ($number_of_all_orders_received_last_3_days_only) : 0);

                $number_of_all_orders_received_last_2_days_only = Order::find()
                        ->where(['order_status' => Order::STATUS_PENDING])
                        ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                        ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                        ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                        ->orWhere(['order_status' => Order::STATUS_CANCELED])
                        ->andWhere(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(' DATE(order.order_created_at) = DATE(NOW() - INTERVAL 1 DAY) ')
                        ->count();


                array_push($orders_received_chart_data_this_week, $number_of_all_orders_received_last_2_days_only ? (int) ($number_of_all_orders_received_last_2_days_only) : 0);


                $number_of_all_orders_received_today_only = Order::find()
                        ->where(['order_status' => Order::STATUS_PENDING])
                        ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                        ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                        ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                        ->orWhere(['order_status' => Order::STATUS_CANCELED])
                        ->andWhere(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(['DATE(order.order_created_at)' => new Expression('CURDATE()')])
                        ->count();

                array_push($orders_received_chart_data_this_week, $number_of_all_orders_received_today_only ? (int) ($number_of_all_orders_received_today_only) : 0);



                $number_of_all_orders_received_this_week = 0;

                foreach ($orders_received_chart_data_this_week as $orderReceived) {
                    $number_of_all_orders_received_this_week += $orderReceived;
                }


                //last month
                $number_of_all_orders_received_last_three_months_only = Order::find()
                        ->where(['order_status' => Order::STATUS_PENDING])
                        ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                        ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                        ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                        ->orWhere(['order_status' => Order::STATUS_CANCELED])
                        ->andWhere(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                        ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)')
                        ->count();

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

                array_push($orders_received_chart_data_last_month, $number_of_all_orders_received_last_two_months_only ? (int) ($number_of_all_orders_received_last_two_months_only) : 0);

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

                array_push($orders_received_chart_data_last_month, $number_of_all_orders_received_last_month_only ? (int) ($number_of_all_orders_received_last_month_only) : 0);



                array_push($orders_received_chart_data_last_three_months, $number_of_all_orders_received_last_three_months_only ? (int) ($number_of_all_orders_received_last_three_months_only) : 0);

                array_push($orders_received_chart_data_last_three_months, $number_of_all_orders_received_last_month_only ? (int) ($number_of_all_orders_received_last_month_only) : 0);

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

                array_push($orders_received_chart_data_last_three_months, $number_of_all_orders_received_current_month_only ? (int) ($number_of_all_orders_received_current_month_only) : 0);


                $number_of_all_orders_received_last_three_months = 0;

                foreach ($orders_received_chart_data_last_three_months as $orderReceived) {
                    $number_of_all_orders_received_last_three_months += $orderReceived ? intval($orderReceived) : 0;
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

                array_push($sold_item_chart_data_this_week, $number_of_all_sold_item_last_7_days_only ? (int) ($number_of_all_sold_item_last_7_days_only) : 0);

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

                array_push($sold_item_chart_data_this_week, $number_of_all_sold_item_last_6_days_only ? (int) ($number_of_all_sold_item_last_6_days_only) : 0);

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

                array_push($sold_item_chart_data_this_week, $number_of_all_sold_item_last_5_days_only ? (int) ($number_of_all_sold_item_last_5_days_only) : 0);

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

                array_push($sold_item_chart_data_this_week, $number_of_all_sold_item_last_4_days_only ? (int) ($number_of_all_sold_item_last_4_days_only) : 0);

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

                array_push($sold_item_chart_data_this_week, $number_of_all_sold_item_last_3_days_only ? (int) ($number_of_all_sold_item_last_3_days_only) : 0);

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

                array_push($sold_item_chart_data_this_week, $number_of_all_sold_item_last_2_days_only ? (int) ($number_of_all_sold_item_last_2_days_only) : 0);


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

                array_push($sold_item_chart_data_this_week, $number_of_all_sold_item_today_only ? (int) ($number_of_all_sold_item_today_only) : 0);

                $number_of_all_sold_item_this_week = 0;

                foreach ($sold_item_chart_data_this_week as $soldItem) {
                    $number_of_all_sold_item_this_week += $soldItem;
                }

                //last month
                $number_of_all_sold_item_last_three_months_only = OrderItem::find()
                        ->joinWith('order')
                        ->where(['order_status' => Order::STATUS_PENDING])
                        ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                        ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                        ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                        ->orWhere(['order_status' => Order::STATUS_CANCELED])
                        ->andWhere(['order.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                        ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)')
                        ->sum('order_item.qty');

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

                array_push($sold_item_chart_data_last_month, $number_of_all_sold_item_last_two_months_only ? (int) ($number_of_all_sold_item_last_two_months_only) : 0);

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

                array_push($sold_item_chart_data_last_month, $number_of_all_sold_item_last_month_only ? (int) ($number_of_all_sold_item_last_month_only) : 0);

                array_push($sold_item_chart_data_last_three_months, $number_of_all_sold_item_last_three_months_only ? (int) ($number_of_all_sold_item_last_three_months_only) : 0);

                array_push($sold_item_chart_data_last_three_months, $number_of_all_sold_item_last_month_only ? (int) ($number_of_all_sold_item_last_month_only) : 0);

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

                array_push($sold_item_chart_data_last_three_months, $number_of_all_sold_item_current_month_only ? (int) ($number_of_all_sold_item_current_month_only) : 0);


                $number_of_all_sold_item_last_three_months = 0;

                foreach ($sold_item_chart_data_last_three_months as $soldItem) {
                    $number_of_all_sold_item_last_three_months += $soldItem ? intval($soldItem) : 0;
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



                $number_of_all_customers_gained_last_7_days_only = Customer::find()
                        ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(' DATE(`customer_created_at`) = DATE(NOW() - INTERVAL 6 DAY) ')
                        ->count(); //

                array_push($customer_chart_data_this_week, $number_of_all_customers_gained_last_7_days_only ? (int) ($number_of_all_customers_gained_last_7_days_only) : 0);

                $number_of_all_customers_gained_last_6_days_only = Customer::find()
                        ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(' DATE(`customer_created_at`) = DATE(NOW() - INTERVAL 5 DAY) ')
                        ->count(); //

                array_push($customer_chart_data_this_week, $number_of_all_customers_gained_last_6_days_only ? (int) ($number_of_all_customers_gained_last_6_days_only) : 0);

                $number_of_all_customers_gained_last_5_days_only = Customer::find()
                        ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(' DATE(`customer_created_at`) = DATE(NOW() - INTERVAL 4 DAY) ')
                        ->count(); //

                array_push($customer_chart_data_this_week, $number_of_all_customers_gained_last_5_days_only ? (int) ($number_of_all_customers_gained_last_5_days_only) : 0);

                $number_of_all_customers_gained_last_4_days_only = Customer::find()
                        ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(' DATE(`customer_created_at`) = DATE(NOW() - INTERVAL 3 DAY) ')
                        ->count(); //

                array_push($customer_chart_data_this_week, $number_of_all_customers_gained_last_4_days_only ? (int) ($number_of_all_customers_gained_last_4_days_only) : 0);

                $number_of_all_customers_gained_last_3_days_only = Customer::find()
                        ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(' DATE(`customer_created_at`) = DATE(NOW() - INTERVAL 2 DAY) ')
                        ->count(); //

                array_push($customer_chart_data_this_week, $number_of_all_customers_gained_last_3_days_only ? (int) ($number_of_all_customers_gained_last_3_days_only) : 0);

                $number_of_all_customers_gained_last_2_days_only = Customer::find()
                        ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(' DATE(`customer_created_at`) = DATE(NOW() - INTERVAL 1 DAY) ')
                        ->count(); //

                array_push($customer_chart_data_this_week, $number_of_all_customers_gained_last_2_days_only ? (int) ($number_of_all_customers_gained_last_2_days_only) : 0);


                $number_of_all_customers_gained_today_only = Customer::find()
                        ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(['DATE(customer_created_at)' => new Expression('CURDATE()')])
                        ->count(); //

                array_push($customer_chart_data_this_week, $number_of_all_customers_gained_today_only ? (int) ($number_of_all_customers_gained_today_only) : 0);

                $number_of_all_customer_gained_this_week = 0;

                foreach ($customer_chart_data_this_week as $customerGained) {
                    $number_of_all_customer_gained_this_week += $customerGained;
                }


                //last month
                $number_of_all_customers_gained_last_three_months_only = Customer::find()
                        ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere('YEAR(`customer`.`customer_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                        ->andWhere('MONTH(`customer`.`customer_created_at`) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)')
                        ->count();

                $number_of_all_customers_gained_last_two_months_only = Customer::find()
                        ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere('YEAR(`customer`.`customer_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                        ->andWhere('MONTH(`customer`.`customer_created_at`) = MONTH(CURRENT_DATE - INTERVAL 2 MONTH)')
                        ->count();

                array_push($customer_chart_data_last_month, $number_of_all_customers_gained_last_two_months_only ? (int) ($number_of_all_customers_gained_last_two_months_only) : 0);

                $number_of_all_customers_gained_last_month_only = Customer::find()
                        ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere('YEAR(`customer`.`customer_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                        ->andWhere('MONTH(`customer`.`customer_created_at`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
                        ->count();

                array_push($customer_chart_data_last_month, $number_of_all_customers_gained_last_month_only ? (int) ($number_of_all_customers_gained_last_month_only) : 0);

                //last three month
                array_push($customer_chart_data_last_three_months, $number_of_all_customers_gained_last_three_months_only ? (int) ($number_of_all_customers_gained_last_three_months_only) : 0);

                array_push($customer_chart_data_last_three_months, $number_of_all_customers_gained_last_month_only ? (int) ($number_of_all_customers_gained_last_month_only) : 0);

                $number_of_all_customers_gained_current_month_only = Customer::find()
                        ->where(['customer.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere('YEAR(`customer`.`customer_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                        ->andWhere('MONTH(`customer`.`customer_created_at`) = MONTH(CURRENT_DATE - INTERVAL 0 MONTH)')
                        ->count();

                array_push($customer_chart_data_last_three_months, $number_of_all_customers_gained_current_month_only ? (int) ($number_of_all_customers_gained_current_month_only) : 0);


                $number_of_all_customer_gained_last_three_months = 0;

                foreach ($customer_chart_data_last_three_months as $customerGained) {
                    $number_of_all_customer_gained_last_three_months += $customerGained ? intval($customerGained) : 0;
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
                //Chart
                $number_of_all_revenue_generated_last_7_days_only = Order::find()
                        ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                        ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                        ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                        ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                        ->andWhere(' DATE(`order_created_at`) = DATE(NOW() - INTERVAL 6 DAY) ')
                        ->sum('total_price');

                array_push($revenue_generated_chart_data_this_week, number_format((float) $number_of_all_revenue_generated_last_7_days_only, 2, '.', ''));

                $number_of_all_revenue_generated_last_6_days_only = Order::find()
                        ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                        ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                        ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                        ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                        ->andWhere(' DATE(`order_created_at`) = DATE(NOW() - INTERVAL 5 DAY) ')
                        ->sum('total_price');

                array_push($revenue_generated_chart_data_this_week, number_format((float) $number_of_all_revenue_generated_last_6_days_only, 2, '.', ''));

                $number_of_all_revenue_generated_last_5_days_only = Order::find()
                        ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                        ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                        ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                        ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                        ->andWhere(' DATE(`order_created_at`) = DATE(NOW() - INTERVAL 4 DAY) ')
                        ->sum('total_price');

                array_push($revenue_generated_chart_data_this_week, number_format((float) $number_of_all_revenue_generated_last_5_days_only, 2, '.', ''));

                $number_of_all_revenue_generated_last_4_days_only = Order::find()
                        ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                        ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                        ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                        ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                        ->andWhere(' DATE(`order_created_at`) = DATE(NOW() - INTERVAL 3 DAY) ')
                        ->sum('total_price');

                array_push($revenue_generated_chart_data_this_week, number_format((float) $number_of_all_revenue_generated_last_4_days_only, 2, '.', ''));


                $number_of_all_revenue_generated_last_3_days_only = Order::find()
                        ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                        ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                        ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                        ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                        ->andWhere(' DATE(`order_created_at`) = DATE(NOW() - INTERVAL 2 DAY) ')
                        ->sum('total_price');

                array_push($revenue_generated_chart_data_this_week, number_format((float) $number_of_all_revenue_generated_last_3_days_only, 2, '.', ''));


                $number_of_all_revenue_generated_last_2_days_only = Order::find()
                        ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                        ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                        ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                        ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                        ->andWhere(' DATE(`order_created_at`) = DATE(NOW() - INTERVAL 1 DAY) ')
                        ->sum('total_price');

                array_push($revenue_generated_chart_data_this_week, number_format((float) $number_of_all_revenue_generated_last_2_days_only, 2, '.', ''));

                $number_of_all_revenue_generated_today_only = Order::find()
                        ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                        ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                        ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                        ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                        ->andWhere(['DATE(order_created_at)' => new Expression('CURDATE()')])
                        ->sum('total_price');

                array_push($revenue_generated_chart_data_this_week, number_format((float) $number_of_all_revenue_generated_today_only, 2, '.', ''));


                $number_of_all_revenue_generated_this_week = 0;

                foreach ($revenue_generated_chart_data_this_week as $revenueGenerated) {
                    $number_of_all_revenue_generated_this_week += $revenueGenerated ? floatval($revenueGenerated) : 0;
                }


                //last month
                $number_of_all_revenue_generated_last_three_months_only = Order::find()
                        ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                        ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                        ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                        ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                        ->andWhere('YEAR(`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                        ->andWhere('MONTH(`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)')
                        ->sum('total_price');

                $number_of_all_revenue_generated_last_two_months_only = Order::find()
                        ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                        ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                        ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                        ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                        ->andWhere('YEAR(`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                        ->andWhere('MONTH(`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 2 MONTH)')
                        ->sum('total_price');

                array_push($revenue_generated_chart_data_last_month, $number_of_all_revenue_generated_last_two_months_only ? number_format((float) $number_of_all_revenue_generated_last_two_months_only, 2, '.', '') : 0);

                $number_of_all_revenue_generated_last_month_only = Order::find()
                        ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                        ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                        ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                        ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                        ->andWhere('YEAR(`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                        ->andWhere('MONTH(`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
                        ->sum('total_price');

                array_push($revenue_generated_chart_data_last_month, $number_of_all_revenue_generated_last_month_only ? number_format((float) $number_of_all_revenue_generated_last_month_only, 2, '.', '') : 0);

                //last 3 months
                array_push($revenue_generated_chart_data_last_three_months, $number_of_all_revenue_generated_last_three_months_only ? number_format((float) $number_of_all_revenue_generated_last_three_months_only, 2, '.', '') : 0);
                array_push($revenue_generated_chart_data_last_three_months, $number_of_all_revenue_generated_last_month_only ? number_format((float) $number_of_all_revenue_generated_last_month_only, 2, '.', '') : 0);

                $number_of_all_revenue_generated_current_month_only = Order::find()
                        ->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                        ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                        ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                        ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                        ->andWhere('YEAR(`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                        ->andWhere('MONTH(`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 0 MONTH)')
                        ->sum('total_price');

                array_push($revenue_generated_chart_data_last_three_months, $number_of_all_revenue_generated_current_month_only ? number_format((float) $number_of_all_revenue_generated_current_month_only, 2, '.', '') : 0);


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
            } else {

                return $this->redirect(['real-time-orders',
                            'restaurant_uuid' => $managedRestaurant->restaurant_uuid
                ]);
            }
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

        return $this->redirect('https://plugn.io/');
    }

    /**
     * Displays signup page.
     *
     * @return mixed
     */
    public function actionSignup() {

        $this->layout = 'login';

        $store_model = new Restaurant();
        $store_model->setScenario(Restaurant::SCENARIO_CREATE_STORE_BY_AGENT);

        $agent_model = new Agent();
        if ($agent_model->load(Yii::$app->request->post()) && $store_model->load(Yii::$app->request->post())) {

            $store_model->restaurant_email = $agent_model->agent_email;

            if ($agent_model->validate() && $store_model->validate() && $agent_model->save() && $store_model->save()) {

                $assignment_agent_model = new AgentAssignment();
                $assignment_agent_model->agent_id = $agent_model->agent_id;
                $assignment_agent_model->assignment_agent_email = $agent_model->agent_email;
                $assignment_agent_model->role = AgentAssignment::AGENT_ROLE_OWNER;
                $assignment_agent_model->restaurant_uuid = $store_model->restaurant_uuid;

                if ($assignment_agent_model->save())
                    return $this->redirect(['login']);
            }
        }

        return $this->render('signup', [
                    'agent_model' => $agent_model,
                    'store_model' => $store_model
        ]);
    }

    public function actionThankYou() {
        $this->layout = 'landing';
        return $this->render('thankYou');
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset() {

        $this->layout = 'login';


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
        $this->layout = 'login';


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
