<?php

namespace backend\controllers;

use agent\models\AgentAssignment;
use backend\models\Admin;
use backend\models\AgentAssignmentSearch;
use Yii;
use yii\db\Expression;
use backend\models\Agent;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\AgentSearch;

/**
 * AgentController implements the CRUD actions for Agent model.
 */
class AgentController extends Controller
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
                        'actions' => ['create', 'update', 'delete'],
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
     * Lists all Agent models.
     * @return mixed
     */
    public function actionIndex()
    {
       $searchModel = new AgentSearch();
       $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

       return $this->render('index', [
           'searchModel' => $searchModel,
           'dataProvider' => $dataProvider,
       ]);
    }

    /**
     * return list of agent for dropdown
     * @return string
     */
    public function actionDropdown()
    {
        $fromPager = Yii::$app->request->get('fromPager');
        //$keyword = Yii::$app->request->get('keyword');

        $searchModel = new AgentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if($fromPager) {
            return $this->renderPartial ('_dropdown_list', [
                'dataProvider' => $dataProvider,
            ]);
        }

        return $this->renderPartial('dropdown', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Agent model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $searchModel = new AgentAssignmentSearch();
        $searchModel->agent_id = $id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    /**
     * send email verification email
     * @param $id
     * @return void
     * @throws NotFoundHttpException
     */
    public function actionSendVerificationEmail($id) {

        $model = $this->findModel($id);

        $model->sendVerificationEmail();

        Yii::$app->session->setFlash('successResponse', "Verification mail sent!");

        return $this->redirect(['view', 'id' => $model->agent_id]);
    }

    /**
     * Creates a new Agent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Agent();
        $model->setScenario(Agent::SCENARIO_CREATE_NEW_AGENT);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['view', 'id' => $model->agent_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Agent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->agent_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Agent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteAssignments($id)
    {
        $agent = $this->findModel($id);

        AgentAssignment::deleteAll(['agent_id' => $agent->agent_id]);

        return $this->redirect(['index']);
    }

    /**
     * Deletes an existing Agent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->deleted = 1;
        $model->agent_deleted_at = new Expression("NOW()");

        $model->save(false);
            //->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Agent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Agent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Agent::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
