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
     * Lists all RestaurantDelivery models.
     * @return mixed
     */
    public function actionIndex($restaurantUuid) {

        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

        $query = City::find()->with(['restaurantDeliveryAreas' => function($query) use($restaurantUuid) {
                return $query->where(['restaurant_uuid' => $restaurantUuid]);
            }])->all();


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);


        foreach ($dataProvider->query as $key => $city) {

            if ($city->restaurantDeliveryAreas) {
                foreach ($city->restaurantDeliveryAreas as $key => $restaurantDeliveryAreas) {

                    if (isset($_POST[$restaurantDeliveryAreas->area->area_id])) {

                        $restaurantDeliveryAreas->delivery_fee = $_POST['RestaurantDelivery']['delivery_fee'];
                        $restaurantDeliveryAreas->delivery_time = $_POST['RestaurantDelivery']['delivery_time'];
                        $restaurantDeliveryAreas->delivery_time_ar = $_POST['RestaurantDelivery']['delivery_time'];
                        $restaurantDeliveryAreas->min_charge = $_POST['RestaurantDelivery']['min_charge'];
                        $restaurantDeliveryAreas->save();
                    } 
                }
            } else {
                unset($dataProvider->query[$key]);
            }
        }


        return $this->render('index', [
                    'dataProvider' => $dataProvider->query,
                    'restaurantUuid' => $restaurant_model->restaurant_uuid
        ]);
    }

    /**
     * Creates a new RestaurantDelivery model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate($restaurantUuid) {

        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

        $model = new RestaurantDelivery();
        $model->restaurant_uuid = $restaurant_model->restaurant_uuid;

        if ($model->load(Yii::$app->request->post())) {

            if ($model->restaurant_delivery_area_array)
                $model->saveRestaurantDeliveryArea($model->restaurant_delivery_area_array);

            return $this->redirect(['index', 'restaurantUuid' => $model->restaurant_uuid]);
        }
        return $this->render('create', [
                    'model' => $model,
                    'restaurantUuid' => $model->restaurant_uuid
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
    public function actionUpdate($restaurantUuid) {

        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

        $model = new RestaurantDelivery();
        $model->restaurant_uuid = $restaurant_model->restaurant_uuid;

        if ($model->load(Yii::$app->request->post())) {

            if ($model->restaurant_delivery_area_array)
                $model->saveRestaurantDeliveryArea($model->restaurant_delivery_area_array);

            //empty array so we goona del all restaurant delivery areas
            else
                RestaurantDelivery::deleteAll(['restaurant_uuid' => $restaurant_model->restaurant_uuid]);

            return $this->redirect(['index', 'restaurantUuid' => $restaurant_model->restaurant_uuid]);
        }

        return $this->render('update', [
                    'model' => $model,
                    'restaurantUuid' => $restaurantUuid,
        ]);
    }

    /**
     * Updates City delivery time
     * @param integer $area_id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateDeliveryTimeForCity($city_id, $restaurantUuid) {

       $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

                
        $model = new DeliveryZoneForm;
        $model->restaurant_uuid = $restaurant_model->restaurant_uuid;
        $model->city_id = $city_id;

        if ($model->load(Yii::$app->request->post()) && $model->applyTimingAndDeliveryFeeForAllAreasBelongsToThisCity()) {

            return $this->redirect(['index', 'restaurantUuid' => $restaurantUuid]);
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
    public function actionDelete($area_id, $restaurantUuid) {
        
        $this->findModel($restaurantUuid, $area_id)->delete();

        return $this->redirect(['index', 'restaurantUuid' => $restaurantUuid]);
    }

    /**
     * Finds the RestaurantDelivery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $restaurant_uuid
     * @param integer $area_id
     * @return RestaurantDelivery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($restaurantUuid, $area_id) {
        if (($model = RestaurantDelivery::find()->where(['restaurant_uuid' => Yii::$app->accountManager->getManagedAccount($restaurantUuid)->restaurant_uuid, 'area_id' => $area_id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
