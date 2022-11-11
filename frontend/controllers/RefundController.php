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
     * Lists all Refund models.
     * @return mixed
     */
    public function actionIndex($storeUuid) {
        $restaurant = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $searchModel = new RefundSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $restaurant->restaurant_uuid);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'storeUuid' => $storeUuid
        ]);
    }

    /**
     * Displays a single Refund model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $storeUuid) {
        return $this->render('view', [
                    'model' => $this->findModel($id, $storeUuid),
        ]);
    }

    /**
     * Creates a new Refund model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($storeUuid, $orderUuid) {

        $restaurant = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $order = Order::find()->where(['order_uuid' => $orderUuid])->exists();

        if ($restaurant && $order)
        {
            $model = new Refund();
            $model->restaurant_uuid = $storeUuid;
            $model->order_uuid = $orderUuid;

            if ($model->load(Yii::$app->request->post()))
            {
                Yii::$app->tapPayments->setApiKeys(
                    $restaurant->live_api_key,
                    $restaurant->test_api_key,
                    $order->payment->is_sandbox
                );

                if ($model->validate()) {

                    $order = Order::findOne($model->order_uuid);

                    if ($order) {
                        $response = Yii::$app->tapPayments->createRefund(
                                $order->payment->payment_gateway_transaction_id, $model->refund_amount, $model->reason
                        );

                        if (array_key_exists('errors', $response->data))
                        {
                            $model->addError('refund_amount', $response->data['errors'][0]['description']);

                            return $this->render('create', [
                                        'model' => $model,
                            ]);
                        }
                        else if ($response->data)
                        {
                            $model->refund_id = $response->data['id'];
                            $model->refund_status = $response->data['status'];

                            if ($model->save())
                                return $this->redirect(['view', 'id' => $model->refund_id, 'storeUuid' => $model->restaurant_uuid]);
                        }
                    }
                    else
                    {
                        $model->addError('order_uuid', Yii::t('yii', '{attribute} is invalid.', [
                           'attribute' => 'order_uuid'
                        ]));

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
    public function actionUpdate($id, $storeUuid) {
        $model = $this->findModel($id, $storeUuid);

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
    protected function findModel($id, $storeUuid)
    {
        $model = Refund::find()
            ->where(['refund_id' => $id, 'restaurant_uuid' => $storeUuid])
            ->one();

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
