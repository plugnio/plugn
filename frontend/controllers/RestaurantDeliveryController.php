<?php

namespace frontend\controllers;

use Yii;
use common\models\RestaurantDelivery;
use frontend\models\RestaurantDeliverySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\City;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

/**
 * RestaurantDeliveryController implements the CRUD actions for RestaurantDelivery model.
 */
class RestaurantDeliveryController extends Controller {

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
     * Lists all RestaurantDelivery models.
     * @return mixed
     */
    public function actionIndex() {

        $query = City::find()->with('restaurantDeliveries')->all();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        foreach ($dataProvider->query as $city) {
            foreach ($city->restaurantDeliveries as $restaurantDeliveries) {
                if (isset($_POST[$restaurantDeliveries->area_id])) {
                    $restaurantDeliveries->delivery_fee = $_POST['RestaurantDelivery']['delivery_fee'];
                    $restaurantDeliveries->min_delivery_time = $_POST['RestaurantDelivery']['min_delivery_time'];
                    $restaurantDeliveries->save();
                }
            }
        }

        return $this->render('index', [
                    'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single RestaurantDelivery model.
     * @param string $restaurant_uuid
     * @param integer $area_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($restaurant_uuid, $area_id) {
        return $this->render('view', [
                    'model' => $this->findModel($restaurant_uuid, $area_id),
        ]);
    }

    /**
     * Updates City delivery time
     * @param integer $area_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateDeliveryTimeForCity($city_id, $area_id) {

        $model = $this->findModel(Yii::$app->user->identity->restaurant_uuid, $area_id);

        if ($model->load(Yii::$app->request->post())) {

            //bring all restaurant delivery area that match city_id
            $allAreasInCity = RestaurantDelivery::find()->joinWith('area')->where(['area.city_id' => $city_id])->all();

            foreach ($allAreasInCity as $area) {
                $area->min_delivery_time = $model->min_delivery_time;
                $area->save();
            }

            $model = new \yii\data\ActiveDataProvider([
                'query' => RestaurantDelivery::find()->where(['restaurant_uuid' => Yii::$app->user->identity->restaurant_uuid]),
                'sort' => false
            ]);

            return $this->render('city_index', [
                        'dataProvider' => $model,
            ]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing RestaurantDelivery model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $restaurant_uuid
     * @param integer $area_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($restaurant_uuid, $area_id) {
        $model = $this->findModel($restaurant_uuid, $area_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $searchModel = new RestaurantDeliverySearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            return $this->render('index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
            ]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RestaurantDelivery model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $restaurant_uuid
     * @param integer $area_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($restaurant_uuid, $area_id) {
        $this->findModel($restaurant_uuid, $area_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RestaurantDelivery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $restaurant_uuid
     * @param integer $area_id
     * @return RestaurantDelivery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($restaurant_uuid, $area_id) {
        if (($model = RestaurantDelivery::findOne(['restaurant_uuid' => $restaurant_uuid, 'area_id' => $area_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
