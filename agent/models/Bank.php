<?php


namespace agent\models;


class Bank extends \common\models\Bank
{
    /**
     * Gets query for [[BankDiscounts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBankDiscounts($modelClass = "\agent\models\Bank")
    {
        return parent::getBankDiscounts($modelClass);
    }
}
