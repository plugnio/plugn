<?php


namespace agent\models;


class Payment extends \common\models\Payment
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer($modelClass = "\agent\models\Customer") {
        return parent::getCustomer ($modelClass);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder($modelClass = "\agent\models\Order") {
        return parent::getOrder ($modelClass);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems($modelClass = "\agent\models\OrderItem") {
        return parent::getOrderItems ($modelClass);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\agent\models\Restaurant") {
        return parent::getRestaurant($modelClass);
    }

    /**
     * Gets query for [[Subscriptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActiveSubscription($modelClass = "\agent\models\Subscription") {
        return parent::getActiveSubscription ($modelClass);
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency($modelClass = "\agent\models\Currency")
    {
        return parent::getCurrency($modelClass);
    }
}