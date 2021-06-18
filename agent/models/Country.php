<?php


namespace agent\models;


class Country extends \common\models\Country
{
    /**
     * Gets query for [[Cities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCities($modelClass = "\agent\models\City")
    {
        return parent::getCities($modelClass);
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
     * Gets query for [[DeliveryZones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryZones($modelClass = "\agent\models\DeliveryZone")
    {
        return parent::getDeliveryZones ($modelClass);
    }

    /**
     * Gets query for [[CountryPaymentMethods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountryPaymentMethods($modelClass = "\agent\models\CountryPaymentMethod")
    {
        return parent::getCountryPaymentMethods ($modelClass);
    }

    /**
     * Gets query for [[PaymentMethods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethods($modelClass = "\agent\models\PaymentMethod")
    {
        return parent::getPaymentMethods ($modelClass);
    }

    /**
     * Gets query for [[Restaurants]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurants($modelClass = "\agent\models\Restaurant")
    {
        return parent::getRestaurants ($modelClass);
    }
}