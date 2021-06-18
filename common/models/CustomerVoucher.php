<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "customer_voucher".
 *
 * @property int $customer_voucher_id
 * @property int|null $customer_id
 * @property int|null $voucher_id
 *
 * @property Customer $customer
 * @property Voucher $voucher
 */
class CustomerVoucher extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_voucher';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_id', 'voucher_id'], 'integer'],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
            [['voucher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Voucher::className(), 'targetAttribute' => ['voucher_id' => 'voucher_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'customer_voucher_id' => 'Customer Voucher ID',
            'customer_id' => 'Customer ID',
            'voucher_id' => 'Voucher ID',
        ];
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

    /**
     * Gets query for [[Voucher]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVoucher($modelClass = "\common\models\Voucher")
    {
        return $this->hasOne($modelClass::className(), ['voucher_id' => 'voucher_id']);
    }
}
