<?php

namespace frontend\controllers;

use Yii;
use common\models\WorkingHours;
use frontend\models\WorkingHoursSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WorkingHoursController implements the CRUD actions for WorkingHours model.
 */
class WorkingHoursController extends Controller {

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
     * Lists all WorkingHours models.
     * @return mixed
     */
    public function actionIndex($restaurantUuid) {
        
        $restaurant_model = Yii::$app->getManagedAccount->getManagedAccount($restaurantUuid);

        $searchModel = new WorkingHoursSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $restaurantUuid);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'restaurantUuid' => $restaurantUuid
        ]);
    }

    /**
     * Creates a new WorkingHours model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($restaurantUuid) {
        
        $restaurant_model = Yii::$app->getManagedAccount->getManagedAccount($restaurantUuid);

        $model = new WorkingHours();
        $model->restaurant_uuid = $restaurant_model->restaurant_uuid;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'restaurantUuid' => $restaurantUuid]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing WorkingHours model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $working_day_id
     * @param string $restaurant_uuid
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($working_day_id, $restaurantUuid) {
        $model = $this->findModel($working_day_id, $restaurantUuid);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'restaurantUuid' => $restaurantUuid]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing WorkingHours model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $working_day_id
     * @param string $restaurant_uuid
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($working_day_id, $restaurantUuid) {
        
        
        $this->findModel($working_day_id, $restaurantUuid)->delete();

        return $this->redirect(['index', 'restaurantUuid' => $restaurantUuid]);
    }

    /**
     * Finds the WorkingHours model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $working_day_id
     * @param string $restaurant_uuid
     * @return WorkingHours the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($working_day_id, $restaurantUuid) {
        if (($model = WorkingHours::findOne(['working_day_id' => $working_day_id, 'restaurant_uuid' => Yii::$app->getManagedAccount->getManagedAccount($restaurantUuid)->restaurant_uuid])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
