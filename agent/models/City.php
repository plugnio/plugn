<?php

namespace agent\models;

use Yii;

class City extends \common\models\City
{
    public function extraFields()
    {
        $fields = parent::fields();

        $fields['totalDeliveryAreas'] = function ($model) {

            $delivery_zone_id = Yii::$app->request->get('delivery_zone_id');

            return $model->getAreaDeliveryZones()
                ->filterWhere(['delivery_zone_id' => $delivery_zone_id])
                ->count();
        };

        return $fields;
    }
}