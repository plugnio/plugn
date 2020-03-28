<?php

namespace frontend\controllers;

use Yii;
use common\models\Agent;
use frontend\models\AgentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AgentController implements the CRUD actions for Agent model.
 */
class AgentController extends Controller
{
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
        ];
    }

    /**
     * Displays a single Agent model.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionIndex()
    {
        return $this->render('view', [
            'model' => $this->findModel(),
        ]);
    }

    /**
     * Updates an existing Agent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate()
    {
        $model = $this->findModel();

        if ($model->load(Yii::$app->request->post())) {

            $agent_model = $this->findModel();
            $agent_model->agent_name = $model->agent_name;
            $agent_model->agent_email = $model->agent_email;
            if($agent_model->save()){

               return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Agent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @return Agent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel()
    {
        if (($model = Agent::findOne(Yii::$app->user->identity->agent_id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
