<?php


namespace agent\models;


class Refund extends \common\models\Refund
{
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
     * Gets query for [[Payment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayment($modelClass = "\agent\models\Payment")
    {
        return parent::getPayment($modelClass);
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

    /**
     * Gets query for [[RefundedItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefundedItems($modelClass = "\agent\models\RefundedItem")
    {
        return parent::getRefundedItems($modelClass);
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
}