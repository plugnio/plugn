<?php

namespace frontend\controllers;

use Yii;
use common\models\Voucher;
use frontend\models\VoucherSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * VoucherController implements the CRUD actions for Voucher model.
 */
class VoucherController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
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
     * Lists all Voucher models.
     * @return mixed
     */
    public function actionIndex($restaurantUuid)
    {
        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

        $searchModel = new VoucherSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $restaurant_model->restaurant_uuid);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'restaurant_model' => $restaurant_model
        ]);
    }

    /**
     * Displays a single Voucher model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id,$restaurantUuid)
    {
        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

        return $this->render('view', [
            'model' => $this->findModel($id,$restaurantUuid),
        ]);
    }

    /**
     * Creates a new Voucher model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($restaurantUuid)
    {
        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

        if($restaurant_model){
          $model = new Voucher();
          $model->restaurant_uuid = $restaurantUuid;


          if ($model->load(Yii::$app->request->post()) && $model->save()) {
              return $this->redirect(['view', 'id' => $model->voucher_id, 'restaurantUuid' => $restaurantUuid]);
          }

          return $this->render('create', [
              'model' => $model,
              'restaurantUuid' => $restaurantUuid
          ]);
        }

    }

    /**
     * Updates an existing Voucher model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $restaurantUuid)
    {
        $model = $this->findModel($id, $restaurantUuid);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->voucher_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Voucher model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $restaurantUuid)
    {
        $this->findModel($id, $restaurantUuid)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Voucher model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Voucher the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $restaurantUuid)
    {
        if (($model = Voucher::find()->where(['voucher_id' => $id, 'restaurant_uuid' => Yii::$app->accountManager->getManagedAccount($restaurantUuid)->restaurant_uuid])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
