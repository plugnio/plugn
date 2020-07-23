<?php

namespace frontend\controllers;

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
class AgentAssignmentController extends Controller {

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
     * Lists all AgentAssignment models.
     * @return mixed
     */
    public function actionIndex($restaurantUuid) {

        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);


        if (AgentAssignment::isOwner($restaurant_model->restaurant_uuid)) {
            $dataProvider = new ActiveDataProvider([
                'query' => AgentAssignment::find()->where(['restaurant_uuid' => $restaurant_model->restaurant_uuid]),
            ]);

            return $this->render('index', [
                        'dataProvider' => $dataProvider,
                        'restaurant_uuid' => $restaurant_model->restaurant_uuid
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
    public function actionView($assignment_id, $agent_id, $restaurantUuid) {
        return $this->render('view', [
                    'model' => $this->findModel($assignment_id, $agent_id, $restaurantUuid),
        ]);
    }


    /**
     * Creates a new AgentAssignment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($restaurantUuid) {

        $model = new AgentAssignment();
        $model->restaurant_uuid = Yii::$app->accountManager->getManagedAccount($restaurantUuid)->restaurant_uuid;

        if (AgentAssignment::isOwner($model->restaurant_uuid)) {

            if ($model->load(Yii::$app->request->post())) {

                if ($model->validate() && $model->save()) {
                    return $this->redirect(['view', 'assignment_id' => $model->assignment_id, 'agent_id' => $model->agent_id, 'restaurantUuid' => $restaurantUuid]);
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
    public function actionUpdate($assignment_id, $agent_id, $restaurantUuid) {
        $model = $this->findModel($assignment_id, $agent_id, $restaurantUuid);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'assignment_id' => $model->assignment_id, 'agent_id' => $model->agent_id, 'restaurantUuid' => $restaurantUuid]);
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
    public function actionDelete($assignment_id, $agent_id, $restaurantUuid) {
        $this->findModel($assignment_id, $agent_id, $restaurantUuid)->delete();

        return $this->redirect(['index', 'restaurantUuid' => $restaurantUuid]);
    }

    /**
     * Finds the AgentAssignment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AgentAssignment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($assignment_id, $agent_id, $restaurantUuid) {
        if (Yii::$app->accountManager->getManagedAccount($restaurantUuid)) {

            if (AgentAssignment::isOwner($restaurantUuid)) {

                if (($model = AgentAssignment::find()->where(['assignment_id' => $assignment_id, 'agent_id' => $agent_id, 'restaurant_uuid' => $restaurantUuid])->one()) !== null) {
                    return $model;
                }
            } else {
                throw new \yii\web\BadRequestHttpException('Sorry, you are not allowed to access this page.');
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
