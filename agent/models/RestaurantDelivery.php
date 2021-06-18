<?php


namespace agent\models;


class RestaurantDelivery extends \common\models\RestaurantDelivery
{
    /**
     * Gets query for [[Area]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArea($modelClass = "\agent\models\Area") {
        return parent::getArea ($modelClass);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity($modelClass = "\agent\models\City") {
        return parent::getCity ($modelClass);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\agent\models\Restaurant") {
        return parent::getRestaurant ($modelClass);
    }
}