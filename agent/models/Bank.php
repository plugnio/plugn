<?php


namespace agent\models;


class Bank extends \common\models\AgentAssignment
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