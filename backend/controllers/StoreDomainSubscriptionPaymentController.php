<?php

namespace backend\controllers;

use common\models\StoreDomainSubscriptionPayment;
use common\models\StoreDomainSubscriptionPaymentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StoreDomainSubscriptionPaymentController implements the CRUD actions for StoreDomainSubscriptionPayment model.
 */
class StoreDomainSubscriptionPaymentController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all StoreDomainSubscriptionPayment models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new StoreDomainSubscriptionPaymentSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StoreDomainSubscriptionPayment model.
     * @param string $id Subscription Payment Uuid
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new StoreDomainSubscriptionPayment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new StoreDomainSubscriptionPayment();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->store_domain_subscription_payment_uuid]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing StoreDomainSubscriptionPayment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id store_domain_subscription_payment_uuid
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->store_domain_subscription_payment_uuid]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing StoreDomainSubscriptionPayment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id Subscription Uuid
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the StoreDomainSubscriptionPayment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id Subscription Uuid
     * @return StoreDomainSubscriptionPayment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StoreDomainSubscriptionPayment::findOne(['store_domain_subscription_payment_uuid' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
