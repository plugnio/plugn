<?php


namespace agent\models;


class PaymentMethod extends \common\models\PaymentMethod
{
    /**
     * Gets query for [[RestaurantPaymentMethods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantPaymentMethods($modelClass = "\agent\models\RestaurantPaymentMethod")
    {
        return parent::getRestaurantPaymentMethods($modelClass);
    }

    /**
     * Gets query for [[RestaurantUus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurants($modelClass = "\agent\models\Restaurant")
    {
        return parent::getRestaurants($modelClass);
    }
}