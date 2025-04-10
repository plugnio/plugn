<?php

namespace agent\modules\v1\controllers;

use agent\models\DeliveryZone;
use Yii;
use yii\db\Expression;
use yii\data\ActiveDataProvider;
use agent\models\City;


class CityController extends BaseController {

    /**
     * Get all cities data
     * @return ActiveDataProvider
     * 
     * @api {get} /cities Get all cities data
     * @apiName ListCities
     * 
     * @apiParam {string} keyword Keyword.
     * @apiParam {string} country_id Country ID.
     * @apiParam {string} state_id State ID.
     * @apiParam {string} store_uuid Store UUID.
     * @apiParam {string} delivery_zone_id Delivery zone ID.
     * 
     * @apiGroup City
     *
     * @apiSuccess {Array} cities List of cities.
     */
    public function actionList() {

        $keyword = Yii::$app->request->get('keyword');
        $country_id = Yii::$app->request->get('country_id');
        $state_id = Yii::$app->request->get('state_id');
        $store_uuid = Yii::$app->request->get('store_uuid');
        $delivery_zone_id = Yii::$app->request->get('delivery_zone_id');

        Yii::$app->accountManager->getManagedAccount($store_uuid);

        $query =  City::find();

        if($delivery_zone_id) {
            $dz = DeliveryZone::findOne(['delivery_zone_id' => $delivery_zone_id]);

            if($dz) {
                $country_id = $dz->country_id;

                if($dz->country && $dz->country->iso == "KW") {
                    $query->andWhere(new Expression('state_id IS NULL'));
                    //hide areas added as city in kuwait by google api
                }
            }
        }

        if ($keyword) {
          $query->andWhere([
              'OR',
              ['like', 'city_name', $keyword],
              ['like', 'city_name_ar', $keyword]
          ]);
        }

        if($state_id) {
            $query->andWhere(['state_id' => $state_id]);
        }

        $query->andWhere(['country_id' => $country_id]);

        return new ActiveDataProvider([
          'query' => $query
        ]);
    }

    /**
    * Return City detail
     * @param string $store_uuid
     * @param string $city_id
     * @return City
     * 
     * @api {get} /cities/:city_id Get city detail
     * @apiName GetCityDetail
     * 
     * @apiParam {string} city_id City ID.
     * 
     * @apiGroup City
     *
     * @apiSuccess {Array} city City.
     */
    public function actionDetail($city_id)
    {
        return $this->findModel($city_id);
    }

    /**
     * @param $city_id
     * @return City
     */
    protected function findModel($city_id)
    {
        //Yii::$app->accountManager->getManagedAccount();

        $model = City::findOne([
            'city_id' => $city_id
        ]);

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested record does not exist.');
        }
    }
}
