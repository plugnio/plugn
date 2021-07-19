<?php


namespace agent\models;


class Subscription extends \common\models\Subscription
{

    /**
     * Gets query for [[PaymentUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptionPayment($modelClass = "\agent\models\SubscriptionPayment")
    {
        return parent::getSubscriptionPayment($modelClass);
    }

    /**
     * Gets query for [[PaymentUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod($modelClass = "\agent\models\PaymentMethod")
    {
        return parent::getPaymentMethod($modelClass);
    }

    /**
     * Gets query for [[Plan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlan($modelClass = "\agent\models\Plan") {
        return parent::getPlan($modelClass);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\agent\models\Restaurant") {
        return parent::getRestaurant($modelClass);
    }
}