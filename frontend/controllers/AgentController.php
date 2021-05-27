<?php

namespace frontend\controllers;

use Yii;
use common\models\Agent;
use frontend\models\AgentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Model;

/**
 * AgentController implements the CRUD actions for Agent model.
 */
class AgentController extends Controller {

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

    // /**
    //  * Displays a single Agent model.
    //  * @return mixed
    //  * @throws NotFoundHttpException if the model cannot be found
    //  */
    // public function actionIndex($storeUuid) {
    //     return $this->render('view', [
    //                 'model' => $this->findModel($storeUuid),
    //                 'storeUuid' => $storeUuid
    //     ]);
    // }

    /**
     * Updates an existing Agent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($storeUuid) {
        $model = $this->findModel($storeUuid);

        $agentAssignment = $model->getAgentAssignments()
                            ->where(['restaurant_uuid' => $storeUuid, 'agent_id' =>Yii::$app->user->identity->agent_id])
                            ->one();

        if(  $agentAssignment->load(Yii::$app->request->post()) && $model->load(Yii::$app->request->post()) ){

          if($agentAssignment->save() && $model->save())
            return $this->redirect(['update', 'storeUuid' => $storeUuid]);
        }

        return $this->render('update', [
                    'model' => $model,
                    'agentAssignment' => $agentAssignment,
                    'storeUuid' => $storeUuid
        ]);
    }

    /**
     * Change an existing Agent's password model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionChangePassword($storeUuid) {
        $model = $this->findModel($storeUuid);
        $model->setScenario(Agent::SCENARIO_CHANGE_PASSWORD);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['site/index', 'storeUuid' => $storeUuid]);
        }

        return $this->render('change-password', [
                    'model' => $model,
                    'storeUuid' => $storeUuid
        ]);
    }

    /**
     * Finds the Agent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @return Agent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($storeUuid) {
        if (($model = Agent::findOne(Yii::$app->user->identity->agent_id)) !== null && Yii::$app->accountManager->getManagedAccount($storeUuid)) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
