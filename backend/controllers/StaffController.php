<?php

namespace backend\controllers;

use backend\models\Admin;
use Yii;
use yii\filters\AccessControl;
use common\models\Staff;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * StaffController implements the CRUD actions for Staff model.
 */
class StaffController extends Controller {

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
     * Lists all Staff models.
     * @return mixed
     */
    public function actionIndex() {
        $dataProvider = new ActiveDataProvider([
            'query' => Staff::find(),
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Staff model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Staff model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Staff();
        $model->scenario = "create";

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            Yii::info('[Staff Added > ' . $model->staff_name . '] By ' . Yii::$app->user->identity->admin_name, __METHOD__);

            return $this->redirect(['view', 'id' => $model->staff_id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Staff model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            Yii::info('[Staff Updated > ' . $model->staff_name . '] By ' . Yii::$app->user->identity->admin_name, __METHOD__);

            return $this->redirect(['view', 'id' => $model->staff_id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Staff model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);

        Yii::info('[Staff Deleted > ' . $model->staff_name . '] By ' . Yii::$app->user->identity->admin_name, __METHOD__);

        $model->staff_status = Staff::STATUS_DELETED;
        $model->save(false);

       // $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionLogin($id)
    {
        $model = $this->findModel($id);

        $model->generateAuthKey();

        if(!$model->save(false)) {
            Yii::$app->session->setFlash('errorResponse', $model->errors);

            return $this->redirect(['view', 'id' => $model->staff_id]);
        }

        $url = Yii::$app->params['crmAppUrl']. '?auth_key='.$model->staff_auth_key;

        return $this->redirect($url);
    }

    /**
     * Finds the Staff model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Staff the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Staff::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
