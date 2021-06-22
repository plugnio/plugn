<?php


namespace agent\models;


class SubscriptionPayment extends \common\models\SubscriptionPayment
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscription($modelClass = "\agent\models\Subscription") {
        return parent::getSubscription($modelClass);
    }

    /**
     * Gets query for [[Plan]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlan($modelClass = "\agent\models\Plan") {
        return parent::getPlan ($modelClass);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\agent\models\Restaurant") {
        return parent::getRestaurant ($modelClass);
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
}