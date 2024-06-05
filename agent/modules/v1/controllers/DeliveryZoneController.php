<?php

namespace agent\modules\v1\controllers;

use agent\models\Country;
use api\models\City;
use api\models\Item;
use api\models\Restaurant;
use api\models\State;
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
     * @param type $id
     * @param type $store_uuid
     * @return type
     */
    public function actionList($store_uuid = null, $business_location_id)
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
     * Create Delivery zone
     * @return array
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
     */
    public function actionDetail($store_uuid = null, $delivery_zone_id)
    {
//        $this->ownerCheck();
        return $this->findModel($delivery_zone_id, $store_uuid);
    }

    /**
     * Delete Delivery zone
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
     * Return list of areas available for delivery
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
     */
    public function actionListOfCountries($restaurant_uuid) {

        $store_model = $this->findStore($restaurant_uuid);

        $subQuery = $store_model->getDeliveryZones()
            ->select('delivery_zone.country_id')
            ->distinct();

        $countries = Country::find()
            ->andWhere(['IN', 'country.country_id', $subQuery])
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
     */
    protected function findStore($id)
    {
        $model = Restaurant::findOne($id);

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
