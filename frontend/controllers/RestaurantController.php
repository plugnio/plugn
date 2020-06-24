<?php

namespace frontend\controllers;

use Yii;
use common\models\Restaurant;
use common\models\Order;
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

        array_push($revenue_generated_chart_data, (int) ($revenue_generated_last_five_months_month));

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

        array_push($revenue_generated_chart_data, (int) ($revenue_generated_last_four_months_month));

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

        array_push($revenue_generated_chart_data, (int) ($revenue_generated_last_three_months_month));

        array_push($months, $lastThreeMonths);

        $revenue_generated_last_two_months_month = Order::find()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 2 MONTH)')
                ->sum('total_price');

        $lastTwoMonths = date('M', strtotime('-2 months'));

        array_push($revenue_generated_chart_data, (int) ($revenue_generated_last_two_months_month));

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

        array_push($revenue_generated_chart_data, (int) ($revenue_generated_last_month));

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

        $order_recevied_last_two_months_month = Order::find()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->andWhere(['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
                ->andWhere(['!=', 'order_status', Order::STATUS_DRAFT])
                ->andWhere(['!=', 'order_status', Order::STATUS_REFUNDED])
                ->andWhere(['!=', 'order_status', Order::STATUS_CANCELED])
                ->andWhere('YEAR(`order`.`order_created_at`) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH)')
                ->andWhere('MONTH(`order`.`order_created_at`) = MONTH(CURRENT_DATE - INTERVAL 2 MONTH)')
                ->count();

        array_push($order_recevied_chart_data, (int) ($order_recevied_last_two_months_month));

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


        $sold_items_chart_data = [];
        $number_of_sold_items_chart_data = [];
              
        

        $sold_items = \common\models\Item::find()
                ->joinWith(['orderItems', 'orderItems.order'])
                 ->where(['order_status' => Order::STATUS_PENDING])
                ->orWhere(['order_status' => Order::STATUS_BEING_PREPARED])
                ->orWhere(['order_status' => Order::STATUS_OUT_FOR_DELIVERY])
                ->orWhere(['order_status' => Order::STATUS_COMPLETE])
                ->andWhere(['item.restaurant_uuid' => $model->restaurant_uuid])
                ->orderBy(['order_item.qty' => SORT_DESC])
                ->limit(7)
                ->all();


        
        foreach ($sold_items as $item) {
            array_push($sold_items_chart_data, $item->item_name);
            array_push($number_of_sold_items_chart_data, $item->getThisMonthSoldUnits() ? $item->getThisMonthSoldUnits() : 0);
        }
        
        

        return $this->render('analytic', [
                    'model' => $model,
                    'months' => $months,
            'sold_items_chart_data' => $sold_items_chart_data,
            'number_of_sold_items_chart_data' => $number_of_sold_items_chart_data,
                    'revenue_generated_chart_data' => $revenue_generated_chart_data,
                    'order_recevied_chart_data' => $order_recevied_chart_data,
                        // 'most_selling_items_chart_data' => $most_selling_items_chart_data,
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

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            if (!$model->phone_number)
                $model->phone_number_display = Restaurant::PHONE_NUMBER_DISPLAY_DONT_SHOW_PHONE_NUMBER;

            if ($model->save()) {

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
