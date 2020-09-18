<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bank".
 *
 * @property int $bank_id
 * @property string|null $bank_name
 * @property string $bank_iban_code
 * @property string $bank_swift_code
 * @property string $bank_address
 * @property string $bank_transfer_type
 * @property string $bank_created_at
 * @property string $bank_updated_at
 * @property int $deleted
 *
 * @property Voucher[] $vouchers
 */
class Bank extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bank';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bank_iban_code', 'bank_swift_code', 'bank_address', 'bank_transfer_type', 'bank_created_at', 'bank_updated_at'], 'required'],
            [['bank_created_at', 'bank_updated_at'], 'safe'],
            [['deleted'], 'integer'],
            [['bank_name', 'bank_address', 'bank_transfer_type'], 'string', 'max' => 100],
            [['bank_iban_code', 'bank_swift_code'], 'string', 'max' => 64],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'bank_id' => 'Bank ID',
            'bank_name' => 'Bank Name',
            'bank_iban_code' => 'Bank Iban Code',
            'bank_swift_code' => 'Bank Swift Code',
            'bank_address' => 'Bank Address',
            'bank_transfer_type' => 'Bank Transfer Type',
            'bank_created_at' => 'Bank Created At',
            'bank_updated_at' => 'Bank Updated At',
            'deleted' => 'Deleted',
        ];
    }

    /**
     * Gets query for [[Vouchers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVouchers()
    {
        return $this->hasMany(Voucher::className(), ['bank_id' => 'bank_id']);
    }
}
