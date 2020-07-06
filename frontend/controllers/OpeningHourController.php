<?php

namespace frontend\controllers;

use Yii;
use common\models\OpeningHour;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OpeningHourController implements the CRUD actions for OpeningHour model.
 */
class OpeningHourController extends Controller {

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
        ];
    }

    /**
     * Lists all OpeningHour models.
     * @return mixed
     */
    public function actionIndex($restaurantUuid) {
        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

        if ($restaurant_model) {

            $dataProvider = new ActiveDataProvider([
                'query' => OpeningHour::find()->where(['restaurant_uuid' => $restaurant_model->restaurant_uuid]),
            ]);

            return $this->render('index', [
                        'dataProvider' => $dataProvider,
                        'restaurantUuid' => $restaurantUuid,
            ]);
        }
    }

    /**
     * Displays a single OpeningHour model.
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
     * Creates a new OpeningHour model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($restaurantUuid) {
        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

        if ($restaurant_model) {
            $numberOfDayOfWeek = 6;

            $models = [new OpeningHour()];
            $models[0]->day_of_week = OpeningHour::DAY_OF_WEEK_SATURDAY;

            for ($i = 1; $i <= $numberOfDayOfWeek; $i++) {
                $models[] = new OpeningHour();
                $models[$i]->day_of_week = $i;
            }




            return $this->render('create', [
                        'models' => $models,
                        'restaurantUuid' => $restaurantUuid,
            ]);
        }
    }

    /**
     * Updates an existing OpeningHour model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->opening_hour_id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing OpeningHour model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the OpeningHour model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OpeningHour the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = OpeningHour::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
