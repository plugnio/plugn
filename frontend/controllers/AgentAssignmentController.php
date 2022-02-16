<?php

namespace frontend\controllers;

use frontend\models\AgentAssignmentSearch;
use Yii;
use common\models\AgentAssignment;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Restaurant;
use common\models\Agent;

/**
 * AgentAssignmentController implements the CRUD actions for AgentAssignment model.
 */
class AgentAssignmentController extends Controller
{

    public $enableCsrfValidation = false;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
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
     * Lists all AgentAssignment models.
     * @return mixed
     */
    public function actionIndex($storeUuid)
    {
        $store = Yii::$app->accountManager->getManagedAccount($storeUuid);

        if (Yii::$app->user->identity->isOwner($store->restaurant_uuid))
        {
            $searchModel = new AgentAssignmentSearch();

            $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $store->restaurant_uuid);

            return $this->render('index', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'restaurant_uuid' => $store->restaurant_uuid
            ]);
        } else {
            throw new \yii\web\BadRequestHttpException('Sorry, you are not allowed to access this page.');
        }
    }

    /**
     * Displays a single AgentAssignment model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($assignment_id, $agent_id, $storeUuid)
    {
        $model = $this->findModel($assignment_id, $agent_id, $storeUuid);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new AgentAssignment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($storeUuid)
    {
        $model = new AgentAssignment();

        $model->restaurant_uuid = Yii::$app->accountManager->getManagedAccount($storeUuid)
            ->restaurant_uuid;

        if (Yii::$app->user->identity->isOwner($model->restaurant_uuid)) {

            if ($model->load(Yii::$app->request->post())) {

                if ($model->validate() && $model->save()) {
                    return $this->redirect(['view', 'assignment_id' => $model->assignment_id, 'agent_id' => $model->agent_id, 'storeUuid' => $storeUuid]);
                }
            }

            return $this->render('create', [
                'model' => $model
            ]);
        } else {
            throw new \yii\web\BadRequestHttpException('Sorry, you are not allowed to access this page.');
        }
    }

    /**
     * Updates an existing AgentAssignment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($assignment_id, $agent_id, $storeUuid)
    {
        $model = $this->findModel($assignment_id, $agent_id, $storeUuid);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'assignment_id' => $model->assignment_id, 'agent_id' => $model->agent_id, 'storeUuid' => $storeUuid]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AgentAssignment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($assignment_id, $agent_id, $storeUuid)
    {
        $this->findModel($assignment_id, $agent_id, $storeUuid)->delete();

        return $this->redirect(['index', 'storeUuid' => $storeUuid]);
    }

    /**
     * Finds the AgentAssignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AgentAssignment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($assignment_id, $agent_id, $storeUuid)
    {

        $store = Yii::$app->accountManager->getManagedAccount($storeUuid);

        if (Yii::$app->user->identity->isOwner($storeUuid)) {

            if (($model = AgentAssignment::find()->where(['assignment_id' => $assignment_id, 'agent_id' => $agent_id, 'restaurant_uuid' => $store->restaurant_uuid])->one()) !== null) {
                return $model;
            } else {
                throw new NotFoundHttpException('The requested page does not exist.');

            }
        } else {
            throw new \yii\web\BadRequestHttpException('Sorry, you are not allowed to access this page.');
        }
    }
}
