<?php


namespace agent\models;


class TapQueue extends \common\models\TapQueue
{
    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\agent\models\Restaurant")
    {
        return parent::getRestaurant($modelClass);
    }
}