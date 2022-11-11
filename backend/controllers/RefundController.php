<?php

namespace backend\controllers;

use backend\models\Admin;
use Yii;
use common\models\Refund;
use backend\models\RefundSearch;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RefundController implements the CRUD actions for Refund model.
 */
class RefundController extends Controller
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
                        'actions' => ['create', 'update', 'delete','make-refund'],
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
     * Lists all Refund models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RefundSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Refund model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Refund model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Refund();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->refund_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Refund model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) ) {

            if(!$model->refund_reference)
              $model->refund_reference = null;

            if($model->save(false))
              return $this->redirect(['view', 'id' => $model->refund_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionMakeRefund($id)
    {
        $refund = Refund::find()
            ->joinWith(['store', 'payment', 'currency'])
            ->where(['refund.refund_reference' => null])
            ->andWhere(['payment.payment_current_status' => 'CAPTURED'])
            ->andWhere(['refund.refund_id' => $id])
            ->andWhere(['NOT', ['refund.payment_uuid' => null]])
            ->andWhere(new Expression('refund_status IS NULL OR refund_status="" or refund_status = "Initiated"'))
            ->one();

            if ($refund->store->is_myfatoorah_enable) {

                Yii::$app->myFatoorahPayment->setApiKeys($refund->currency->code);

                $response = Yii::$app->myFatoorahPayment->makeRefund($refund->payment->payment_gateway_payment_id, $refund->refund_amount, $refund->reason, $refund->store->supplierCode);

                $responseContent = json_decode($response->content);

                if (!$response->isOk || ($responseContent && !$responseContent->IsSuccess))
                {
                    $errorMessage = "Error: " . $responseContent->Message . " - " . isset($responseContent->ValidationErrors) ? json_encode($responseContent->ValidationErrors) : $responseContent->Message;

                    $refund->refund_status = 'REJECTED';
                    $refund->refund_message = 'Rejected because: ' . $errorMessage;

                    if(!$refund->save()) {
                        Yii::$app->session->setFlash('error','Refund Error (' . $refund->refund_id . '): ' . serialize($refund->errors) .
                            ' Data: '. $refund->attributes .' Message:' . $errorMessage);
                        return $this->redirect(['view', 'id' => $refund->refund_id]);
                    }
                }
                else
                {
                    $refund->refund_reference = $responseContent->Data->RefundReference;
                    $refund->refund_status = 'Pending';

                    if(!$refund->save()) {
                        Yii::$app->session->setFlash('error','Refund Error (' . $refund->refund_id . '): ' . serialize($refund->errors) . ' Data: '. $refund->attributes);
                    }

                    Yii::$app->session->setFlash('success',"Your refund request has been initiated successfully #".$refund->refund_id);
                    return $this->redirect(['view', 'id' => $refund->refund_id]);
                }

            } else if ($refund->store->is_tap_enable) {

                Yii::$app->tapPayments->setApiKeys(
                    $refund->store->live_api_key,
                    $refund->store->test_api_key,
                    $refund->payment->is_sandbox
                );

                $response = Yii::$app->tapPayments->createRefund(
                    $refund->payment->payment_gateway_transaction_id,
                    $refund->refund_amount,
                    $refund->currency->code,
                    $refund->reason ? $refund->reason : 'requested_by_customer'
                );

                if (array_key_exists('errors', $response->data)) {

                    $errorMessage = $response->data['errors'][0]['description'];

                    //Yii::error('Refund Error (' . $refund->refund_id . '): ' . $errorMessage);

                    //mark as failed and notify customer + vendor

                    $refund->notifyFailure($errorMessage);

                    $refund->refund_status = 'REJECTED';
                    $refund->refund_message = 'Rejected because: ' . $errorMessage;

                    if(!$refund->save()) {
                        Yii::$app->session->setFlash('error','Refund Error (' . $refund->refund_id . '): ' . serialize($refund->errors) .
                            ' Data: '. $refund->attributes .' Response: ' . serialize($response->data));
                    }
                    return $this->redirect(['view', 'id' => $refund->refund_id]);

                } else if ($response->data && isset($response->data['status'])) {

                    $refund->refund_reference = isset($response->data['id']) ? $response->data['id'] : null;
                    $refund->refund_status = $response->data['status'];

                    if(!$refund->save()) {
                        Yii::$app->session->setFlash('error','Refund Error (' . $refund->refund_id . '): ' . serialize($refund->errors) .
                            ' Data: '. $refund->attributes . ' Response: '. serialize($response->data));
                    }

                    Yii::$app->session->setFlash('success',"Your refund request has been initiated successfully #".$refund->refund_id);
                    return $this->redirect(['view', 'id' => $refund->refund_id]);
                }
            }
    }

    /**
     * Deletes an existing Refund model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Refund model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Refund the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Refund::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
