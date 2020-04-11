<?php

namespace frontend\controllers;

use Yii;
use common\models\RestaurantTheme;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RestaurantThemeController implements the CRUD actions for RestaurantTheme model.
 */
class RestaurantThemeController extends Controller
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
     * Displays a single RestaurantTheme model.
     * @param string $restaurantUuid
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionIndex($restaurantUuid)
    {
        return $this->render('view', [
            'model' => $this->findModel($restaurantUuid),
        ]);
    }

    /**
     * Updates an existing RestaurantTheme model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($restaurantUuid)
    {
        $model = $this->findModel($restaurantUuid);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'restaurantUuid' => $model->restaurant_uuid]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the RestaurantTheme model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return RestaurantTheme the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($restaurantUuid)
    {
        if (($model = RestaurantTheme::findOne(Yii::$app->ownedAccountManager->getOwnedAccount($restaurantUuid)->restaurant_uuid)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
