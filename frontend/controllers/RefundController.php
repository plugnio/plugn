<?php

namespace frontend\controllers;

use Yii;
use common\models\Refund;
use common\models\RefundSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Order;

/**
 * RefundController implements the CRUD actions for Refund model.
 */
class RefundController extends Controller {

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
        ];
    }

    /**
     * Lists all Refund models.
     * @return mixed
     */
    public function actionIndex($restaurantUuid) {
        $restaurant_model = Yii::$app->ownedAccountManager->getOwnedAccount($restaurantUuid);

        $searchModel = new RefundSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $restaurant_model->restaurant_uuid);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'restaurantUuid' => $restaurantUuid
        ]);
    }

    /**
     * Displays a single Refund model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $restaurantUuid) {
        return $this->render('view', [
                    'model' => $this->findModel($id, $restaurantUuid),
        ]);
    }

    /**
     * Creates a new Refund model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($restaurantUuid) {

        $restaurant_model = Yii::$app->ownedAccountManager->getOwnedAccount($restaurantUuid);

        if ($restaurant_model) {
            $model = new Refund();
            $model->restaurant_uuid = $restaurantUuid;

            if ($model->load(Yii::$app->request->post())) {
                Yii::$app->tapPayments->setApiKeys($restaurant_model->live_api_key, $restaurant_model->test_api_key);

                if ($model->validate()) {

                    $order_model = Order::findOne($model->order_uuid);

                    if ($order_model) {
                        $response = Yii::$app->tapPayments->createRefund(
                                $order_model->payment->payment_gateway_transaction_id, $model->refund_amount, $model->reason
                        );

                        if (array_key_exists('errors', $response->data)) {
                            $model->addError('refund_amount', $response->data['errors'][0]['description']);
                            return $this->render('create', [
                                        'model' => $model,
                            ]);
                        } else if ($response->data) {
                            $model->refund_id = $response->data['id'];
                            $model->refund_status = $response->data['status'];
       
                            if ($model->save())
                                return $this->redirect(['view', 'id' => $model->refund_id, 'restaurantUuid' => $model->restaurant_uuid]);
                        }
                    }else {
                        $model->addError('order_uuid', 'Invalid Order Uuid');

                        return $this->render('create', [
                                    'model' => $model,
                        ]);
                    }
                }
            }

            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Refund model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $restaurantUuid) {
        $model = $this->findModel($id, $restaurantUuid);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->refund_id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Finds the Refund model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Refund the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $restaurantUuid) {
        if (($model = Refund::find()->where(['refund_id' => $id, 'restaurant_uuid' => $restaurantUuid])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
