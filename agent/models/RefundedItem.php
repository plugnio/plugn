<?php


namespace agent\models;


class RefundedItem extends \common\models\RefundedItem
{
    /**
     * Gets query for [[ItemUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem($modelClass = "\agent\models\Item")
    {
        return parent::getItem($modelClass);
    }

    /**
     * Gets query for [[ItemUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\agent\models\Restaurant")
    {
        return parent::getRestaurant($modelClass);
    }

    /**
     * Gets query for [[OrderItem]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItem($modelClass = "\agent\models\OrderItem")
    {
        return parent::getOrderItem($modelClass);
    }

    /**
     * Gets query for [[ItemImages]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemImages($modelClass = "\agent\models\ItemImage")
    {
        return parent::getItemImages($modelClass);
    }

    /**
     * Gets query for [[OrderUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder($modelClass = "\agent\models\Order")
    {
        return parent::getOrder($modelClass);
    }

    /**
     * Gets query for [[Refund]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefund($modelClass = "\agent\models\Refund")
    {
        return parent::getRefund($modelClass);
    }
}