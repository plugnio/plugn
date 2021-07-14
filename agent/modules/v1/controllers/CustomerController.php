<?php

namespace agent\modules\v1\controllers;

use Yii;
use yii\db\Expression;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use agent\models\Customer;
use agent\models\Order;

class CustomerController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors ();

        // remove authentication filter for cors to work
        unset($behaviors['authenticator']);

        // Allow XHR Requests from our different subdomains and dev machines
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className (),
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
            'class' => \yii\filters\auth\HttpBearerAuth::className (),
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
        $actions = parent::actions ();
        $actions['options'] = [
            'class' => 'yii\rest\OptionsAction',
            // optional:
            'collectionOptions' => ['GET', 'POST', 'HEAD', 'OPTIONS'],
            'resourceOptions' => ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
        ];
        return $actions;
    }


    /**
     * Get all store's Customers
     * @param type $id
     * @param type $store_uuid
     * @return type
     */
    public function actionList($store_uuid)
    {

        $keyword = Yii::$app->request->get ('keyword');

        Yii::$app->accountManager->getManagedAccount ($store_uuid);

        $query = Customer::find ();

        if ($keyword) {
            $query->where (['like', 'customer_name', $keyword]);
            $query->orWhere (['like', 'customer_phone_number', $keyword]);
            $query->orWhere (['like', 'customer_email', $keyword]);
        }

        $query->andWhere (['restaurant_uuid' => $store_uuid]);

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Return customer detail
     * @param type $store_uuid
     * @param type $order_uuid
     * @return type
     */
    public function actionDetail($store_uuid, $customer_id)
    {

        if (Yii::$app->accountManager->getManagedAccount ($store_uuid)) {

            $customer = Customer::find ()
                ->andWhere ([
                    'restaurant_uuid' => $store_uuid,
                    'customer_id' => $customer_id
                ])
                ->one ();

            return $customer;
        }
    }


    /**
     * Return a List of all customers Orders
     * @param type $store_uuid
     * @param type $customer_id
     * @return type
     */
    public function actionListAllCustomerOrders($store_uuid, $customer_id)
    {

        if (Yii::$app->accountManager->getManagedAccount ($store_uuid)) {

            $query = Order::find ()
                ->andWhere (['restaurant_uuid' => $store_uuid, 'customer_id' => $customer_id])
                ->orderBy (['order_created_at' => SORT_DESC]);

            return new ActiveDataProvider([
                'query' => $query
            ]);


        }

    }

    /**
     * Export customers data to excel
     * @return mixed
     */
    public function actionExportToExcel()
    {
        $restaurant_model = Yii::$app->accountManager->getManagedAccount ();

        $start_date = Yii::$app->request->get('start_date');
        $end_date = Yii::$app->request->get('end_date');

        $query = \common\models\Customer::find ()
            ->andWhere (['restaurant_uuid' => $restaurant_model->restaurant_uuid])
            ->orderBy (['customer_created_at' => SORT_DESC]);

        if($start_date && $end_date) {
            $query->andWhere (new Expression('DATE(customer.customer_created_at) >= DATE("'.$start_date.'") AND 
                DATE(customer.customer_created_at) <= DATE("'.$end_date.'")'));
        }

        $model = $query->all();

        header ('Access-Control-Allow-Origin: *');
        header ("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header ("Content-Disposition: attachment;filename=\"customers.xlsx\"");
        header ("Cache-Control: max-age=0");

        \moonland\phpexcel\Excel::export ([
            'isMultipleSheet' => false,
            'models' => $model,
            'columns' => [
                'customer_name',
                'customer_email',
                [
                    'attribute' => 'customer_phone_number',
                    "format" => "raw",
                    "value" => function ($model) {
                        return str_replace (' ', '', strval ($model->customer_phone_number));
                    }
                ],
                [
                    'attribute' => 'Total spent',
                    "format" => "raw",
                    "value" => function ($data) {
                        $total_spent = $data->getOrders ()
                            ->andWhere ([
                                'NOT IN',
                                'order_status', [
                                    Order::STATUS_DRAFT,
                                    Order::STATUS_ABANDONED_CHECKOUT,
                                    Order::STATUS_REFUNDED,
                                    Order::STATUS_PARTIALLY_REFUNDED,
                                    Order::STATUS_CANCELED
                                ]
                            ])
                            ->sum ('total_price');

                        $total_spent = \Yii::$app->formatter->asDecimal ($total_spent ? $total_spent : 0, 3);

                        return Yii::$app->formatter->asCurrency ($total_spent ? $total_spent : 0, $data->currency->code);
                    }
                ],
                [
                    'attribute' => 'Number of orders',
                    "format" => "raw",
                    "value" => function ($model) {
                        return $model->getOrders ()
                            ->andWhere ([
                                'NOT IN',
                                'order_status', [
                                    Order::STATUS_DRAFT,
                                    Order::STATUS_ABANDONED_CHECKOUT,
                                    Order::STATUS_REFUNDED,
                                    Order::STATUS_PARTIALLY_REFUNDED,
                                    Order::STATUS_CANCELED
                                ]
                            ])
                            ->count ();
                    }
                ],
                [
                    'header' => Yii::t('agent', 'Account created at'),
                    "format" => "raw",
                    "value" => function ($data) {
                        return $data->customer_created_at;
                    }
                ]
            ]
        ]);
    }
}
