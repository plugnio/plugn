<?php


namespace agent\models;


class RestaurantPaymentMethod extends \common\models\RestaurantPaymentMethod
{
    /**
     * Gets query for [[PaymentMethod]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod($modelClass = "\agent\models\PaymentMethod") {
        return parent::getPaymentMethod($modelClass);
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