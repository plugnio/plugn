<?php

namespace agent\modules\v1\controllers;

use agent\models\Country;
use api\models\City;
use api\models\Item;
use api\models\Restaurant;
use api\models\State;
use common\models\Area;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Exp;
use Yii;
use yii\db\Expression;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use agent\models\DeliveryZone;
use agent\models\AreaDeliveryZone;
use agent\models\BusinessLocation;


/**
 * 
 * 'GET' => 'list',
                        'GET detail' => 'detail',
                        'GET country-cities/<country_id>' => 'country-cities',
                        'GET cities/<state_id>' => 'cities',
                        'GET states/<country_id>' => 'states',
                        'GET areas/<city_id>' => 'areas',
                        'GET list-of-countries/<restaurant_uuid>' => 'list-of-countries',
                        'GET list-of-areas/<restaurant_uuid>/<country_id>' => 'list-of-areas',
                        'GET detail-by-location' => 'detail-by-location',
                        'GET by-location' => 'by-location',
                        'POST create' => 'create',
                        'POST add-state-to-delivery-area' => "add-state-to-delivery-area",
                        'PATCH <delivery_zone_id>/<store_uuid>' => 'update',
                        'PATCH <delivery_zone_id>' => 'update',
                        "DELETE remove-state-from-delivery-area/<state_id>/<delivery_zone_id>" => "remove-state-from-delivery-area",
                        'DELETE cancel-override/<delivery_zone_id>/<store_uuid>' => 'cancel-override',
                        'DELETE cancel-override/<delivery_zone_id>' => 'cancel-override',
                        'DELETE <delivery_zone_id>/<store_uuid>' => 'delete',
                        'DELETE <delivery_zone_id>' => 'delete',
 */
class DeliveryZoneController extends BaseController
{
    /**
     * only owner will have access
     */
//    public function beforeAction($action)
//    {
//        parent::beforeAction ($action);
//
//        if($action->id == 'options') {
//            return true;
//        }
//
//        if(!Yii::$app->accountManager->isOwner()) {
//            throw new \yii\web\BadRequestHttpException(
//                Yii::t('agent', 'You are not allowed to manage business locations. Please contact with store owner')
//            );
//
//            return false;
//        }
//
//        //should have access to store
//
//        Yii::$app->accountManager->getManagedAccount();
//
//        return true;
//    }

    private function ownerCheck()
    {
        if (!Yii::$app->accountManager->isOwner()) {
            throw new \yii\web\BadRequestHttpException(
                Yii::t('agent', 'You are not allowed to manage discounts. Please contact with store owner')
            );
        }

        //should have access to store
        Yii::$app->accountManager->getManagedAccount();
        return true;
    }

    /**
     * Get all delivery zones
     * @param string $business_location_id
     * @param string $store_uuid
     * @return ActiveDataProvider
     * 
     * @api {get} /delivery-zones Get all delivery zones
     * @apiName ListDeliveryZones
     * @apiParam {string} business_location_id Business location ID.
     * @apiGroup DeliveryZone
     *
     * @apiSuccess {Array} deliveryZones List of delivery zones.
     */
    public function actionList($business_location_id, $store_uuid = null)
    {
//        $this->ownerCheck();
        $store = Yii::$app->accountManager->getManagedAccount($store_uuid);

            $query = DeliveryZone::find()
                ->andWhere([
                    'restaurant_uuid' => $store->restaurant_uuid,
                    'business_location_id' => $business_location_id
                ]);

            return new ActiveDataProvider([
                'query' => $query
            ]);
    }

