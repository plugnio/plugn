<?php

namespace frontend\controllers;

use Yii;
use common\models\RestaurantDelivery;
use frontend\models\RestaurantDeliverySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\City;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use frontend\models\DeliveryZoneForm;
use common\models\Restaurant;

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
     * Lists all RestaurantDelivery models.
     * @return mixed
     */
    public function actionIndex() {

        $query = City::find()->with('restaurantDeliveryAreas')->all();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        foreach ($dataProvider->query as $city) {
            foreach ($city->restaurantDeliveryAreas as $restaurantDeliveryAreas) {

                if (isset($_POST[$restaurantDeliveryAreas->area->area_id])) {
                    $restaurantDeliveryAreas->delivery_fee = $_POST['RestaurantDelivery']['delivery_fee'];
                    $restaurantDeliveryAreas->delivery_time = $_POST['RestaurantDelivery']['delivery_time'];
                    $restaurantDeliveryAreas->min_charge = $_POST['RestaurantDelivery']['min_charge'];
                    $restaurantDeliveryAreas->save();
                }
         
                }
        }

        $restaurant_model = Restaurant::findOne(Yii::$app->user->identity->restaurant_uuid);
        
        if (Yii::$app->request->isPost && $restaurant_model->load(Yii::$app->request->post())) {

            if ($restaurant_model->restaurant_delivery_area)
                $restaurant_model->saveRestaurantDeliveryArea($restaurant_model->restaurant_delivery_area);

                return $this->redirect(['index']);
        }

        $restaurant_model = Restaurant::findOne(Yii::$app->user->identity->restaurant_uuid);
        
        if (Yii::$app->request->isPost && $restaurant_model->load(Yii::$app->request->post())) {

            if ($restaurant_model->restaurant_delivery_area)
                $restaurant_model->saveRestaurantDeliveryArea($restaurant_model->restaurant_delivery_area);

                return $this->redirect(['index']);
        }

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'restaurant_model' => $restaurant_model
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
    public function actionUpdateDeliveryTimeForCity($city_id) {

        $model = new DeliveryZoneForm;
        $model->city_id = $city_id;

        if ($model->load(Yii::$app->request->post()) && $model->applyTimingAndDeliveryFeeForAllAreasBelongsToThisCity()) {

            return $this->redirect(['index']);
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
    public function actionDelete($area_id) {
        $this->findModel(Yii::$app->user->identity->restaurant_uuid, $area_id)->delete();

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
