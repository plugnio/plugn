<?php

namespace frontend\controllers;

use Yii;
use common\models\BusinessLocation;
use frontend\models\BusinessLocationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BusinessLocationController implements the CRUD actions for BusinessLocation model.
 */
class BusinessLocationController extends Controller
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
     * Lists all BusinessLocation models.
     * @return mixed
     */
    public function actionIndex($storeUuid)
    {
        $restaurant_model = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $businessLocations = BusinessLocation::find()->where(['restaurant_uuid' => $restaurant_model->restaurant_uuid])->all();


        return $this->render('index', [
            'businessLocations' => $businessLocations,
            'storeUuid' => $storeUuid
        ]);
    }



    /**
     * Creates a new BusinessLocation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($storeUuid)
    {
        $model = new BusinessLocation();
        $model->restaurant_uuid = $storeUuid;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'storeUuid' => $storeUuid]);
        }

        return $this->render('create', [
            'model' => $model,
            'storeUuid' => $storeUuid
        ]);
    }

    /**
     * Updates an existing BusinessLocation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $storeUuid)
    {
        $model = $this->findModel($id, $storeUuid);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index',  'storeUuid' => $storeUuid]);
        }

        return $this->render('update', [
            'model' => $model,
            'storeUuid' => $storeUuid
        ]);
    }

    /**
     * Deletes an existing BusinessLocation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $storeUuid)
    {
        $this->findModel($id, $storeUuid)->delete();

        return $this->redirect(['index', 'storeUuid' => $storeUuid]);
    }

    /**
     * Finds the BusinessLocation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BusinessLocation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $storeUuid)
    {

        if (($model = BusinessLocation::find()->where(['business_location_id' => $id, 'restaurant_uuid' => Yii::$app->accountManager->getManagedAccount($storeUuid)->restaurant_uuid])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
