<?php


namespace agent\models;


class CustomerBankDiscount extends \common\models\CustomerBankDiscount
{

    /**
     * Gets query for [[BankDiscount]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBankDiscount($modelClass = "\agent\models\BankDiscount")
    {
        return parent::getBankDiscount($modelClass);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer($modelClass = "\agent\models\Customer")
    {
        return parent::getCustomer($modelClass);
    }
}
