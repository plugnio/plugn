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

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry($modelClass = "\agent\models\Country")
    {
        return parent::getCountry ($modelClass);
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreas($modelClass = "\agent\models\Area")
    {
        return parent::getAreas ($modelClass);
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreaDeliveryZones($modelClass = "\agent\models\AreaDeliveryZone")
    {
        return parent::getAreaDeliveryZones ($modelClass);
    }

    /**
     * Gets query for [[RestaurantDeliveryAreas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantDeliveryAreas($modelClass = "\agent\models\RestaurantDelivery")
    {
        return parent::getRestaurantDeliveryAreas ($modelClass);
    }
}