<?php

namespace agent\modules\v1\controllers;

use Yii;
use agent\models\DeliveryZone;
use agent\models\State;
use yii\data\ActiveDataProvider;

class StateController extends BaseController
{
    /**
     * Get all cities data
     * 
     * @api {get} /states List
     * @apiName List
     * @apiGroup State
     * 
     * @apiSuccess {string} message Message.
     * @apiSuccess {string} operation Operation.
     */
    public function actionList() {

        $keyword = Yii::$app->request->get('keyword');
        $country_id = Yii::$app->request->get('country_id');
        $store_uuid = Yii::$app->request->get('store_uuid');
        $delivery_zone_id = Yii::$app->request->get('delivery_zone_id');

        Yii::$app->accountManager->getManagedAccount($store_uuid);

        if($delivery_zone_id) {
            $dz = DeliveryZone::findOne(['delivery_zone_id' => $delivery_zone_id]);

            if($dz)
                $country_id = $dz->country_id;
        }

        $query =  State::find()
            ->andWhere(['country_id' => $country_id]);

        if ($keyword) {
            $query->andWhere(['OR', 'name', $keyword]);
        }

        return new ActiveDataProvider([
            'query' => $query
        ]);
    }
}
