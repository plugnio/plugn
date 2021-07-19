<?php


namespace agent\models;


class OrderItemExtraOption extends \common\models\OrderItemExtraOption
{
    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder($modelClass = "\agent\models\Order") {
        return parent::getOrder($modelClass);
    }

    /**
     * Gets query for [[Order]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\agent\models\Restaurant") {
        return parent::getRestaurant($modelClass);
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency($modelClass = "\agent\models\Currency")
    {
        return parent::getCurrency ($modelClass);
    }

    /**
     * Gets query for [[ExtraOption]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExtraOption($modelClass = "\agent\models\ExtraOption") {
        return parent::getExtraOption($modelClass);
    }

    /**
     * Gets query for [[OrderItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItem($modelClass = "\agent\models\OrderItem") {
        return parent::getOrderItem($modelClass);
    }

    /**
     * Gets query for [[OrderItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem($modelClass = "\agent\models\Item")
    {
        return parent::getItem ($modelClass);
    }
}