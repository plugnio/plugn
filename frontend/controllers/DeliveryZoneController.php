<?php

namespace frontend\controllers;

use Yii;
use common\models\DeliveryZone;
use common\models\Restaurant;
use common\models\Country;
use common\models\BusinessLocation;
use common\models\Area;
use common\models\City;
use common\models\AreaDeliveryZone;
use frontend\models\DeliveryZoneSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * DeliveryZoneController implements the CRUD actions for DeliveryZone model.
 */
class DeliveryZoneController extends Controller {

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
     * Lists all DeliveryZone models.
     * @return mixed
     */
    public function actionRenderCitiesChecboxList($restaurantUuid, $countryId) {

        if (Yii::$app->request->isPost) {
            $selectedAreas = Yii::$app->request->post('selectedAreas');
        }

        $cities = City::find()->where(['country_id' => $countryId])->all();

        return $this->renderPartial('_cities', [
                    'cities' => $cities,
                    'selectedAreas' => $selectedAreas
        ]);
    }

    /**
     * Lists all DeliveryZone models.
     * @return mixed
     */
    public function actionIndex($restaurantUuid, $businessLocationId) {

        $store_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

        if($business_location_model = BusinessLocation::find()->where(['restaurant_uuid' => $store_model->restaurant_uuid, 'business_location_id' => $businessLocationId])->one()) {

          $searchModel = new DeliveryZoneSearch();
          $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $store_model->restaurant_uuid, $businessLocationId);

          return $this->render('index', [
                      'searchModel' => $searchModel,
                      'dataProvider' => $dataProvider,
                      'business_location_model' => $business_location_model,
                      'store_model' => $store_model
          ]);
      }
    }

    /**
     * Creates a new DeliveryZone model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($restaurantUuid, $businessLocationId, $countryId) {
        $store_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);
        $country_model = Country::findOne($countryId);

        if($country_model && $business_location_model = BusinessLocation::find()->where(['restaurant_uuid' => $store_model->restaurant_uuid, 'business_location_id' => $businessLocationId])->one()) {


        $model = new DeliveryZone();
        $model->business_location_id = $business_location_model->business_location_id;
        $model->country_id = $country_model->country_id;

        if ($model->load(Yii::$app->request->post())) {

          $storeDeliveryZones = $store_model->getDeliveryZonesForSpecificCountry($model->country_id);

          if($storeDeliveryZones->exists() && $store_model->getAreaDeliveryZonesForSpecificCountry($model->country_id)->count() == 0){
            Yii::$app->session->setFlash('errorResponse', "Cant add another zone1");
            return $this->redirect(['index', 'restaurantUuid' => $restaurantUuid]);
          }

          if( $model->save()){
            if ($model['selectedAreas']) {
                foreach ($model['selectedAreas'] as $cities) {


                    if (is_array($cities)) {
                        foreach ($cities as $areas) {

                            if (is_array($areas)) {

                                foreach ($areas as $area_id) {

                                    if ($area_id) {
                                        $delivery_zone_area_model = new AreaDeliveryZone();
                                        $delivery_zone_area_model->delivery_zone_id = $model->delivery_zone_id;
                                        $delivery_zone_area_model->area_id = $area_id;
                                        $delivery_zone_area_model->restaurant_uuid = $restaurantUuid;
                                        $delivery_zone_area_model->save(false);
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
              $delivery_zone_area_model = new AreaDeliveryZone();
              $delivery_zone_area_model->delivery_zone_id = $model->delivery_zone_id;
              $delivery_zone_area_model->country_id = $model->country_id;
              $delivery_zone_area_model->restaurant_uuid = $restaurantUuid;
              $delivery_zone_area_model->save(false);
            }



                        if($store_model->getDeliveryZonesForSpecificCountry($model->country_id)->count() > 1 &&  !AreaDeliveryZone::find()->where(['delivery_zone_id' => $model->delivery_zone_id])->exists()   ){
                          DeliveryZone::deleteAll(['delivery_zone_id' => $model->delivery_zone_id]);
                          Yii::$app->session->setFlash('errorResponse', "Cant add another zone2");
                          return $this->redirect(['index', 'restaurantUuid' => $restaurantUuid]);
                        }


            }


            $this->redirect(['index', 'restaurantUuid' => $restaurantUuid]);
        }


        return $this->render('create', [
                    'model' => $model,
                    'restaurantUuid' => $restaurantUuid
        ]);

      }

    }

    /**
     * Updates an existing DeliveryZone model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $restaurantUuid) {

        $store_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

        $model = $this->findModel($id, $restaurantUuid);


        if ($model->load(Yii::$app->request->post())) {

          $storeDeliveryZones = $store_model->getDeliveryZonesForSpecificCountry($model->country_id);


          if($model->save()){
            AreaDeliveryZone::deleteAll(['delivery_zone_id' => $model->delivery_zone_id]);
            if (($model['selectedAreas'])) {

                foreach ($model['selectedAreas'] as $cities) {

                    if (is_array($cities)) {

                        foreach ($cities as $areas) {
                            if (is_array($areas)) {


                                foreach ($areas as $area_id) {

                                    $delivery_zone_area_model = new AreaDeliveryZone();
                                    $delivery_zone_area_model->delivery_zone_id = $model->delivery_zone_id;
                                    $delivery_zone_area_model->area_id = $area_id;
                                    $delivery_zone_area_model->restaurant_uuid = $restaurantUuid;
                                    $delivery_zone_area_model->save(false);
                                }
                            }
                        }
                    }
                }
            } else {
              $delivery_zone_area_model = new AreaDeliveryZone();
              $delivery_zone_area_model->delivery_zone_id = $model->delivery_zone_id;
              $delivery_zone_area_model->country_id = $model->country_id;
              $delivery_zone_area_model->restaurant_uuid = $restaurantUuid;
              $delivery_zone_area_model->save(false);
            }


            if( !AreaDeliveryZone::find()->where(['delivery_zone_id' => $model->delivery_zone_id])->exists()   ){
              DeliveryZone::deleteAll(['delivery_zone_id' => $model->delivery_zone_id]);
              Yii::$app->session->setFlash('errorResponse', "Cant add another zone");
              return $this->redirect(['index', 'restaurantUuid' => $restaurantUuid]);
            }



          }

            $this->redirect(['index', 'restaurantUuid' => $restaurantUuid]);
        }


        return $this->render('update', [
                    'model' => $model,
                    'restaurantUuid' => $restaurantUuid
        ]);
    }

    /**
     * Deletes an existing DeliveryZone model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $restaurantUuid) {
        $this->findModel($id, $restaurantUuid)->delete();

        return $this->redirect(['index', 'restaurantUuid' => $restaurantUuid]);
    }

    /**
     * Finds the DeliveryZone model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeliveryZone the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $restaurantUuid) {
        $model = Yii::$app->accountManager->getManagedAccount($restaurantUuid)->getDeliveryZones()->where(['delivery_zone_id' => $id])->one();

        if ($model != null)
            return $model;

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
