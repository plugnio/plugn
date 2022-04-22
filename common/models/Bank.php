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
 * @property BankDiscount[] $bankDiscounts
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
            [['bank_name', 'bank_iban_code', 'bank_swift_code', 'bank_address', 'bank_transfer_type', 'bank_created_at', 'bank_updated_at'], 'required'],
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
            'bank_id' => Yii::t('app', 'Bank ID'),
            'bank_name' => Yii::t('app', 'Bank Name'),
            'bank_iban_code' => Yii::t('app', 'Bank Iban Code'),
            'bank_swift_code' => Yii::t('app', 'Bank Swift Code'),
            'bank_address' => Yii::t('app', 'Bank Address'),
            'bank_transfer_type' => Yii::t('app', 'Bank Transfer Type'),
            'bank_created_at' => Yii::t('app', 'Bank Created At'),
            'bank_updated_at' => Yii::t('app', 'Bank Updated At'),
            'deleted' => Yii::t('app', 'Deleted'),
        ];
    }

    /**
     * Gets query for [[BankDiscounts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBankDiscounts($modelClass = "\common\models\BankDiscount")
    {
        return $this->hasMany($modelClass::className(), ['bank_id' => 'bank_id']);
    }
}
