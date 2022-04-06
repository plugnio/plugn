<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "customer_bank_discount".
 *
 * @property int $customer_bank_discount_id
 * @property int|null $customer_id
 * @property int|null $bank_discount_id
 *
 * @property BankDiscount $bankDiscount
 * @property Customer $customer
 */
class CustomerBankDiscount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_bank_discount';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'bank_discount_id'], 'required'],
            [['customer_id', 'bank_discount_id'], 'integer'],
            [['bank_discount_id'], 'exist', 'skipOnError' => true, 'targetClass' => BankDiscount::className(), 'targetAttribute' => ['bank_discount_id' => 'bank_discount_id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'customer_bank_discount_id' => Yii::t('app','Customer Bank Discount ID'),
            'customer_id' => Yii::t('app','Customer ID'),
            'bank_discount_id' => Yii::t('app','Bank Discount ID'),
        ];
    }

    /**
     * Gets query for [[BankDiscount]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBankDiscount($modelClass = "\common\models\BankDiscount")
    {
        return $this->hasOne($modelClass::className(), ['bank_discount_id' => 'bank_discount_id']);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer($modelClass = "\common\models\Customer")
    {
        return $this->hasOne($modelClass::className(), ['customer_id' => 'customer_id']);
    }
}
