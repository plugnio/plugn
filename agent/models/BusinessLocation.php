<?php


namespace agent\models;


class BusinessLocation extends \common\models\BusinessLocation
{
    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry($modelClass = "\agent\models\Country")
    {
        return parent::getCountry($modelClass);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\agent\models\Restaurant")
    {
        return parent::getRestaurant($modelClass);
    }

    //
    // /**
    //  * Gets query for [[DeliveryZones]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getDeliveryZones($modelClass = "\agent\models\DeliveryZone")
    // {
    //     return $this->hasMany($modelClass::className(), ['business_location_id' => 'business_location_id'])->joinWith(['areas']);
    // }

    /**
     * Gets query for [[DeliveryZone]].
     * @param string $modelClass
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryZones($modelClass = "\agent\models\DeliveryZone")
    {
        return parent::getDeliveryZones($modelClass);
    }

    /**
     * Gets query for [[DeliveryZone]].
     * @param $countryId
     * @param string $modelClass
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryZoneByCountryId($countryId, $modelClass = "\agent\models\DeliveryZone")
    {
        return parent::getDeliveryZoneByCountryId($countryId, $modelClass);
    }

    /**
     * Gets query for [[AreaDeliveryZones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreaDeliveryZones($modelClass = "\agent\models\AreaDeliveryZone")
    {
        return parent::getAreaDeliveryZones($modelClass);
    }
}
