<?php
namespace backend\controllers;

use agent\models\Country;
use agent\models\PaymentMethod;
use common\models\Order;
use Yii;
use backend\models\Admin;
use common\models\Payment;
use yii\db\Expression;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;


class ReportController extends Controller
{
    public $enableCsrfValidation = false;

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => Yii::$app->user->identity && Yii::$app->user->identity->admin_role != Admin::ROLE_CUSTOMER_SERVICE_AGENT,
                        'actions' => ['index', 'download'],
                        'roles' => ['@'],
                    ],
                    [//allow authenticated users only
                        'allow' => true,
                        'roles' => ['@'],
                    ],
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

    public function actionIndex() {

        $countries = ArrayHelper::map(Country::find()->all(), 'country_id', 'country_name');
        $countries = ["0" => "All"] + $countries;

        return $this->render('fees', [
            "countries" => $countries
        ]);
    }

    /**
     * plugn commission report
     * @return void
     */
    public function actionDownload() {

        ini_set('max_execution_time', 60*2);//2 min

        $date_start = Yii::$app->request->get('date_start');
        $date_end = Yii::$app->request->get('date_end');
        $country_id = Yii::$app->request->get('country_id');

        $currencies = Payment::find()
            ->filterByDateRange($date_start, $date_end)
            //, SUM(payment_gateway_fee) as payment_gateway_fees,
            //                SUM(plugn_fee) as plugn_fees, SUM(partner_fee) as partner_fees"
            ->select(new Expression("currency_code"))
            ->joinWith(['order'], false, 'inner join')
            ->filterByCountry($country_id)
            ->filterPaid()
            ->groupBy('order.currency_code')
            ->asArray()
            //->limit(1)
            ->all();

        $models = $columns = [];

        foreach ($currencies as $currency) {

            $models[$currency['currency_code']] = Payment::find()
                ->filterByDateRange($date_start, $date_end)
                //, SUM(payment_gateway_fee) as payment_gateway_fees,
                //                SUM(plugn_fee) as plugn_fees, SUM(partner_fee) as partner_fees"
                ->joinWith(['order', 'restaurant'])
                ->filterByCountry($country_id)
                ->filterPaid()
                ->andWhere(['order.currency_code' => $currency['currency_code']])
                //->asArray()
                ->all();

            $columns[$currency['currency_code']] = [
                [
                    'header' => 'Date',
                    "format" => "raw",
                    "value" => function($data) {
                        return  $data->payment_updated_at;
                    }
                ],
                [
                    'header' => 'Store name',
                    "format" => "raw",
                    "value" => function($data) {
                        return  $data->restaurant?$data->restaurant->name: null;
                    }
                ],
                [
                    'header' => 'Payment Method',
                    "format" => "raw",
                    "value" => function($data) {
                        return  $data->payment_mode;
                    }
                ],
                [
                    'header' => 'Revenue',
                    "format" => "raw",
                    "value" => function($data) {
                        return  $data->payment_amount_charged;
                    }
                ],
                [
                    'header' => 'Our commission',
                    "format" => "raw",
                    "value" => function($data) {
                        return  $data->plugn_fee;
                    }
                ],
                [
                    'header' => 'Payment gateway charge',
                    "format" => "raw",
                    "value" => function($data) {
                        return  $data->payment_gateway_fee;
                    }
                ]
            ];
        }

        \common\components\PhpExcel::export([
            'isMultipleSheet' => true,
            "asAttachment" => true,
            'mode' => 'export', //default value as 'export'
            'models' => $models,
            'columns' => $columns
        ]);
    }

    /**
     * cash on delivery order report form
     * @return void
     */
    public function actionCashOnDelivery()
    {
        $countries = ArrayHelper::map(Country::find()->all(), 'country_id', 'country_name');
        $countries = ["0" => "All"] + $countries;

        return $this->render('cod', [
            "countries" => $countries
        ]);
    }

    /**
     * Download cash on delivery order report
     * @return void
     */
    public function actionDownloadCashOnDelivery() {

        ini_set('max_execution_time', 60*2);//2 min

        $date_start = Yii::$app->request->get('date_start');
        $date_end = Yii::$app->request->get('date_end');

        $country_id = Yii::$app->request->get('country_id');
        $restaurant_uuid = Yii::$app->request->get('restaurant_uuid');

        $payment_method = PaymentMethod::find()
            ->andWhere(['payment_method_code' => PaymentMethod::CODE_CASH])
            ->one();

        $currencies = Order::find()
            ->filterByDateRange($date_start, $date_end)
            //, SUM(payment_gateway_fee) as payment_gateway_fees,
            //                SUM(plugn_fee) as plugn_fees, SUM(partner_fee) as partner_fees"
            ->select(new Expression("currency_code"))
            ->filterByCountry($country_id)
            ->filterByStore($restaurant_uuid)
            ->filterByPaymentMethod($payment_method->payment_method_id)
            ->placedOrders()
            ->groupBy('order.currency_code')
            ->asArray()
            //->limit(1)
            ->all();

        $models = $columns = [];

        foreach ($currencies as $currency) {

            $models[$currency['currency_code']] = Order::find()
                ->filterByDateRange($date_start, $date_end)
                //, SUM(payment_gateway_fee) as payment_gateway_fees,
                //                SUM(plugn_fee) as plugn_fees, SUM(partner_fee) as partner_fees"
                ->filterByCountry($country_id)
                ->filterByStore($restaurant_uuid)
                ->placedOrders()
                ->andWhere(['order.currency_code' => $currency['currency_code']])
                //->asArray()
                ->joinWith(['invoiceItem'])
                ->all();

            $columns[$currency['currency_code']] = [
                [
                    'header' => 'Date',
                    "format" => "raw",
                    "value" => function($data) {
                        return  $data->order_created_at;
                    }
                ],
                [
                    'header' => 'Store name',
                    "format" => "raw",
                    "value" => function($data) {
                        return  $data->restaurant?$data->restaurant->name: null;
                    }
                ],
                /*[
                    'header' => 'Payment Method',
                    "format" => "raw",
                    "value" => function($data) {
                        return  $data->payment_method_name;
                    }
                ],*/
                [
                    'header' => 'Order UUID',
                    "format" => "raw",
                    "value" => function($data) {
                        return  $data->order_uuid;
                    }
                ],
                [
                    'header' => 'Revenue',
                    "format" => "raw",
                    "value" => function($data) {
                        return  $data->total_price * $data->currency_rate;
                    }
                ],
                [
                    'header' => 'Our commission',
                    "format" => "raw",
                    "value" => function($data) {
                        return  $data->invoiceItem? $data->invoiceItem->total * $data->currency_rate: 0;
                    }
                ],
            ];
        }

        \common\components\PhpExcel::export([
            'isMultipleSheet' => true,
            "asAttachment" => true,
            'mode' => 'export', //default value as 'export'
            'models' => $models,
            'columns' => $columns
        ]);
    }
}