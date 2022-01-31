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
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\Model;
use yii\helpers\ArrayHelper;

/**
 * DeliveryZoneController implements the CRUD actions for DeliveryZone model.
 */
class DeliveryZoneController extends Controller
{

    public $enableCsrfValidation = false;

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
    public function actionRenderCitiesChecboxList($storeUuid, $countryId)
    {

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
    public function actionIndex($storeUuid, $businessLocationId)
    {

        $store_model = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $business_location_model = BusinessLocation::find()
            ->andWhere(['restaurant_uuid' => $store_model->restaurant_uuid, 'business_location_id' => $businessLocationId])
            ->with(['country'])
            ->one();

        if ($business_location_model) {

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


    public function actionDeliverAllAreas($storeUuid, $deliveryZoneId)
    {
        $store_model = Yii::$app->accountManager->getManagedAccount($storeUuid);
        $model = $this->findModel($deliveryZoneId, $storeUuid);

        if ($model) {
            foreach ($model->country->getAreas()->all() as $key => $area) {

                if (!AreaDeliveryZone::find()->where(['area_id' => $area->area_id, 'restaurant_uuid' => $storeUuid])->exists()) {
                    $delivery_zone_area = new AreaDeliveryZone();
                    $delivery_zone_area->delivery_zone_id = $model->delivery_zone_id;
                    $delivery_zone_area->area_id = $area->area_id;
                    $delivery_zone_area->restaurant_uuid = $storeUuid;
                    $delivery_zone_area->save(false);
                }

            }

            $this->redirect(['index', 'storeUuid' => $storeUuid, 'businessLocationId' => $model->business_location_id]);


        }

    }


    public function actionRemoveTaxOverride($storeUuid, $deliveryZoneId)
    {
        $store_model = Yii::$app->accountManager->getManagedAccount($storeUuid);
        $model = $this->findModel($deliveryZoneId, $storeUuid);

        $model->delivery_zone_tax = null;
        $model->save();

        $this->redirect(['index', 'storeUuid' => $storeUuid, 'businessLocationId' => $model->business_location_id]);

    }


    /**
     * Creates a new DeliveryZone model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($storeUuid, $businessLocationId, $countryId = null)
    {

        $store_model = Yii::$app->accountManager->getManagedAccount($storeUuid);
        $business_location_model = BusinessLocation::find()->where(['restaurant_uuid' => $store_model->restaurant_uuid, 'business_location_id' => $businessLocationId])->one();

        if ($business_location_model) {


            $delivery_zone_model = new DeliveryZone();
            $delivery_zone_model->business_location_id = $business_location_model->business_location_id;
            $delivery_zone_model->restaurant_uuid = $storeUuid;


            if ($countryId)
                $delivery_zone_model->country_id = $countryId;


            if ($delivery_zone_model->load(Yii::$app->request->post()) && $delivery_zone_model->save()) {


                if ($delivery_zone_model->country->getAreas()->count() > 0) {

                    return $this->render('select-area', [
                        'deliveryZoneId' => $delivery_zone_model->delivery_zone_id,
                        'selectedCountry' => $delivery_zone_model->country->country_name,
                        'storeUuid' => $storeUuid
                    ]);


                } else {

                    $area_delivery_zone_model = new AreaDeliveryZone();
                    $area_delivery_zone_model->delivery_zone_id = $delivery_zone_model->delivery_zone_id;
                    $area_delivery_zone_model->country_id = $delivery_zone_model->country_id;
                    $area_delivery_zone_model->restaurant_uuid = $storeUuid;
                    $area_delivery_zone_model->save(false);


                    $this->redirect(['index', 'storeUuid' => $storeUuid, 'businessLocationId' => $businessLocationId]);
                }

            }

            return $this->render('create', [
                'model' => $delivery_zone_model,
                'storeUuid' => $storeUuid
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
    public function actionUpdate($id, $storeUuid)
    {

        $store_model = Yii::$app->accountManager->getManagedAccount($storeUuid);

        $model = $this->findModel($id, $storeUuid);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->redirect(['index', 'storeUuid' => $storeUuid, 'businessLocationId' => $model->business_location_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'storeUuid' => $storeUuid
        ]);
    }


    public function actionUpdateDeliveryZoneVat($deliveryZoneId, $storeUuid)
    {

        $model = $this->findModel($deliveryZoneId, $storeUuid);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            return $this->redirect(['index', 'storeUuid' => $storeUuid, 'businessLocationId' => $model->business_location_id]);

        }

        return $this->render('update-vat', [
            'storeUuid' => $storeUuid,
            'model' => $model
        ]);


    }

    /**
     * list cities with delivery area count
     * @param $id
     * @param $storeUuid
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionCities($id, $storeUuid)
    {
        $model = $this->findModel($id, $storeUuid);

        $query = City::find()
            ->where(['country_id' => $model->country_id]);

        $provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('cities', [
            //'delivery_zone_id' => $id,
            'cityProvider' => $provider,
            'cities' => [],//$cities,
            'model' => $model,
        ]);
    }

    /**
     * enable delivery area in zone
     * @param $id
     * @param $storeUuid
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionEnable($id, $storeUuid)
    {
        $area_id = Yii::$app->request->get('area_id');

        $model = $this->findModel($id, $storeUuid);

        $delivery_zone_area = new AreaDeliveryZone();
        $delivery_zone_area->delivery_zone_id = $model->delivery_zone_id;
        $delivery_zone_area->area_id = $area_id;
        $delivery_zone_area->restaurant_uuid = $storeUuid;

        if($delivery_zone_area->save()) {
            // return $this->goBack();
        }

        //return $this->goBack();

        return $this->redirect(['delivery-zone/update-areas?storeUuid=1&=1&id=1',
            'storeUuid' => $storeUuid,
            'city_id' => $delivery_zone_area->area->city_id,
            'id' => $id
        ]);
    }

    /**
     * disable delivery area in zone
     * @param $id
     * @param $storeUuid
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDisable($id, $storeUuid)
    {
        $area_id = Yii::$app->request->get('area_id');

        $model = $this->findModel($id, $storeUuid);

        $delivery_zone_area = AreaDeliveryZone::findOne([
            'delivery_zone_id' => $model->delivery_zone_id,
            'area_id' => $area_id
        ]);

        if(!$delivery_zone_area) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if($delivery_zone_area->delete()) {
            //   return $this->goBack();
        }

        //return $this->goBack();

        return $this->redirect(['delivery-zone/update-areas?storeUuid=1&=1&id=1',
            'storeUuid' => $storeUuid,
            'city_id' => $delivery_zone_area->area->city_id,
            'id' => $id
        ]);
    }

    /**
     * enable all delivery area in zone for given city
     * @param $id
     * @param $storeUuid
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionEnableAll($id, $storeUuid)
    {
        $city_id = Yii::$app->request->get('city_id');

        $model = $this->findModel($id, $storeUuid);

        $subQuery = 'select area_id from area_delivery_zone where delivery_zone_id="'.$id.'" 
            AND city_id="'.$city_id.'"';

        $areas = Area::find()
            ->with('city')
            ->andWhere(['city_id' => $city_id])
            ->andWhere(new Expression('area_id NOT IN (' . $subQuery . ')'))
            ->all();

        /*AreaDeliveryZone::deleteAll([
            'delivery_zone_id' => $model->delivery_zone_id,
            'restaurant_uuid' => $storeUuid
        ]);*/

        $data = [];

        foreach ($areas as $area)
        {
            $data[] = [
                'country_id' => $area->city->country_id,
                'city_id' => $area->city_id,
                'delivery_zone_id' => $model->delivery_zone_id,
                'restaurant_uuid' => $model->restaurant_uuid,
                'area_id' =>  $area->area_id
            ];
        }

        Yii::$app->db->createCommand()->batchInsert('area_delivery_zone', [
            'country_id',
            'city_id',
            'delivery_zone_id',
            'restaurant_uuid',
            'area_id'
        ], $data)->execute();

        //return $this->goBack();

        return $this->redirect(['delivery-zone/update-areas?storeUuid=1&=1&id=1',
            'storeUuid' => $storeUuid,
            'city_id' => $city_id,
            'id' => $id
        ]);
    }
    
    /**
     * disable delivery area in zone for given city
     * @param $id
     * @param $storeUuid
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDisableAll($id, $storeUuid)
    {
        $city_id = Yii::$app->request->get('city_id');

        $model = $this->findModel($id, $storeUuid);

        AreaDeliveryZone::deleteAll([
            'delivery_zone_id' => $model->delivery_zone_id,
            'city_id' => $city_id
        ]);

        //return $this->goBack();

        return $this->redirect(['delivery-zone/update-areas?storeUuid=1&=1&id=1',
            'storeUuid' => $storeUuid,
            'city_id' => $city_id,
            'id' => $id
        ]);
    }

    /**
     * @param $id
     * @param $storeUuid
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdateAreas($id, $storeUuid)
    {
        $city_id = Yii::$app->request->get('city_id');

        $model = $this->findModel($id, $storeUuid);

        $city = City::findOne([
            'country_id' => $model->country_id,
            'city_id' => $city_id
        ]);

        if(!$city) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        //handling if the saveSelectedAreas button has been pressed

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post())) {

            if ($model['selectedAreas']) {

                AreaDeliveryZone::deleteAll(['delivery_zone_id' => $model->delivery_zone_id, 'restaurant_uuid' => $storeUuid]);

                foreach ($model['selectedAreas'] as $cities) {

                    if (is_array($cities)) {
                        foreach ($cities as $areas) {

                            if (is_array($areas)) {

                                foreach ($areas as $area_id) {

                                    if ($area_id) {
                                        $delivery_zone_area = new AreaDeliveryZone();
                                        $delivery_zone_area->delivery_zone_id = $model->delivery_zone_id;
                                        $delivery_zone_area->area_id = $area_id;
                                        $delivery_zone_area->restaurant_uuid = $storeUuid;
                                        $delivery_zone_area->save(false);
                                    }
                                }
                            }
                        }
                    }
                }

            } else {
                AreaDeliveryZone::deleteAll(['delivery_zone_id' => $model->delivery_zone_id, 'restaurant_uuid' => $storeUuid]);
            }

            return $this->redirect(['index', 'storeUuid' => $storeUuid, 'businessLocationId' => $model->business_location_id]);
        }

        $provider = new ActiveDataProvider([
            'query' => $city->getAreas(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('update-areas', [
            'city' => $city,
            'model' => $model,
            'dataProvider' => $provider,
        ]);
    }

    /**
     * Deletes an existing DeliveryZone model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $storeUuid)
    {
        $model = $this->findModel($id, $storeUuid);
        $businessLocationId = $model->business_location_id;
        $model->delete();

        $this->redirect(['index', 'storeUuid' => $storeUuid, 'businessLocationId' => $businessLocationId]);
    }

    /**
     * Finds the DeliveryZone model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DeliveryZone the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $storeUuid = null)
    {
        $model = Yii::$app->accountManager->getManagedAccount($storeUuid)
            ->getDeliveryZones()
            ->where(['delivery_zone_id' => $id])
            ->one();

        if ($model != null)
            return $model;

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
