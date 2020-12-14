<?php

namespace frontend\controllers;

use Yii;
use common\models\WebLink;
use frontend\models\WebLinkSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WebLinkController implements the CRUD actions for WebLink model.
 */
class WebLinkController extends Controller {

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
     * Lists all WebLink models.
     * @return mixed
     */
    public function actionIndex($storeUuid) {
        $searchModel = new WebLinkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $storeUuid);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'storeUuid' => $storeUuid
        ]);
    }


    /**
     * Creates a new WebLink model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($storeUuid) {
        $restaurant_model = Yii::$app->accountManager->getManagedAccount($storeUuid);

        if ($restaurant_model) {

            $model = new WebLink();
            $model->restaurant_uuid = $storeUuid;

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['index', 'storeUuid' => $storeUuid]);
            }

            return $this->render('create', [
                        'model' => $model,
                        'storeUuid' => $storeUuid
            ]);
        }
    }

    /**
     * Updates an existing WebLink model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $storeUuid) {
        $model = $this->findModel($id, $storeUuid);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'storeUuid' => $storeUuid]);
        }

        return $this->render('update', [
                    'model' => $model,
                    'storeUuid' => $storeUuid
        ]);
    }

    /**
     * Deletes an existing WebLink model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $storeUuid) {
        $this->findModel($id, $storeUuid)->delete();

        return $this->redirect(['index', 'storeUuid' => $storeUuid]);
    }

    /**
     * Finds the WebLink model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WebLink the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $storeUuid) {
        if (($model = WebLink::find()->where(['web_link_id' => $id, 'restaurant_uuid' => Yii::$app->accountManager->getManagedAccount($storeUuid)->restaurant_uuid])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
