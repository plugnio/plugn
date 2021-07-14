<?php

namespace frontend\controllers;

use Yii;
use common\models\OpeningHour;
use frontend\models\OpeningHourSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Model;

/**
 * OpeningHourController implements the CRUD actions for OpeningHour model.
 */
class OpeningHourController extends Controller
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
     * Lists all OpeningHour models.
     * @return mixed
     */
    public function actionIndex($storeUuid)
    {

        $restaurant_model = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $models = OpeningHour::find()
                      ->andWhere(['restaurant_uuid' => $restaurant_model->restaurant_uuid])
                      ->orderBy(['day_of_week' => SORT_ASC, 'open_at' => SORT_ASC])
                      ->all();

        return $this->render('index', [
            'models' => $models,
            'storeUuid' => $storeUuid,
        ]);
    }

    /**
     * Displays a single OpeningHour model.
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
     * Creates a new OpeningHour model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new OpeningHour();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->opening_hour_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OpeningHour model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($storeUuid, $dayOfWeek) {

        // $models = $this->findModel($storeUuid, $dayOfWeek);
        //
        // if(!$models){
        //   $models = [new OpeningHour()];
        //   $models[0]->restaurant_uuid = $storeUuid;
        // }
        //
        //
        // if (Model::loadMultiple($models, Yii::$app->request->post()) && Model::validateMultiple($models)) {
        //       foreach ($models as $opening_hour) {
        //           $opening_hour->restaurant_uuid = $storeUuid;
        //           $opening_hour->save(false);
        //       }
        //
        //       return $this->redirect(['index', 'storeUuid' => $storeUuid]);
        //
        //   }
        //
        // return $this->render('update', [
        //     'models' => $models,
        //     'storeUuid' => $storeUuid
        // ]);
        //






        $openingHours = $this->findModel($storeUuid, $dayOfWeek);


        switch ($dayOfWeek) {
            case OpeningHour::DAY_OF_WEEK_SATURDAY:
                 $day = 'Saturday';
                break;
            case OpeningHour::DAY_OF_WEEK_SUNDAY:
                $day = 'Sunday';
                break;
            case OpeningHour::DAY_OF_WEEK_MONDAY:
                $day = 'Monday';
                break;
            case OpeningHour::DAY_OF_WEEK_TUESDAY:
                $day = 'Tuesday';
                break;
            case OpeningHour::DAY_OF_WEEK_WEDNESDAY:
                $day = 'Wednesday';
                break;
            case OpeningHour::DAY_OF_WEEK_THURSDAY:
                $day = 'Thursday';
                break;
            case OpeningHour::DAY_OF_WEEK_FRIDAY:
                $day = 'Friday';
                break;
        }


        // if(!$openingHours){
        //   $openingHours = [new OpeningHour()];
        //   $openingHours[0]->restaurant_uuid = $storeUuid;
        //   $openingHours[0]->day_of_week = $dayOfWeek;
        // }




            $formDetails = Yii::$app->request->post('OpeningHour', []);
            foreach ($formDetails as $i => $formDetail) {

                //loading the models if they are not new
                if (isset($formDetail['opening_hour_id']) && isset($formDetail['updateType']) && $formDetail['updateType'] != OpeningHour::UPDATE_TYPE_CREATE) {

                    //making sure that it is actually a child of the main model
                    $modelDetail = OpeningHour::findOne(['opening_hour_id' => $formDetail['opening_hour_id'] ,'restaurant_uuid' => $storeUuid ,'day_of_week' => $dayOfWeek]);

                    $modelDetail->day_of_week = $dayOfWeek;
                    $modelDetail->restaurant_uuid = $storeUuid;
                    $modelDetail->setScenario(OpeningHour::SCENARIO_BATCH_UPDATE);
                    $modelDetail->setAttributes($formDetail);
                    $openingHours[$i] = $modelDetail;
                    //validate here if the modelDetail loaded is valid, and if it can be updated or deleted
                } else {
                    $modelDetail = new OpeningHour(['scenario' => OpeningHour::SCENARIO_BATCH_UPDATE]);
                    // $modelDetail->restaurant_uuid = $storeUuid;
                    $modelDetail->setAttributes($formDetail);
                    $openingHours[] = $modelDetail;
                }

            }


        //handling if the addRow button has been pressed
        if (Yii::$app->request->post('addRow') == 'true') {
            $openingHours[] = new OpeningHour();
            return $this->render('update', [
                'openingHours' => $openingHours,
                'day' => $day,
                'storeUuid' => $storeUuid
            ]);
        }

        if (Model::loadMultiple($openingHours, Yii::$app->request->post()) ) {

            // if (Model::validateMultiple($openingHours)) { //todo
                foreach($openingHours as $modelDetail) {
                    //details that has been flagged for deletion will be deleted
                    if ($modelDetail->updateType == OpeningHour::UPDATE_TYPE_DELETE) {
                        $modelDetail->delete();
                    } else {
                        //new or updated records go here
                        $modelDetail->day_of_week = $dayOfWeek;
                        $modelDetail->restaurant_uuid = $storeUuid;

                        $modelDetail->save();


                    }
                }
                return $this->redirect(['index', 'storeUuid' => $storeUuid]);
            // }
        }

        return $this->render('update', [
          'openingHours' => $openingHours,
          'day' => $day,
          'storeUuid' => $storeUuid
        ]);

    }

    /**
     * Deletes an existing OpeningHour model.
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
     * Finds the OpeningHour model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OpeningHour the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($storeUuid, $dayOfWeek)
    {
        if (($model = OpeningHour::find()->where(['day_of_week'=>$dayOfWeek, 'restaurant_uuid' => Yii::$app->accountManager->getManagedAccount($storeUuid)->restaurant_uuid ])->all()) !== null) {
            return $model;
        }


        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
