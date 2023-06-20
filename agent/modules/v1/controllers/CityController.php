<?php

namespace agent\modules\v1\controllers;

use agent\models\DeliveryZone;
use Yii;
use yii\rest\Controller;
use yii\data\ActiveDataProvider;
use agent\models\City;


class CityController extends BaseController {

    /**
    * Get all cities data
     * @return type
     */
    public function actionList() {

        $keyword = Yii::$app->request->get('keyword');
        $country_id = Yii::$app->request->get('country_id');
        $state_id = Yii::$app->request->get('state_id');
        $store_uuid = Yii::$app->request->get('store_uuid');
        $delivery_zone_id = Yii::$app->request->get('delivery_zone_id');

        Yii::$app->accountManager->getManagedAccount($store_uuid);

        if($delivery_zone_id) {
            $dz = DeliveryZone::findOne(['delivery_zone_id' => $delivery_zone_id]);

            if($dz)
                $country_id = $dz->country_id;
        }

        $query =  City::find();

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
     * @param type $store_uuid
     * @param type $city_id
     * @return type
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
