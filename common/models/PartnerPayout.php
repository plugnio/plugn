<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "partner_payout".
 *
 * @property string $partner_payout_uuid
 * @property string $partner_uuid
 * @property string $payment_uuid
 * @property float|null $amount
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Partner $partners
 * @property Payment $payments
 */
class PartnerPayout extends \yii\db\ActiveRecord
{

    //Values for `payout_status`
    const PAYOUT_STATUS_UNPAID = 0;
    const PAYOUT_STATUS_PAID = 1;
    const PAYOUT_STATUS_PENDING = 2;

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
            [['partner_uuid', 'payment_uuid'], 'required'],
            [['amount'], 'number'],
            ['payout_status', 'in', 'range' => [self::PAYOUT_STATUS_UNPAID, self::PAYOUT_STATUS_PAID,self::PAYOUT_STATUS_PENDING]],
            [['partner_payout_uuid', 'partner_uuid'], 'string', 'max' => 60],
            [['payment_uuid'], 'string', 'max' => 36],
            [['partner_payout_uuid'], 'unique'],
            [['created_at', 'updated_at'], 'safe'],
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
            'payout_status' => 'Payout Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    /**
     *
     * @param type $insert
     * @param type $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave ($insert, $changedAttributes);

        if(isset($changedAttributes['payout_status']) && $changedAttributes['payout_status'] == self::PAYOUT_STATUS_PENDING && $this->order_status == self::PAYOUT_STATUS_PAID) {
          $this->payment->payout_status = $this->order_status;
          $this->payment->save(false);
        }
    }

    /**
     *
     * @return type
     */
    public function behaviors() {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'partner_payout_uuid',
                ],
                'value' => function() {
                    if (!$this->partner_payout_uuid)
                        $this->partner_payout_uuid = 'part_payout_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->partner_payout_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ]
        ];
    }


    /**
     * Gets query for [[PartnerUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPartners()
    {
        return $this->hasOne(Partner::className(), ['partner_uuid' => 'partner_uuid']);
    }

    /**
     * Gets query for [[PaymentUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayments()
    {
        return $this->hasOne(Payment::className(), ['payment_uuid' => 'payment_uuid']);
    }
}
