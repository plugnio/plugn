<?php


namespace agent\models;


class WebLink extends \common\models\WebLink
{
    /**
     * Gets query for [[StoreWebLinks]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStoreWebLinks($modelClass = "\agent\models\StoreWebLink")
    {
        return parent::getStoreWebLinks($modelClass);
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
}