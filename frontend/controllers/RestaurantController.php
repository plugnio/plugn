<?php

namespace frontend\controllers;

use Yii;
use common\models\Restaurant;
use common\models\Order;
use common\models\Item;
use yii\db\Expression;
use common\models\Customer;
use common\models\RestaurantTheme;
use common\models\AgentAssignment;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RestaurantController implements the CRUD actions for Restaurant model.
 */
class RestaurantController extends Controller {

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
                    [//allow authenticated users only
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }




    public function actionExportTodaySoldItems($restaurantUuid) {
        if ($managedRestaurant = Yii::$app->accountManager->getManagedAccount($restaurantUuid)) {


            $today_sold_item = Item::find()
                    ->joinWith(['orderItems', 'orderItems.order'])
                    ->where(['order.order_status' => Order::STATUS_PENDING])
                    ->orWhere(['order.order_status' => Order::STATUS_BEING_PREPARED])
                    ->orWhere(['order.order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                    ->orWhere(['order.order_status' => Order::STATUS_COMPLETE])
                    ->orWhere(['order_status' => Order::STATUS_CANCELED])
                    ->andWhere(['order.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                    ->andWhere(['DATE(order.order_created_at)' => new Expression('CURDATE()')])
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
                        'label' => 'Sold items',
                        'format' => 'html',
                        'value' => function ($data) {

                            return $data->getTodaySoldUnits();
                        },
                    ],
                ],
            ]);
        }
    }


