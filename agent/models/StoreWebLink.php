<?php


namespace agent\models;


class StoreWebLink extends \common\models\StoreWebLink
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

    /**
     * Gets query for [[WebLink]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWebLink($modelClass = "\agent\models\WebLink")
    {
        return parent::getWebLink($modelClass);
    }
}