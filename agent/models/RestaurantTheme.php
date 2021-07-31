<?php


namespace agent\models;


class RestaurantTheme extends \common\models\RestaurantTheme
{
    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\agent\models\Restaurant") {
        return parent::getRestaurant($modelClass);
    }
}