    public function actionExportLastSevenDaysSoldItems($restaurantUuid) {
        if ($managedRestaurant = Yii::$app->accountManager->getManagedAccount($restaurantUuid)) {


            $this_week_sold_item = Item::find()
                          ->joinWith(['orderItems', 'orderItems.order'])
                          ->where(['order.order_status' => Order::STATUS_PENDING])
                          ->orWhere(['order.order_status' => Order::STATUS_BEING_PREPARED])
                          ->orWhere(['order.order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                          ->orWhere(['order.order_status' => Order::STATUS_COMPLETE])
                          ->orWhere(['order_status' => Order::STATUS_CANCELED])
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
                      'attribute' => 'Sold items',
                        'format' => 'html',
                        'value' => function ($data) {
                            return $data->getThisWeekSoldUnits();
                        },
                    ],
                ],
            ]);
        }
    }


    public function actionExportCurrentMonthSoldItems($restaurantUuid) {

            if ($managedRestaurant = Yii::$app->accountManager->getManagedAccount($restaurantUuid)) {


                $current_month_sold_item = Item::find()
                        ->joinWith(['orderItems', 'orderItems.order'])
                        ->where(['order.order_status' => Order::STATUS_PENDING])
                        ->orWhere(['order.order_status' => Order::STATUS_BEING_PREPARED])
                        ->orWhere(['order.order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                        ->orWhere(['order.order_status' => Order::STATUS_COMPLETE])
                        ->orWhere(['order_status' => Order::STATUS_CANCELED])
                        ->andWhere(['order.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                        ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 0 MONTH)')
                        ->all();

                header('Access-Control-Allow-Origin: *');
                header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
                header("Content-Disposition: attachment;filename=\"sold-items.xlsx\"");
                header("Cache-Control: max-age=0");

                \moonland\phpexcel\Excel::export([
                    'isMultipleSheet' => false,
                    'models' => $current_month_sold_item,
                    'columns' => [
                        'item_name',
                        [
                          'attribute' => 'Sold items',
                            'format' => 'html',
                            'value' => function ($data) {

                                return $data->getCurrentMonthSoldUnits();
                            },
                        ],
                    ],
                ]);
            }
        }

    public function actionExportLastMonthSoldItems($restaurantUuid) {

            if ($managedRestaurant = Yii::$app->accountManager->getManagedAccount($restaurantUuid)) {


                $last_month_sold_item = Item::find()
                        ->joinWith(['orderItems', 'orderItems.order'])
                        ->where(['order.order_status' => Order::STATUS_PENDING])
                        ->orWhere(['order.order_status' => Order::STATUS_BEING_PREPARED])
                        ->orWhere(['order.order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                        ->orWhere(['order.order_status' => Order::STATUS_COMPLETE])
                        ->orWhere(['order_status' => Order::STATUS_CANCELED])
                        ->andWhere(['order.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                        ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
                        ->all();

                header('Access-Control-Allow-Origin: *');
                header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
                header("Content-Disposition: attachment;filename=\"sold-items.xlsx\"");
                header("Cache-Control: max-age=0");


                \moonland\phpexcel\Excel::export([
                    'isMultipleSheet' => false,
                    'models' => $last_month_sold_item,
                    'columns' => [
                        'item_name',
                        'order.order_created_at',
                        [
                          'attribute' => 'Sold items',
                            'format' => 'html',
                            'value' => function ($data) {

                                return $data->getLastMonthSoldUnits();
                            },
                        ],
                    ],
                ]);
            }
        }

    public function actionExportLastThreeMonthsSoldItems($restaurantUuid) {

            if ($managedRestaurant = Yii::$app->accountManager->getManagedAccount($restaurantUuid)) {

                $last_three_month_sold_item = Item::find()
                        ->joinWith(['orderItems', 'orderItems.order'])
                        ->where(['order.order_status' => Order::STATUS_PENDING])
                        ->orWhere(['order.order_status' => Order::STATUS_BEING_PREPARED])
                        ->orWhere(['order.order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                        ->orWhere(['order.order_status' => Order::STATUS_COMPLETE])
                        ->orWhere(['order_status' => Order::STATUS_CANCELED])
                        ->andWhere(['order.restaurant_uuid' => $managedRestaurant->restaurant_uuid])
                        ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                        ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)')
                        ->all();

                header('Access-Control-Allow-Origin: *');
                header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
                header("Content-Disposition: attachment;filename=\"sold-items.xlsx\"");
                header("Cache-Control: max-age=0");


                \moonland\phpexcel\Excel::export([
                    'isMultipleSheet' => false,
                    'models' => $last_three_month_sold_item,
                    'columns' => [
                        'item_name',
                        [
                          'attribute' => 'Sold items',
                            'format' => 'html',
                            'value' => function ($data) {

                                return $data->getLastThreeMonthSoldUnits();
                            },
                        ],
                    ],
                ]);
            }
        }



    /**
     * @return mixed
     */
    public function actionAnalytic($restaurantUuid) {

        $model = $this->findModel($restaurantUuid);


        $revenue_generated_chart_data = [];
        $months = [];

        $revenue_generated_last_five_months_month = Order::find()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 5 MONTH)')
                ->sum('total_price');

        $lastFiveMonths = date('M', strtotime('-5 months'));

        array_push($revenue_generated_chart_data, number_format($revenue_generated_last_five_months_month));

        array_push($months, $lastFiveMonths);


        $revenue_generated_last_four_months_month = Order::find()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 4 MONTH)')
                ->sum('total_price');

        $lastFoureMonths = date('M', strtotime('-4 months'));

        array_push($revenue_generated_chart_data, number_format($revenue_generated_last_four_months_month,3));

        array_push($months, $lastFoureMonths);

        $revenue_generated_last_three_months_month = Order::find()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)')
                ->sum('total_price');

        $lastThreeMonths = date('M', strtotime('-3 months'));

        array_push($revenue_generated_chart_data, number_format($revenue_generated_last_three_months_month,3));

        array_push($months, $lastThreeMonths);

        $revenue_generated_last_three_months_month = Order::find()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)')
                ->sum('total_price');

        $lastTwoMonths = date('M', strtotime('-2 months'));

        array_push($revenue_generated_chart_data, number_format($revenue_generated_last_three_months_month,3));

        array_push($months, $lastTwoMonths);

        $revenue_generated_last_month = Order::find()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->sum('total_price');

        $lastMonth = date('M', strtotime('-1 months'));

        array_push($revenue_generated_chart_data, number_format($revenue_generated_last_month,3));

        array_push($months, $lastMonth);

        $revenue_generated_current_month = Order::find()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 0 MONTH)')
                ->sum('total_price');

        $currentMonth = date('M');

        array_push($revenue_generated_chart_data, (int) ($revenue_generated_current_month));

        array_push($months, $currentMonth);


        $order_recevied_chart_data = [];

        $order_recevied_last_five_months_month = Order::find()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 5 MONTH)')
                ->count();

        array_push($order_recevied_chart_data, (int) ($order_recevied_last_five_months_month));


        $order_recevied_last_four_months_month = Order::find()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 4 MONTH)')
                ->count();


        array_push($order_recevied_chart_data, (int) ($order_recevied_last_four_months_month));


        $order_recevied_last_three_months_month = Order::find()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)')
                ->count();

        array_push($order_recevied_chart_data, (int) ($order_recevied_last_three_months_month));

        $order_recevied_last_three_months_month = Order::find()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)')
                ->count();

        array_push($order_recevied_chart_data, (int) ($order_recevied_last_three_months_month));

        $order_recevied_last_month = Order::find()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->count();


        array_push($order_recevied_chart_data, (int) ($order_recevied_last_month));


        $order_recevied_current_month = Order::find()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 0 MONTH)')
                ->count();


        array_push($order_recevied_chart_data, (int) ($order_recevied_current_month));





        $customer_gained_chart_data = [];

        $customer_gained_last_five_months_month = Customer::find()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->andWhere('YEAR(`customer`.`customer_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->andWhere('MONTH(`customer`.`customer_created_at`) = MONTH(CURRENT_DATE - INTERVAL 5 MONTH)')
                ->count();

        array_push($customer_gained_chart_data, (int) ($customer_gained_last_five_months_month));


        $customer_gained_last_four_months_month = Customer::find()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->andWhere('YEAR(`customer`.`customer_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->andWhere('MONTH(`customer`.`customer_created_at`) = MONTH(CURRENT_DATE - INTERVAL 4 MONTH)')
                ->count();


        array_push($customer_gained_chart_data, (int) ($customer_gained_last_four_months_month));


        $customer_gained_last_three_months_month = Customer::find()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->andWhere('YEAR(`customer`.`customer_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->andWhere('MONTH(`customer`.`customer_created_at`) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)')
                ->count();

        array_push($customer_gained_chart_data, (int) ($customer_gained_last_three_months_month));

        $customer_gained_last_three_months_month = Customer::find()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->andWhere('YEAR(`customer`.`customer_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->andWhere('MONTH(`customer`.`customer_created_at`) = MONTH(CURRENT_DATE - INTERVAL 3 MONTH)')
                ->count();

        array_push($customer_gained_chart_data, (int) ($customer_gained_last_three_months_month));

        $customer_gained_last_month = Customer::find()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->andWhere('YEAR(`customer`.`customer_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->andWhere('MONTH(`customer`.`customer_created_at`) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->count();


        array_push($customer_gained_chart_data, (int) ($customer_gained_last_month));


        $customer_gained_current_month = Customer::find()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->andWhere('YEAR(`customer`.`customer_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->andWhere('MONTH(`customer`.`customer_created_at`) = MONTH(CURRENT_DATE - INTERVAL 0 MONTH)')
                ->count();


        array_push($customer_gained_chart_data, (int) ($customer_gained_current_month));


        $most_selling_items_chart_data = [];
        $number_of_sold_items_chart_data = [];



        $sold_items = \common\models\Item::find()
                ->joinWith(['orderItems', 'order'])
                ->where(['order_status' => Order::STATUS_PENDING])
                ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                ->orWhere(['order_status' => Order::STATUS_CANCELED])
                ->where(['item.restaurant_uuid' => $model->restaurant_uuid])
                ->orderBy(['order_item.qty' => SORT_ASC])
                ->all();


        $most_selling_items_counter = 0;

        foreach ($sold_items as $key => $item) {
            if ($most_selling_items_counter < 5) {
                $most_selling_items_counter++;
                array_push($most_selling_items_chart_data, $item->item_name);
                array_push($number_of_sold_items_chart_data, $item->getCurrentMonthSoldUnits() ? $item->getCurrentMonthSoldUnits() : 0);
            }
        }



        return $this->render('analytic', [
                    'model' => $model,
                    'months' => $months,
                    'most_selling_items_chart_data' => $most_selling_items_chart_data,
                    'customer_gained_chart_data' => $customer_gained_chart_data,
                    'number_of_sold_items_chart_data' => $number_of_sold_items_chart_data,
                    'revenue_generated_chart_data' => $revenue_generated_chart_data,
                    'order_recevied_chart_data' => $order_recevied_chart_data
        ]);
    }

    /**
     * Lists all Restaurant models.
     * @return mixed
     */
    public function actionIndex($restaurantUuid) {

        $model = $this->findModel($restaurantUuid);


        return $this->render('view', [
                    'model' => $model
        ]);
    }

    /**
     * Updates an existing Restaurant model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {

        $model = $this->findModel($id);

        $store_theme_model = RestaurantTheme::findOne($model->restaurant_uuid);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())  && $store_theme_model->load(Yii::$app->request->post())) {

            if (!$model->phone_number)
                $model->phone_number_display = Restaurant::PHONE_NUMBER_DISPLAY_DONT_SHOW_PHONE_NUMBER;

            if ($model->save() && $store_theme_model->save()) {

                $thumbnail_image = \yii\web\UploadedFile::getInstances($model, 'restaurant_thumbnail_image');
                $logo = \yii\web\UploadedFile::getInstances($model, 'restaurant_logo');

                if ($model->restaurant_payments_method)
                    $model->saveRestaurantPaymentMethod($model->restaurant_payments_method);

                if ($thumbnail_image)
                    $model->uploadThumbnailImage($thumbnail_image[0]->tempName);

                if ($logo)
                    $model->uploadLogo($logo[0]->tempName);


                return $this->redirect(['index', 'restaurantUuid' => $id]);
            }
        }

        return $this->render('update', [
                    'model' => $model,
                    'store_theme_model' => $store_theme_model,
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

        $restaurant_model = Yii::$app->accountManager->getManagedAccount($id);

        if ($restaurant_model !== null) {
            if (AgentAssignment::isOwner($id))
                return $restaurant_model;
            else
                throw new \yii\web\BadRequestHttpException('Sorry, you are not allowed to access this page.');
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
