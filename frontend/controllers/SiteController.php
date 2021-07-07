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
use common\models\BusinessLocation;
use common\models\Restaurant;
use common\models\OrderItem;
use common\models\Category;
use common\models\Order;
use common\models\Plan;
use common\models\PaymentMethod;
use common\models\Subscription;
use common\models\AgentAssignment;
use common\models\Item;
use common\models\RestaurantPaymentMethod;
use common\models\Customer;
use common\models\SubscriptionPayment;
use yii\db\Expression;
use yii\helpers\Url;
use common\components\TapPayments;
use yii\web\NotFoundHttpException;

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
                        'actions' => ['login', 'error',  'index', 'signup', 'thank-you', 'request-password-reset', 'reset-password'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'redirect-to-store-domain',  'check-for-new-orders',  'domains',  'downgrade-to-free-plan', 'confirm-plan', 'promote-to-open', 'connect-domain', 'promote-to-close', 'callback', 'vendor-dashboard', 'real-time-orders', 'mark-as-busy', 'mark-as-open'],
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

                if (Yii::$app->user->identity->isOwner($managedRestaurant->restaurant_uuid)) {
                    return $this->redirect(['vendor-dashboard',
                                'id' => $managedRestaurant->restaurant_uuid
                    ]);
                } else {
                    return $this->redirect(['real-time-orders',
                                'storeUuid' => $managedRestaurant->restaurant_uuid
                    ]);
                }
            }
        }
    }

    /**
     * Check for new orders
     */
    public function actionCheckForNewOrders($storeUuid) {

        $this->layout = false;
        $managedRestaurant = $this->findModel($storeUuid);
        $agentAssignment = $managedRestaurant->getAgentAssignments()->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])->one();

        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->searchPendingOrders(Yii::$app->request->queryParams, $storeUuid,$agentAssignment);

        return $this->render('incoming-orders-table', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider
        ]);
    }


    /**
     * View Stores domains
     *
     * @return mixed
     */
    public function actionRedirectToStoreDomain($storeUuid) {
        if ($managedRestaurant = $this->findModel($storeUuid)) {

            if($managedRestaurant->has_deployed)
            return $this->redirect($managedRestaurant->restaurant_domain);
            else{
              $this->layout = 'login';
              return $this->render('coming-soon', [
                          'restaurant_model' => $managedRestaurant
              ]);

            }


        }
    }


    /**
     * Displays  Real time orders
     *
     * @return mixed
     */
    public function actionConnectDomain($id) {
        if ($managedRestaurant = $this->findModel($id)) {

            $old_domain = $managedRestaurant->restaurant_domain;

            if ($managedRestaurant->load(Yii::$app->request->post())) {




                if ($old_domain != $managedRestaurant->restaurant_domain) {

                   $managedRestaurant->restaurant_domain = rtrim($managedRestaurant->restaurant_domain, '/');

                   if( $managedRestaurant->save()){
                     Yii::$app->session->setFlash('successResponse', "Congratulations you have successfully changed your domain name");

                     \Yii::$app->mailer->compose([
                                 'html' => 'domain-update-request',
                                     ], [
                                 'store_name' => $managedRestaurant->name,
                                 'new_domain' => $managedRestaurant->restaurant_domain,
                                 'old_domain' => $old_domain
                             ])
                             ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                             ->setTo(Yii::$app->params['supportEmail'])
                             ->setSubject('[Plugn] Agent updated DN')
                             ->send();

                   }

                }
            }
            return $this->render('connect-domain', [
                        'restaurant_model' => $managedRestaurant
            ]);
        }
    }


    public function actionConfirmPlan($id, $selectedPlanId) {
      if ($managedRestaurant = $this->findModel($id)) {



        $selectedPlan = Plan::findOne($selectedPlanId);

        $subscription_model = new Subscription();
        $subscription_model->restaurant_uuid = $managedRestaurant->restaurant_uuid;
        $subscription_model->plan_id = $selectedPlan->plan_id;

        $payment_methods = PaymentMethod::find()->all();

        if ($subscription_model->load(Yii::$app->request->post()) && $subscription_model->save()) {

        if($selectedPlan->price > 0){

          $payment = new SubscriptionPayment;
          $payment->restaurant_uuid = $managedRestaurant->restaurant_uuid;
          $payment->payment_mode = $subscription_model->payment_method_id == 1 ? TapPayments::GATEWAY_KNET : TapPayments::GATEWAY_VISA_MASTERCARD;
          $payment->subscription_uuid = $subscription_model->subscription_uuid; //subscription_uuid
          $payment->payment_amount_charged = $subscription_model->plan->price;
          $payment->payment_current_status = "Redirected to payment gateway";

          if ($payment->save()) {
              //Update payment_uuid in order
              $subscription_model->payment_uuid = $payment->payment_uuid;
              $subscription_model->save(false);


              // Redirect to payment gateway
              Yii::$app->tapPayments->setApiKeys(\Yii::$app->params['liveApiKey'], \Yii::$app->params['testApiKey']);

              $response = Yii::$app->tapPayments->createCharge(
                      "KWD",
                      "Upgrade $managedRestaurant->name's plan to " . $subscription_model->plan->name, // Description
                      'Plugn', //Statement Desc.
                       $payment->payment_uuid, // Reference
                       $subscription_model->plan->price,
                       $managedRestaurant->name,
                       $managedRestaurant->getAgents()->one()->agent_email,
                       $managedRestaurant->country->country_code,
                       $managedRestaurant->owner_number ? $managedRestaurant->owner_number : null,
                       0, //Comission
                      Url::to(['site/callback'], true),
                      $subscription_model->payment_method_id == 1 ? TapPayments::GATEWAY_KNET :  TapPayments::GATEWAY_VISA_MASTERCARD,
                      0
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
                      \Yii::error('[Payment Issue > Charge id is missing ]' . json_encode($responseContent), __METHOD__); // Log error faced by user
                  }

                  return $this->redirect($redirectUrl);
              } catch (\Exception $e) {

                  if ($payment)
                      Yii::error('[TAP Payment Issue > ]' . json_encode($payment->getErrors()), __METHOD__);

                  Yii::error('[TAP Payment Issue > Charge id is missing]' . json_encode($responseContent), __METHOD__);

                  $response = [
                      'operation' => 'error',
                      'message' => json_encode($responseContent)
                  ];
              }
          }

        }
      }


        return $this->render('confirm-plan', [
              'restaurant_model' => $managedRestaurant,
              'selectedPlan' => Plan::findOne($selectedPlanId),
              'subscription_model' => $subscription_model,
              'paymentMethods' => $payment_methods
        ]);

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
            if ($paymentRecord->payment_current_status == 'CAPTURED')
              Yii::$app->session->setFlash('success',$paymentRecord->plan->name .  print_r(' has been activated', true));
            else if ($paymentRecord->payment_current_status != 'CAPTURED')  //Failed Payment
            Yii::$app->session->setFlash('error', print_r('There seems to be an issue with your payment, please try again.', true));

            // Redirect back to current plan page
            return $this->redirect(['store/view-payment-methods',
                        'storeUuid' => $paymentRecord->restaurant_uuid
            ]);

        } catch (\Exception $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }


    /**
     * Displays  Real time orders
     *
     * @return mixed
     */
    public function actionRealTimeOrders($storeUuid) {

        $managedRestaurant = $this->findModel($storeUuid);
        $agentAssignment = $managedRestaurant->getAgentAssignments()->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])->one();


        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->searchPendingOrders(Yii::$app->request->queryParams, $storeUuid, $agentAssignment);

        return $this->render('real-time-orders', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'storeUuid' => $storeUuid
        ]);
    }

    /**
     * Displays vendor dashboard homepage.
     *
     * @return mixed
     */
    public function actionVendorDashboard($id) {

        if ($managedRestaurant = $this->findModel($id)) {
            if (Yii::$app->user->identity->isOwner($managedRestaurant->restaurant_uuid)) {

                $numberOfOrders = Order::find()->where(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->count();

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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
                        ->orWhere(['order_status' => Order::STATUS_CANCELED])
                        ->andWhere(['restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        // ->andWhere(['DATE(order_created_at)' => new Expression('CURDATE()')])
                        ->andWhere(new Expression("date(order_created_at) = date(NOW())"))
                        ->count();

                $number_of_all_orders_received_last_month = Order::find()
                        ->where(['order_status' => Order::STATUS_PENDING])
                        ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                        ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                        ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                        ->orWhere(['order_status' => Order::STATUS_ACCEPTED])
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
                            'numberOfOrders' => $numberOfOrders,
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
                            'storeUuid' => $managedRestaurant->restaurant_uuid
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
      //temp
      // return $this->redirect('https://plugn.io/');

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
        $store_model->version = 3;
        $store_model->setScenario(Restaurant::SCENARIO_CREATE_STORE_BY_AGENT);

        $agent_model = new Agent();
        $agent_model->setScenario(Agent::SCENARIO_CREATE_NEW_AGENT);

        if ($agent_model->load(Yii::$app->request->post()) && $store_model->load(Yii::$app->request->post())) {

            $store_model->restaurant_email = $agent_model->agent_email;
            $store_model->owner_first_name = $agent_model->agent_name;

            $store_model->name_ar = $store_model->name;





            if ($agent_model->validate() && $store_model->validate() && $store_model->save() && $agent_model->save()) {


                //Create a catrgory for a store by default named "Products". so they can get started adding products without having to add category first
                $category_model = new Category();
                $category_model->restaurant_uuid = $store_model->restaurant_uuid;
                $category_model->title = 'Products';
                $category_model->title_ar = 'منتجات';
                $category_model->save();

                //Create a business Location for a store by default named "Main Branch".
                $business_location_model = new BusinessLocation();
                $business_location_model->restaurant_uuid = $store_model->restaurant_uuid;
                $business_location_model->country_id = $store_model->country_id;
                $business_location_model->support_pick_up = 1;
                $business_location_model->business_location_name = 'Main Branch';
                $business_location_model->business_location_name_ar = 'الفرع الرئيسي';
                $business_location_model->save();

                //Enable cash by default
                $payments_method = new RestaurantPaymentMethod();
                $payments_method->payment_method_id = 3; //Cash
                $payments_method->restaurant_uuid = $store_model->restaurant_uuid;
                $payments_method->save();


                //Enable cash by default
                $payments_method = new RestaurantPaymentMethod();
                $payments_method->payment_method_id = 3; //Cash
                $payments_method->restaurant_uuid = $store_model->restaurant_uuid;
                $payments_method->save();


                $assignment_agent_model = new AgentAssignment();
                $assignment_agent_model->agent_id = $agent_model->agent_id;
                $assignment_agent_model->assignment_agent_email = $agent_model->agent_email;
                $assignment_agent_model->role = AgentAssignment::AGENT_ROLE_OWNER;
                $assignment_agent_model->restaurant_uuid = $store_model->restaurant_uuid;

                if ($assignment_agent_model->save()) {
                    $model = new LoginForm();
                    $model->email = $agent_model->agent_email;
                    $model->password = $agent_model->tempPassword;

                    if ($managedRestaurant = $model->login()) {
                      \Yii::info("[New Store Signup] " . $store_model->name . " has just joined Plugn", __METHOD__);

                  if(YII_ENV == 'prod') {
                      $full_name = explode(' ', $agent_model->agent_name);
                      $firstname = $full_name[0];
                      $lastname = array_key_exists(1, $full_name) ? $full_name[1] : null;

                      \Segment::init('2b6WC3d2RevgNFJr9DGumGH5lDRhFOv5');
                      \Segment::track([
                          'userId' => $store_model->restaurant_uuid,
                          'event' => 'Store Created',
                          'type' => 'track',
                          'properties' => [
                               'first_name' => trim($firstname),
                               'last_name' => trim($lastname),
                               'store_name' => $store_model->name,
                               'phone_number' => $store_model->owner_number,
                               'email' => $agent_model->agent_email,
                               'store_url' => $store_model->restaurant_domain
                          ]
                      ]);

                      Yii::$app->session->setFlash('storeCreated');

                    }

                        return $this->redirect(['site/vendor-dashboard', 'id' => $managedRestaurant->restaurant_uuid]);
                    } else {
                        $model->password = '';

                        return $this->render('login', [
                                    'model' => $model,
                        ]);
                    }
                }
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
        if ($model->load(Yii::$app->request->post()) ) {


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
        if (($model = Yii::$app->accountManager->getManagedAccount($id)) ) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
