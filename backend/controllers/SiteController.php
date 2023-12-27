<?php
namespace backend\controllers;

use backend\models\RestaurantInvoice;
use common\models\PaymentGatewayQueue;
use common\models\Queue;
use common\models\Restaurant;
use common\models\RestaurantDomainRequest;
use common\models\Subscription;
use common\models\Ticket;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\LoginForm;
use backend\models\Admin;
use yii\helpers\Url;

/**
 * Site controller
 */
class SiteController extends Controller
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
                        'actions' => ['login', 'error', 'callback-auth0', 'login-auth0'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index'],
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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $openTickets = Ticket::find()
            ->andWhere(['!=', 'ticket_status', Ticket::STATUS_COMPLETED])
            ->count();

        $draft = RestaurantInvoice::find()
            ->andWhere(['invoice_status' => RestaurantInvoice::STATUS_UNPAID])
            ->count();

        $pending = RestaurantInvoice::find()
            ->andWhere(['invoice_status' => RestaurantInvoice::STATUS_LOCKED])
            ->count();

        $paid = RestaurantInvoice::find()
            ->andWhere(['invoice_status' => RestaurantInvoice::STATUS_PAID])
            ->count();

        $failedInQueue = Queue::find()
                
            ->andWhere ([
                'queue_status' => Queue::QUEUE_STATUS_FAILED
            ])->count ();

        $holdInQueue = Queue::find()->andWhere ([
            'queue_status' => Queue::QUEUE_STATUS_HOLD
        ])->count ();

        $pendingInQueue = Queue::find()->andWhere ([
            'queue_status' => Queue::QUEUE_STATUS_PENDING
        ])->count ();

        $notPublished = Restaurant::find()
            ->filterNotPublished()
            ->count();

        $failedInPaymentQueue = PaymentGatewayQueue::find()->andWhere ([
            'queue_status' => PaymentGatewayQueue::QUEUE_STATUS_FAILED
        ])->count ();

        $pendingInPaymentQueue = PaymentGatewayQueue::find()->andWhere ([
            'queue_status' => PaymentGatewayQueue::QUEUE_STATUS_PENDING
        ])->count ();

        $pendingDomain = RestaurantDomainRequest::find()
            ->andWhere(['status' => RestaurantDomainRequest::STATUS_PENDING])
            ->count();

        $purchaseDomain = RestaurantDomainRequest::find()
            ->andWhere(['status' => RestaurantDomainRequest::STATUS_PURCHASED])
            ->count();

        $premiumStores = Subscription::find()
            ->filterPremium()
            ->count();

        return $this->render('index', [
            'draft' => $draft,
            'pending' => $pending,
            'paid' => $paid,
            'openTickets' => $openTickets,
            'notPublished' => $notPublished,
            "failedInQueue" => $failedInQueue,
            "premiumStores" => $premiumStores,
            "holdInQueue" => $holdInQueue,
            "pendingInQueue" => $pendingInQueue,
            "failedInPaymentQueue" => $failedInPaymentQueue,
            "pendingInPaymentQueue" => $pendingInPaymentQueue,
            "pendingDomain" => $pendingDomain,
            "purchaseDomain" => $purchaseDomain
        ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionLoginAuth0()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        Yii::$app->auth0->logout();

        $loginUrl = Yii::$app->auth0->login(Url::to(['site/callback-auth0'], true));;

        return $this->redirect($loginUrl);
    } 

    public function actionCallbackAuth0()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $exchange = Yii::$app->auth0->exchange(Url::to(['site/index'], true));

        if(!$exchange)
        {
            return $this->redirect(['site/login']);
        }

        $session = Yii::$app->auth0->getCredentials();

        if ($session) {

            $user = Admin::findByEmail(isset($session->user)? $session->user['email']:  $session['email']);

            if (!$user) {
                Yii::$app->session->addFlash('error', "Email not registered as admin");
                return $this->redirect(['site/index']);
            }

            if (Yii::$app->user->login($user, 3600 * 24 * 30)) {//
                return $this->redirect(['site/index']);
            }
        }

        return $this->redirect(['site/index']);
    }

    public function actionProcessAuth0()
    {
        $session = Yii::$app->auth0->getCredentials();

        if ($session === null) {
            // The user isn't logged in.
            return $this->redirect(['site/login']);
        }

        /**
         * The user is logged in.
         * (
        [given_name] => Kathrecha
        [family_name] => Krushn
        [nickname] => kathrechakrushn
        [name] => Kathrecha Krushn
        [picture] => https://lh3.googleusercontent.com/a/ALm5wu1wHV3MEVxErjfznzjR3dlfY9-kfz3XzXS-sL0hvA=s96-c
        [locale] => en
        [email] => kathrechakrushn@gmail.com
        [email_verified] => 1
        ) */

        $user = Admin::findByEmail($session->user);

        if(!$user)
        {
            /*$user = new User();
            $user->username = isset($session->user['nickname'])? $session->user['nickname']: $session->user['name'];
            $user->email = $session->user['email'];
            $user->bank_account_name = $session->user['name'];

            //$user->email_verified = $session->user['email_verified'];

            if(!$user->save())
            {*/
            Yii::$app->session->addFlash('error', "Email not registered as admin");
            return $this->redirect(['site/index']);
        }

        if(Yii::$app->user->login($user, 3600 * 24 * 30))
        {
            return $this->goBack();
        }

        return $this->redirect(['site/login']);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->auth0->logout();
        
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
