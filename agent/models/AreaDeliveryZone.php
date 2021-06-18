<?php


namespace agent\models;


class AreaDeliveryZone extends \common\models\AgentAssignment
{
    /**
     * Gets query for [[Area]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArea($modelClass = "\agent\models\Area")
    {
        return parent::getArea($modelClass);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity($modelClass = "\agent\models\City")
    {
        return parent::getCity($modelClass);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\agent\models\Restaurant")
    {
        return parent::getRestaurant ($modelClass);
    }

    /**
     * Gets query for [[DeliveryZone]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryZone($modelClass = "\agent\models\DeliveryZone")
    {
        return parent::getDeliveryZone($modelClass);
    }

    /**
     * Gets query for [[BusinessLocation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessLocation($modelClass = "\agent\models\BusinessLocation")
    {
        return parent::getBusinessLocation($modelClass);
    }
}
