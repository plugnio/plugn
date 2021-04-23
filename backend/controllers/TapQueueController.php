<?php

namespace backend\controllers;

use Yii;
use common\models\TapQueue;
use backend\models\TapQueueSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TapQueueController implements the CRUD actions for TapQueue model.
 */
class TapQueueController extends Controller
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
                 [//allow authenticated users only
                     'allow' => true,
                     'roles' => ['@'],
                 ],
             ],
         ],
     ];
 }


    /**
     * Lists all TapQueue models.
     * @return mixed
     */
    public function actionIndex()
    {

       $searchModel = new TapQueueSearch();
       $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

       return $this->render('index', [
           'searchModel' => $searchModel,
           'dataProvider' => $dataProvider,
       ]);

    }

    /**
     * Displays a single TapQueue model.
     * @param integer $id
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
     * Creates a new TapQueue model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TapQueue();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->tap_queue_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TapQueue model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->tap_queue_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing TapQueue model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the TapQueue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TapQueue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TapQueue::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
