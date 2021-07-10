<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "partner_payout".
 *
 * @property string $partner_payout_uuid
 * @property string $partner_uuid
 * @property string $payment_uuid
 * @property float|null $amount
 * @property int $partner_status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Partner $partnerUu
 * @property Payment $paymentUu
 */
class PartnerPayout extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'partner_payout';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['partner_payout_uuid', 'partner_uuid', 'payment_uuid', 'created_at', 'updated_at'], 'required'],
            [['amount'], 'number'],
            [['partner_status', 'created_at', 'updated_at'], 'integer'],
            [['partner_payout_uuid', 'partner_uuid'], 'string', 'max' => 60],
            [['payment_uuid'], 'string', 'max' => 36],
            [['partner_payout_uuid'], 'unique'],
            [['partner_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Partner::className(), 'targetAttribute' => ['partner_uuid' => 'partner_uuid']],
            [['payment_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Payment::className(), 'targetAttribute' => ['payment_uuid' => 'payment_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'partner_payout_uuid' => 'Partner Payout Uuid',
            'partner_uuid' => 'Partner Uuid',
            'payment_uuid' => 'Payment Uuid',
            'amount' => 'Amount',
            'partner_status' => 'Partner Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[PartnerUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPartnerUu()
    {
        return $this->hasOne(Partner::className(), ['partner_uuid' => 'partner_uuid']);
    }

    /**
     * Gets query for [[PaymentUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentUu()
    {
        return $this->hasOne(Payment::className(), ['payment_uuid' => 'payment_uuid']);
    }
}
