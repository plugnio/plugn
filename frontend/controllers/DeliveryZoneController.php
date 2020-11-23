<?php

namespace frontend\controllers;

use Yii;
use common\models\DeliveryZone;
use common\models\Restaurant;
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

        if (Yii::$app->request->isPost){
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
    public function actionIndex($restaurantUuid) {
        $restaurant_model = Yii::$app->accountManager->getManagedAccount($restaurantUuid);

        $searchModel = new DeliveryZoneSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $restaurant_model->restaurant_uuid);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'restaurantUuid' => $restaurantUuid
        ]);
    }

    /**
     * Creates a new DeliveryZone model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($restaurantUuid) {
        $model = new DeliveryZone();

        if ($model->load(Yii::$app->request->post()) && $model->save() ) {

            foreach ($model['selectedAreas'] as $cities) {

                if (is_array($cities)) {
                    foreach ($cities as $areas) {

                            if (is_array($areas)) {

                      foreach ($areas as $area_id) {

                            $delivery_zone_area_model = new AreaDeliveryZone();
                            $delivery_zone_area_model->country_id = $model->country_id;
                            $delivery_zone_area_model->city_id = $model->city_id;
                            $delivery_zone_area_model->delivery_zone_id = $model->delivery_zone_id;
                            $delivery_zone_area_model->area_id = $area_id;
                            $delivery_zone_area_model->save(false);

                          }
                    }
                    }
                }
            }

            $this->redirect(['index', 'restaurantUuid' => $restaurantUuid]);
        }


        return $this->render('create', [
                    'model' => $model,
                    'restaurantUuid' => $restaurantUuid
        ]);
    }

    /**
     * Updates an existing DeliveryZone model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $restaurantUuid) {
        $model = $this->findModel($id, $restaurantUuid);


          $areaQuery = $model->getAreas()->all();
          $areaArray[] = ArrayHelper::map($areaQuery, 'area_id', 'area_name');


                  if ($model->load(Yii::$app->request->post())) {

                    AreaDeliveryZone::deleteAll(['delivery_zone_id' => $model->delivery_zone_id]);

                      if ($model->save()){
                        foreach ($model['selectedAreas'] as $cities) {

                            if (is_array($cities)) {
                                foreach ($cities as $areas) {

                                        if (is_array($areas)) {

                                  foreach ($areas as $area_id) {

                                        $delivery_zone_area_model = new AreaDeliveryZone();
                                        $delivery_zone_area_model->delivery_zone_id = $model->delivery_zone_id;
                                        $delivery_zone_area_model->area_id = $area_id;
                                        $delivery_zone_area_model->save(false);

                                      }
                                }
                                }
                            }
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
    public function actionDelete($id) {
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
        $model = Yii::$app->accountManager->getManagedAccount($restaurantUuid)->getDeliveryZones()->one();

        if ($model != null)
            return $model;

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
