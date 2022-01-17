<?php


namespace agent\models;


class BankDiscount extends \common\models\BankDiscount
{
    public function fields()
    {
        $field = parent::fields();

        $field['redeemed'] = function ($model) {
            return $model->getCustomerBankDiscounts()->count();
        };

        return $field;
    }

    /**
     * Gets query for [[CustomerBankDiscounts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerBankDiscounts($modelClass = "\agent\models\CustomerBankDiscount")
    {
        return parent::getCustomerBankDiscounts($modelClass);
    }

    /**
     * Gets query for [[Bank]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBank($modelClass = "\agent\models\Bank")
    {
        return parent::getBank($modelClass);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\agent\models\Restaurant")
    {
        return parent::getRestaurant ($modelClass);
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency($modelClass = "\agent\models\Currency")
    {
        return parent::getCurrency($modelClass);
    }
}
