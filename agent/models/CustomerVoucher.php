<?php


namespace agent\models;


class CustomerVoucher extends \common\models\CustomerVoucher
{
    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer($modelClass = "\agent\models\Customer")
    {
        return parent::getCustomer($modelClass);
    }

    /**
     * Gets query for [[Voucher]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVoucher($modelClass = "\agent\models\Voucher")
    {
        return parent::getVoucher($modelClass);
    }
}