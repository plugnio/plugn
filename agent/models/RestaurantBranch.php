<?php


namespace agent\models;


class RestaurantBranch extends \common\models\RestaurantBranch
{

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\agent\models\Restaurant")
    {
        return parent::getRestaurant ($modelClass);
    }
}