    /**
     * Return list of cities available for state
     * @param string $country_id
     * @return ActiveDataProvider
     * 
     * @api {get} /delivery-zones/country-cities/:country_id Get list of cities available for state
     * @apiName CountryCities
     * @apiParam {string} country_id Country ID.
     * 
     * @apiGroup DeliveryZone
     *
     * @apiSuccess {Array} cities List of cities.
     */
    public function actionCountryCities($country_id)
    {
        $keyword = Yii::$app->request->get("keyword");

        $country = Country::findOne($country_id);

        if (!$country) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $query = $country->getCities("\api\models\City");

        if($country && $country->iso == "KW") {
            $query->andWhere(new Expression('state_id IS NULL'));
            //hide areas added as city in kuwait by google api
        }

        if ($keyword) {
            $query->andWhere([
                'OR',
                ['like', 'city_name', $keyword],
                ['like', 'city_name_ar', $keyword]
            ]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Create Delivery zone
     * @return array
     * 
     * @api {post} /delivery-zones Create delivery zone
     * @apiName CreateDeliveryZone
     * @apiParam {string} store_uuid Store UUID.
     * @apiParam {string} business_location_id Business location ID.
     * @apiParam {string} country_id Country ID.
     * @apiParam {string} delivery_time Delivery time.
     * @apiParam {string} time_unit Time unit.
     * @apiParam {string} delivery_fee Delivery fee.
     * @apiParam {string} min_charge Minimum charge.
     * @apiParam {string} delivery_zone_tax Delivery zone tax.
     * @apiParam {string} deliver_whole_country Deliver whole country.
     * 
     * @apiGroup DeliveryZone
     *
     * @apiSuccess {string} operation success|error.
     * @apiSuccess {string} message Message.
     * @apiSuccess {Array} model Delivery zone.
     */
    public function actionCreate()
    {
//        $this->ownerCheck();
        $store_uuid = Yii::$app->request->getBodyParam("store_uuid");

        $store = Yii::$app->accountManager->getManagedAccount($store_uuid);

        $business_location_id = Yii::$app->request->getBodyParam("business_location_id");

        $business_location = BusinessLocation::findOne([
            'business_location_id' => $business_location_id,
            'restaurant_uuid' => $store->restaurant_uuid
        ]);

        if (!$business_location)
            throw new NotFoundHttpException('business location not found.');

        $model = new DeliveryZone();
        $model->restaurant_uuid = $store->restaurant_uuid;
        $model->business_location_id = $business_location->business_location_id;
        $model->country_id = Yii::$app->request->getBodyParam("country_id");
        $model->delivery_time = (int)Yii::$app->request->getBodyParam("delivery_time");
        $model->time_unit = Yii::$app->request->getBodyParam("time_unit");
        $model->delivery_fee = (float)Yii::$app->request->getBodyParam("delivery_fee");
        $model->min_charge = (float)Yii::$app->request->getBodyParam("min_charge");
        $model->delivery_zone_tax = (float)Yii::$app->request->getBodyParam("delivery_zone_tax");
        $model->deliver_whole_country = Yii::$app->request->getBodyParam("deliver_whole_country");

        if ($model->save()) {

            if($model->deliver_whole_country) 
            {
                $area_delivery_zone = new AreaDeliveryZone();
                $area_delivery_zone->delivery_zone_id = $model->delivery_zone_id;
                $area_delivery_zone->country_id = $model->country_id;
                $area_delivery_zone->restaurant_uuid = $store->restaurant_uuid;
                $area_delivery_zone->save(false);
            }

        } else {
          return [
              "operation" => "error",
              "message" => $model->errors
          ];
        }

        $zone = DeliveryZone::find()
            ->andWhere(['delivery_zone_id' => $model->delivery_zone_id])
            ->with(['country'])
            ->asArray()
            ->one();

        return [
            "operation" => "success",
            "message" => Yii::t('agent', "Delivery Zone created successfully"),
            "model" => $zone
        ];

    }

    /**
     * Update Delivery Zone
     * @param string $delivery_zone_id
     * @param string $store_uuid
     * @return array
     * 
     * @api {PATCH} /delivery-zones Update delivery zone
     * @apiName UpdateDeliveryZone
     * 
     * @apiParam {string} delivery_zone_id Delivery zone ID.
     * @apiParam {string} store_uuid Store UUID.
     * @apiParam {string} business_location_id Business location ID.
     * @apiParam {string} country_id Country ID.
     * @apiParam {string} delivery_time Delivery time.
     * @apiParam {string} time_unit Time unit.
     * @apiParam {string} delivery_fee Delivery fee.
     * @apiParam {string} min_charge Minimum charge.
     * @apiParam {string} delivery_zone_tax Delivery zone tax.
     * @apiParam {string} deliver_whole_country Deliver whole country.
     * 
     * @apiGroup DeliveryZone
     *
     * @apiSuccess {string} operation success|error.
     */
    public function actionUpdate($delivery_zone_id, $store_uuid = null)
    {
//        $this->ownerCheck();
        $store = Yii::$app->accountManager->getManagedAccount($store_uuid);

        $business_location_id = Yii::$app->request->getBodyParam("business_location_id");

        $business_location = BusinessLocation::findOne(['business_location_id' => $business_location_id, 'restaurant_uuid' => $store->restaurant_uuid]);

        if (!$business_location)
            throw new NotFoundHttpException('The business location record does not exist.');

        $model = $this->findModel($delivery_zone_id, $store_uuid);

        $model->country_id = Yii::$app->request->getBodyParam("country_id");
        $model->business_location_id = $business_location->business_location_id;
        $model->delivery_time = (int)Yii::$app->request->getBodyParam("delivery_time");
        $model->time_unit = Yii::$app->request->getBodyParam("time_unit");
        $model->delivery_fee = (float)Yii::$app->request->getBodyParam("delivery_fee");
        $model->min_charge = (float)Yii::$app->request->getBodyParam("min_charge");
        $model->delivery_zone_tax = (float)Yii::$app->request->getBodyParam("delivery_zone_tax");
        $model->deliver_whole_country = Yii::$app->request->getBodyParam("deliver_whole_country");

        if (!$model->save()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent',"We've faced a problem updating the delivery zone")
                ];
            }
        }

        if($model->deliver_whole_country) 
        {
            //AreaDeliveryZone::deleteAll(['delivery_zone_id' => $delivery_zone_id]);

            $area_delivery_zone = AreaDeliveryZone::find()
                ->andWhere(["delivery_zone_id" => $model->delivery_zone_id])
                ->andWhere(new Expression('area_delivery_zone.country_id="'.$model->country_id.'" AND area_delivery_zone.state_id IS NULL 
                    AND area_delivery_zone.city_id IS NULL AND area_delivery_zone.area_id IS NULL AND area_delivery_zone.is_deleted = 0'))
                ->one();

            if(!$area_delivery_zone) {
                $area_delivery_zone = new AreaDeliveryZone();
                $area_delivery_zone->delivery_zone_id = $model->delivery_zone_id;
                $area_delivery_zone->country_id = $model->country_id;
                $area_delivery_zone->restaurant_uuid = $store->restaurant_uuid;
                $area_delivery_zone->save(false);
            }
        }

        $zone = DeliveryZone::find()
            ->andWhere(['delivery_zone_id' => $model->delivery_zone_id])
            ->with(['country'])
            ->asArray()
            ->one();

        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Delivery zone updated successfully"),
            "model" => $zone
        ];
    }

    /**
     * Return Delivery zone detail
     * @param type $store_uuid
     * @param type $order_uuid
     * @return type
     * 
     * @api {get} /delivery-zones/:delivery_zone_id Get delivery zone detail
     * @apiName GetDeliveryZoneDetail
     * @apiParam {string} delivery_zone_id Delivery zone ID.
     * @apiParam {string} store_uuid Store UUID.
     * @apiGroup DeliveryZone
     *
     * @apiSuccess {Array} deliveryZone Delivery zone.
     */
    public function actionDetail($delivery_zone_id, $store_uuid = null)
    {
//        $this->ownerCheck();
        return $this->findModel($delivery_zone_id, $store_uuid);
    }

    /**
     * Delete Delivery zone
     * 
     * @api {delete} /delivery-zones/:delivery_zone_id Delete delivery zone
     * @apiName DeleteDeliveryZone
     * @apiParam {string} delivery_zone_id Delivery zone ID.
     * @apiParam {string} store_uuid Store UUID.
     * @apiGroup DeliveryZone
     *
     * @apiSuccess {string} operation success|error.
     */
    public function actionDelete($delivery_zone_id, $store_uuid = null)
    {
        $this->ownerCheck();

        $transaction = Yii::$app->db->beginTransaction();

        AreaDeliveryZone::deleteAll(['delivery_zone_id'=> $delivery_zone_id, 'restaurant_uuid' => $store_uuid]);
        
        $model = $this->findModel($delivery_zone_id, $store_uuid);

        if (!$model->delete()) {
            $transaction->rollBack();
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent',"We've faced a problem deleting the delivery zone")
                ];
            }
        }

        $transaction->commit();
        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Delivery Zone deleted successfully")
        ];
    }

    /**
     * cancel-override Delivery zone
     * 
     * @api {DELETE} /delivery-zones/cancel-override Cancel override delivery zone
     * @apiName CancelOverrideDeliveryZone
     * @apiParam {string} delivery_zone_id Delivery zone ID.
     * @apiParam {string} store_uuid Store UUID.
     * @apiGroup DeliveryZone
     *
     * @apiSuccess {string} operation success|error.
     */
    public function actionCancelOverride($delivery_zone_id, $store_uuid = null)
    {
        $this->ownerCheck();

        $model = $this->findModel($delivery_zone_id, $store_uuid);

        $model->delivery_zone_tax = null;

        if (!$model->save()) {
            if (isset($model->errors)) {
                return [
                    "operation" => "error",
                    "message" => $model->errors
                ];
            } else {
                return [
                    "operation" => "error",
                    "message" => Yii::t('agent',"We've faced a problem cancelling VAT Charged")
                ];
            }
        }

        return [
            "operation" => "success",
            "message" => Yii::t('agent',"Delivery Zone VAT Charged cancelled successfully")
        ];
    }

    /**
     * Return list of states available for delivery
     * 
     * @api {get} /delivery-zones/states/:country_id Get list of states available for delivery
     * @apiName States
     * @apiParam {string} country_id Country ID.
     * @apiGroup DeliveryZone
     *
     * @apiSuccess {Array} states List of states.
     */
    public function actionStates($country_id) {

        //$store_id = Yii::$app->request->getHeaders()->get('Store-Id');

        $keyword = Yii::$app->request->get("keyword");

        $country = Country::findOne($country_id);

        if(!$country) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $query = $country->getStates("\api\models\State");

        /*if($store_id) {
            $query->joinWith('areaDeliveryZones', true, 'inner join')
                ->andWhere(['area_delivery_zone.restaurant_uuid' => $store_id]);
        }*/

        if($keyword) {
            $query->andWhere(['like', 'name', $keyword]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Return list of cities available for delivery
     * 
     * @api {get} /delivery-zones/cities/:state_id Get list of cities available for delivery
     * @apiName Cities
     * @apiParam {string} state_id State ID.
     * 
     * @apiGroup DeliveryZone
     *
     * @apiSuccess {Array} cities List of cities.
     */
    public function actionCities($state_id) {

        //$store_id = Yii::$app->request->getHeaders()
        //    ->get('Store-Id');

        $keyword = Yii::$app->request->get("keyword");

        $state = State::findOne($state_id);

        if(!$state) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $query = $state->getCities("\api\models\City");

        /*if($store_id) {
            $query->joinWith('areaDeliveryZones', true, 'inner join')
                ->andWhere(['area_delivery_zone.restaurant_uuid' => $store_id]);
        }*/

        if($keyword) {
            $query->andWhere([
                'OR',
                ['like', 'city_name', $keyword],
                ['like', 'city_name_ar', $keyword]
            ]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * Return list of cities available for delivery
     * 
     * @api {get} /delivery-zones/areas/:city_id Get list of areas available for delivery
     * @apiName Areas
     * @apiParam {string} city_id City ID.
     * @apiGroup DeliveryZone
     *
     * @apiSuccess {Array} areas List of areas.
     */
    public function actionAreas($city_id) {

        //$store_id = Yii::$app->request->getHeaders()
        //    ->get('Store-Id');

        $keyword = Yii::$app->request->get("keyword");

        $city = City::findOne($city_id);

        if(!$city) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        $query = $city->getAreas("\common\models\Area");

        /*if($store_id) {
            $query->joinWith('areaDeliveryZones', true, 'inner join')
                ->andWhere(['area_delivery_zone.restaurant_uuid' => $store_id]);
        }*/

        if($keyword) {
            $query->andWhere([
                'OR',
                ['like', 'area_name', $keyword],
                ['like', 'area_name_ar', $keyword]
            ]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    /**
     * delivery zone by city, state and country
     * @return void
     * 
     * @api {get} /delivery-zones/detail-by-location Delivery zone by city, state and country
     * @apiName DetailByLocation
     * 
     * @apiParam {string} city_id City ID.
     * @apiParam {string} state_id State ID.
     * @apiParam {string} country_id Country ID.
     * @apiGroup DeliveryZone
     *
     * @apiSuccess {Array} deliveryZone Delivery zone.
     */
    public function actionDetailByLocation()
    {
        $store_id = Yii::$app->request->getHeaders()
            ->get('Store-Id');

        $city_id = Yii::$app->request->get("city_id");
        $state_id = Yii::$app->request->get("state_id");
        $country_id = Yii::$app->request->get("country_id");

        $store = $this->findStore($store_id);

        $areaDeliveryZone = $store->getAreaDeliveryZones()
            ->andWhere([
                'city_id' => $city_id,
                'state_id' => $state_id,
                'country_id' => $country_id
            ])
            ->one();

        //state wide

        if(!$areaDeliveryZone) {

            $areaDeliveryZone = $store->getAreaDeliveryZones()
                ->andWhere([
                    'state_id' => $state_id,
                   // 'country_id' => $country_id
                ])
                ->andWhere(new Expression("city_id IS NULL"))
                ->one();
        }

        //country wide

        if(!$areaDeliveryZone) {
            $areaDeliveryZone = $store->getAreaDeliveryZones()
                ->andWhere([
                    'country_id' => $country_id
                ])
                ->andWhere(new Expression("city_id IS NULL AND state_id IS NULL"))
                ->one();
        }

        return $areaDeliveryZone? $areaDeliveryZone->deliveryZone: null;
    }

    /**
     * Return delivery zone by location
     * 
     * @api {get} /delivery-zones/by-location Delivery zone by location 
     * @apiName ByLocation
     * 
     * @apiParam {string} area_id Area ID.
     * @apiParam {string} city_id City ID.
     * @apiParam {string} state_id State ID.
     * 
     * @apiGroup DeliveryZone
     *
     * @apiSuccess {Array} deliveryZone Delivery zone.
     */
    public function actionByLocation()
    {
        $area_id = Yii::$app->request->get('area_id');
        $city_id = Yii::$app->request->get('city_id');
        $state_id = Yii::$app->request->get("state_id");

        if (!$state_id && !$area_id && !$city_id) {
            return [
                "operation" => "error",
                "message" => "Location details missing"
            ];
        }

        $query = $this->findStore()
            ->getAreaDeliveryZones();

        if ($area_id) //for kuwait
        {
            $area = Area::find()
                ->with(['city'])
                ->andWhere(['area_id' => $area_id])
                ->one();

            if(!$area || !$area->city) {
                return null;
            }

            //area or whole country, not having states in Kuwait

            $query->andWhere([
                'OR',
                new Expression('area_delivery_zone.country_id="'.$area->city->country_id.'" AND area_delivery_zone.state_id IS NULL 
                    AND area_delivery_zone.city_id IS NULL AND area_delivery_zone.area_id IS NULL'),
                ['area_delivery_zone.area_id' => $area_id]
            ]);
        }
        else if ($city_id)
        {
            $city = \common\models\City::find()
                ->andWhere(['city_id' => $city_id])
                ->one();

            if(!$city) {
                return null;
            }

            //city or whole country

            $conditions = [
                'OR',
                new Expression('area_delivery_zone.country_id="'.$city->country_id.'" AND area_delivery_zone.state_id IS NULL 
                    AND area_delivery_zone.city_id IS NULL AND area_delivery_zone.area_id IS NULL'),
                ['area_delivery_zone.city_id' => $city_id]
            ];

            // or whole state, some cities might not have state, so checking if having state

            if($city->state_id) {
                $conditions[] = new Expression('area_delivery_zone.state_id="'.$city->state_id.'" AND 
                    area_delivery_zone.city_id IS NULL AND 
                    area_delivery_zone.area_id IS NULL');
            }

            $query->andWhere($conditions);
        }
        else if ($state_id)
        {
            $state = \common\models\State::find()
                ->andWhere(['state_id' => $state_id])
                ->one();

            if(!$state) {
                return null;
            }

            //delivering to whole state or whole country

            $query->andWhere([
                "OR",
                new Expression('area_delivery_zone.state_id="'.$state_id.'" AND 
                    area_delivery_zone.city_id IS NULL AND 
                    area_delivery_zone.area_id IS NULL'),
                new Expression('area_delivery_zone.country_id="'.$state->country_id.'" AND 
                    area_delivery_zone.city_id IS NULL AND 
                    area_delivery_zone.area_id IS NULL')
            ]);
        }

        return $query->one();
    }

    /**
     * Return list of areas available for delivery
     * 
     * @api {get} /delivery-zones/list-of-areas/:restaurant_uuid/:country_id Get list of areas available for delivery
     * @apiName ListOfAreas
     * @apiParam {string} restaurant_uuid Restaurant UUID.
     * @apiParam {string} country_id Country ID.
     * @apiGroup DeliveryZone
     *
     * @apiSuccess {Array} areas List of areas.
     */
    public function actionListOfAreas($restaurant_uuid, $country_id) {

        $store_model = Restaurant::findOne($restaurant_uuid);

            $countryCities = City::find()
                ->andWhere(['country_id' => $country_id])
                ->asArray()
                ->all();

            if($countryCities) {

                $areaDeliveryZones = $store_model->getAreaDeliveryZonesForSpecificCountry($country_id)->asArray()->all();

                foreach ($countryCities as $cityKey => $city) {
                    foreach ($areaDeliveryZones as $areaDeliveryZoneKey => $areaDeliveryZone) {

                        if(isset($areaDeliveryZone['area'])){
                            if($areaDeliveryZone['area']['city_id'] == $city['city_id']){
                                $countryCities[$cityKey]['areas'][] = $areaDeliveryZone;
                            }
                        }
                        else {
                            $countryCities[$cityKey]['areas'][] = $areaDeliveryZone;
                        }
                    }
                }
            }

            $citiesData = [];
            foreach ($countryCities as $key => $city) {
                if(isset($city['areas']))
                    $citiesData []= $city;
            }

            if(!empty($citiesData))
                return $citiesData ;
    }

    /**
     * Return List of countries available for delivery
     * 
     * @api {get} /delivery-zones/list-of-countries/:restaurant_uuid Get list of countries available for delivery
     * @apiName ListOfCountries
     * @apiParam {string} restaurant_uuid Restaurant UUID.
     * @apiGroup DeliveryZone
     *
     * @apiSuccess {Array} countries List of countries.
     */
    public function actionListOfCountries($restaurant_uuid) {

        $keyword = Yii::$app->request->get("keyword");

        $store_model = $this->findStore($restaurant_uuid);

        $subQuery = $store_model->getDeliveryZones()
            ->select('delivery_zone.country_id')
            ->distinct();

        $countryQuery = Country::find()
            ->andWhere(['IN', 'country.country_id', $subQuery]);

        if (!empty($keyword)) {
            $countryQuery->andWhere([
                "OR",
                ['like', 'country_name', $keyword],
                ['like', 'country_name_ar', $keyword],
            ]);
        }

        $countries = $countryQuery
            ->all();

        $data = [];

        foreach ($countries as $country) {

            $areas = $store_model->getAreaDeliveryZones()
                ->andWhere(new Expression('state_id IS NOT NULL OR city_id IS NOT NULL OR area_id IS NOT NULL'))
                ->andWhere(['area_delivery_zone.country_id' => $country->country_id])
                ->count();

            $deliveryZone = null;

            $deliveryZone = $store_model->getDeliveryZones()
                ->andWhere(['delivery_zone.country_id' => $country->country_id])
                ->one();

            $data[] = array_merge($country->attributes, [
                'areas' => $areas,
                'deliveryZone' => $deliveryZone,
                'delivery_zone_id' => $deliveryZone? $deliveryZone->delivery_zone_id : null
            ]);
        }

        return $data;
    }

    /**
     * @param $state_id
     * @param $delivery_zone_id
     * @return string[]
     * 
     * @api {post} /delivery-zones/remove-state-from-delivery-area/:state_id/:delivery_zone_id Remove state from delivery area
     * @apiName RemoveStateFromDeliveryArea
     * @apiParam {string} state_id State ID.
     * @apiParam {string} delivery_zone_id Delivery zone ID.
     * @apiGroup DeliveryZone
     *
     * @apiSuccess {string} operation success|error.
     */
    public function actionRemoveStateFromDeliveryArea($state_id, $delivery_zone_id) {

        $store_id = Yii::$app->request->getHeaders()
            ->get('Store-Id');

        //validate delivery zone access

        $this->findModel($delivery_zone_id);

        AreaDeliveryZone::updateAll([
            'is_deleted' => 1
        ], [
            'restaurant_uuid' => $store_id,
            "state_id" => $state_id,
            'delivery_zone_id' => $delivery_zone_id
        ]);

        return [
            "operation" => "success"
        ];
    }

    /**
     * @return string[]
     * 
     * @api {post} /delivery-zones/add-state-to-delivery-area/:state_id/:delivery_zone_id Add state to delivery area
     * @apiName AddStateToDeliveryArea
     * @apiParam {string} state_id State ID.
     * @apiParam {string} delivery_zone_id Delivery zone ID.
     * @apiGroup DeliveryZone
     *
     * @apiSuccess {string} operation success|error.
     */
    public function actionAddStateToDeliveryArea() {

        $store_id = Yii::$app->request->getHeaders()
            ->get('Store-Id');

        $state_id = Yii::$app->request->getBodyParam("state_id");
        $delivery_zone_id = Yii::$app->request->getBodyParam("delivery_zone_id");

        //validate delivery zone access

        $this->findModel($delivery_zone_id);

        $exists = AreaDeliveryZone::find()
            ->andWhere(['area_delivery_zone.is_deleted' => 0, 'restaurant_uuid' => $store_id, "state_id" => $state_id, 'delivery_zone_id' => $delivery_zone_id])
            ->andWhere(new Expression("area_id IS NULL AND city_id IS NULL"))
            ->exists();

        if($exists) {
            return [
                "operation" => "error",
                "message" => "Already added!"
            ];
        }

        $state = State::findOne($state_id);

        if(!$state) {
            return [
                "operation" => "error",
                "message" => "State not found!"
            ];
        }

        $model = new AreaDeliveryZone;
        $model->restaurant_uuid = $store_id;
        $model->delivery_zone_id = $delivery_zone_id;
        $model->state_id = $state_id;
        $model->country_id = $state->country_id;

        if(!$model->save()) {
            return [
                "operation" => "error",
                "message" => $model->errors
            ];
        }

        return [
            "operation" => "success",
            "message" => "added",
            "model" => $model
        ];
    }

    /**
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * 
     * @api {get} /delivery-zones/find-store/:id Find store
     * @apiName FindStore
     * @apiParam {string} id Store UUID.
     * @apiGroup DeliveryZone
     *
     * @apiSuccess {Array} store Store.
     */
    protected function findStore($id = null)
    {
        $model = Yii::$app->accountManager->getManagedAccount($id);
        //$model = Restaurant::findOne($id);

        if ($model !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Finds the Delivery zone model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BusinessLocation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($delivery_zone_id, $store_uuid = null)
    {
        $store = Yii::$app->accountManager->getManagedAccount($store_uuid);

        $model = DeliveryZone::find()->where([
                'delivery_zone_id' => $delivery_zone_id,
                'restaurant_uuid' => $store->restaurant_uuid
            ])
            ->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The delivery zone record does not exist.');
        }
    }
}
