<?php

namespace frontend\controllers;

use Yii;
use common\models\Option;
use frontend\models\OptionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OptionController implements the CRUD actions for Option model.
 */
class OptionController extends Controller {

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
     * Displays a single Option model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $restaurantUuid) {
        
        $option_model = $this->findModel($id, $restaurantUuid);

        // extra options
        $itemExtraOptionsDataProvider = new \yii\data\ActiveDataProvider([
            'query' => $option_model->getExtraOptions(),
        ]);

        return $this->render('view', [
                    'model' => $option_model,
                    'itemExtraOptionsDataProvider' => $itemExtraOptionsDataProvider,
                    'restaurantUuid' => $restaurantUuid
        ]);
    }

    /**
     * Creates a new Option model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($item_uuid, $restaurantUuid) {
        $model = new Option();
        $model->item_uuid = $item_uuid;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->option_id,'restaurantUuid' => $restaurantUuid]);
        }

        return $this->render('create', [
                    'model' => $model,
                    'restaurantUuid' => $restaurantUuid
        ]);
    }

    /**
     * Updates an existing Option model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $restaurantUuid) {
        $model = $this->findModel($id, $restaurantUuid);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->option_id,'restaurantUuid' => $restaurantUuid]);
        }

        return $this->render('update', [
                    'model' => $model,
                    'restaurantUuid' => $restaurantUuid
        ]);
    }

    /**
     * Deletes an existing Option model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $restaurantUuid) {

        $model = $this->findModel($id, $restaurantUuid);
        $item_uuid = $model->item_uuid;

        $model->delete();

        return $this->redirect(['item/view', 'id' => $item_uuid,'restaurantUuid' => $restaurantUuid]);
        
    }

    /**
     * Finds the Option model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Option the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id,$restaurantUuid) {
        if (($model = Option::findOne($id)) !== null) {
            if($model->item->restaurant_uuid == Yii::$app->accountManager->getManagedAccount($restaurantUuid)->restaurant_uuid)
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
