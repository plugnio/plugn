<?php

namespace backend\controllers;

use backend\models\Admin;
use Yii;
use common\models\Queue;
use backend\models\QueueSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * QueueController implements the CRUD actions for Queue model.
 */
class QueueController extends Controller
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
                        'actions' => ['create', 'update', 'delete','publish-store','status-hold'],
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
     * Lists all Queue models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QueueSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Queue model.
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
     * Creates a new Queue model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Queue();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->queue_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Queue model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->queue_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionStatusHold($id)
    {
        $model = $this->findModel($id);
        $model->queue_status = Queue::QUEUE_STATUS_HOLD;
        if (!$model->save()) {
            Yii::$app->session->setFlash('error','error while update status');
        } else {
            Yii::$app->session->setFlash('success', 'status updated successfully');
        }
        return $this->redirect(['view', 'id' => $model->queue_id]);
    }

    /**
     * Deletes an existing Queue model.
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
     * @param $id
     * @return \yii\web\Response
     */
    public function actionPublishStore($id) {
        $queue = Queue::find()
            ->joinWith('restaurant')
            ->andWhere(new \yii\db\Expression("queue.queue_status ='".Queue::QUEUE_STATUS_PENDING."' OR queue.queue_status ='".Queue::QUEUE_STATUS_HOLD."'"))
            ->andWhere(['queue.restaurant_uuid' => $id])
            ->orderBy(['queue_created_at' => SORT_ASC])
            ->one();

        if ($queue && $queue->restaurant_uuid) {
            $queue->queue_status = Queue::QUEUE_STATUS_CREATING;
            if (!$queue->save()) {
                return $this->redirect(['queue/view','id'=>$queue->queue_id]);

            }
            return $this->redirect(['queue/view','id'=>$queue->queue_id]);
        } else {
            die('invalid store');
        }
    }
    /**
     * Finds the Queue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Queue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Queue::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
