<?php


namespace agent\models;


class Customer extends \common\models\Customer
{
    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders($modelClass = "\agent\models\Order") {
        return parent::getOrders($modelClass);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActiveOrders($modelClass = "\agent\models\Order") {
        return parent::getActiveOrders ($modelClass);
    }

    /**
     * @param string $modelClass
     * @return mixed
     */
    public function getTotalSpent($modelClass = "\agent\models\Order") {
        return parent::getTotalSpent($modelClass);
    }

    /**
     * Gets query for [[CustomerVouchers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerVouchers($modelClass = "\agent\models\CustomerVoucher")
    {
        return parent::getCustomerVouchers($modelClass);
    }

    /**
     * Gets query for [[Orders]].
     *
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