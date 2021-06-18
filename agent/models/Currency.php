<?php


namespace agent\models;


class Currency extends \common\models\Currency
{
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