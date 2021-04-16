<?php

namespace frontend\controllers;

use Yii;
use common\models\OpeningHour;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Model;

/**
 * OpeningHourController implements the CRUD actions for OpeningHour model.
 */
class OpeningHourController extends Controller {

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
        ];
    }

    /**
     * Lists all OpeningHour models.
     * @return mixed
     */
    public function actionIndex($storeUuid) {

        $restaurant_model = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $daily_hours = new OpeningHour();

        $models = OpeningHour::find()
                      ->where(['restaurant_uuid' => $restaurant_model->restaurant_uuid])

                      ->orderBy(['day_of_week' => SORT_ASC])
                      ->all();


        if (Model::loadMultiple($models, Yii::$app->request->post()) && Model::validateMultiple($models)) {
            foreach ($models as $opening_hour) {
                $opening_hour->save(false);
            }

        }


            return $this->render('index', [
                        'models' => $models,
                        'daily_hours' => $daily_hours,
                        'storeUuid' => $storeUuid,
            ]);


    }



    public function actionCreate($storeUuid, $dayOfWeek)
    {
        $restaurant_model = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $model = new OpeningHour();
        $model->restaurant_uuid = $restaurant_model->restaurant_uuid;
        $model->day_of_week = $dayOfWeek;
        $model->save(false);
        return $this->redirect(['index', 'storeUuid' => $storeUuid]);


    }

    public function actionDelete($storeUuid, $opening_hour_id, $dayOfWeek)
    {
        $restaurant_model = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $model =  OpeningHour::findOne($opening_hour_id);

        if($isExist = OpeningHour::find()->where(['restaurant_uuid' =>$storeUuid,'day_of_week' => $dayOfWeek ])->count() > 1){
          $model->delete();

        }


        return $this->redirect(['index', 'storeUuid' => $storeUuid]);


    }


}
