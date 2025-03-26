<?php

namespace backend\controllers;

use common\models\StoreDomainSubscription;
use common\models\StoreDomainSubscriptionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StoreDomainSubscriptionController implements the CRUD actions for StoreDomainSubscription model.
 */
class StoreDomainSubscriptionController extends Controller
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
     * Lists all StoreDomainSubscription models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new StoreDomainSubscriptionSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StoreDomainSubscription model.
     * @param string $subscription_uuid Subscription Uuid
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($subscription_uuid)
    {
        return $this->render('view', [
            'model' => $this->findModel($subscription_uuid),
        ]);
    }

    /**
     * Creates a new StoreDomainSubscription model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new StoreDomainSubscription();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'subscription_uuid' => $model->subscription_uuid]);
            } else {
                print_r($model->getErrors());
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing StoreDomainSubscription model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $subscription_uuid Subscription Uuid
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($subscription_uuid)
    {
        $model = $this->findModel($subscription_uuid);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'subscription_uuid' => $model->subscription_uuid]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing StoreDomainSubscription model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $subscription_uuid Subscription Uuid
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($subscription_uuid)
    {
        $this->findModel($subscription_uuid)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the StoreDomainSubscription model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $subscription_uuid Subscription Uuid
     * @return StoreDomainSubscription the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($subscription_uuid)
    {
        if (($model = StoreDomainSubscription::findOne(['subscription_uuid' => $subscription_uuid])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
