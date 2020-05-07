<?php

namespace frontend\controllers;

use Yii;
use common\models\ExtraOption;
use frontend\models\ExtraOptionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ExtraOptionController implements the CRUD actions for ExtraOption model.
 */
class ExtraOptionController extends Controller
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
     * Displays a single ExtraOption model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id ,$restaurantUuid)
    {
        return $this->render('view', [
            'model' => $this->findModel($id,$restaurantUuid),
            'restaurantUuid' => $restaurantUuid
        ]);
    }

    /**
     * Creates a new ExtraOption model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($option_id, $restaurantUuid)
    {
        $model = new ExtraOption();
        $model->option_id = $option_id;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->extra_option_id,'restaurantUuid' => $restaurantUuid]);
        }

        return $this->render('create', [
            'model' => $model,
            'restaurantUuid' => $restaurantUuid
        ]);
    }

    /**
     * Updates an existing ExtraOption model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $restaurantUuid)
    {
        $model = $this->findModel($id, $restaurantUuid);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->extra_option_id, 'restaurantUuid' => $restaurantUuid]);
        }

        return $this->render('update', [
            'model' => $model,
            'restaurantUuid' => $restaurantUuid
        ]);
    }

    /**
     * Deletes an existing ExtraOption model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $restaurantUuid)
    {
        $model = $this->findModel($id, $restaurantUuid);
        $option_id = $model->option_id;

        $model->delete();

        return $this->redirect(['option/view', 'id' => $option_id, 'restaurantUuid' => $restaurantUuid]);
    }

    /**
     * Finds the ExtraOption model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ExtraOption the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $restaurantUuid)
    {
        if (($model = ExtraOption::findOne($id)) !== null) {
           if($model->item->restaurant_uuid == Yii::$app->accountManager->getManagedAccount($restaurantUuid)->restaurant_uuid)
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
