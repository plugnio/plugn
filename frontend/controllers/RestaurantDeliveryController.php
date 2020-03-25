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

        foreach ($dataProvider->query as $key => $city) {
            if ($city->restaurantDeliveryAreas)
                foreach ($city->restaurantDeliveryAreas as $restaurantDeliveryAreas) {

                    if (isset($_POST[$restaurantDeliveryAreas->area->area_id])) {
                        $restaurantDeliveryAreas->delivery_fee = $_POST['RestaurantDelivery']['delivery_fee'];
                        $restaurantDeliveryAreas->delivery_time = $_POST['RestaurantDelivery']['delivery_time'];
                        $restaurantDeliveryAreas->min_charge = $_POST['RestaurantDelivery']['min_charge'];
                        $restaurantDeliveryAreas->save();
                    }
                } else {
                unset($dataProvider->query[$key]);
            }
        }
    }

    /**
     * Creates a new RestaurantDelivery model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new RestaurantDelivery();
        $model->restaurant_uuid = Yii::$app->user->identity->restaurant_uuid ;

        if ($model->load(Yii::$app->request->post())) {

            if ($model->restaurant_delivery_area_array)
                $model->saveRestaurantDeliveryArea($model->restaurant_delivery_area_array);

            return $this->render(['index']);
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing RestaurantDelivery model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param string $restaurant_uuid
     * @param integer $area_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate() {
        $model = RestaurantDelivery::findOne(['restaurant_uuid' => Yii::$app->user->identity->restaurant_uuid]);

        if ($model->load(Yii::$app->request->post())) {

            if ($model->restaurant_delivery_area_array)
                $model->saveRestaurantDeliveryArea($model->restaurant_delivery_area_array);

            return $this->render(['index']);
        }
        
        return $this->render('update', [
                    'model' => $model,
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

        return $this->render('update-delivery-zone', [
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
