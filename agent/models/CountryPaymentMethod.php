<?php


namespace agent\models;


class CountryPaymentMethod extends \common\models\CountryPaymentMethod
{
    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry($modelClass = "\agent\models\Country")
    {
        return parent::getCountry ($modelClass);
    }

    /**
     * Gets query for [[PaymentMethod]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod($modelClass = "\agent\models\PaymentMethod")
    {
        return parent::getPaymentMethod($modelClass);
    }
}
