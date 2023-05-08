<?php
namespace backend\controllers;

use agent\models\Country;
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

        \moonland\phpexcel\Excel::export([
            'isMultipleSheet' => true,
            "asAttachment" => true,
            'mode' => 'export', //default value as 'export'
            'models' => $models,
            'columns' => $columns
        ]);
    }
}