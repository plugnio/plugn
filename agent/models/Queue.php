<?php


namespace agent\models;


class Queue extends \common\models\Queue
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