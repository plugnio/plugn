<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "store_domain_subscription_payment".
 *
 * @property string $store_domain_subscription_payment_uuid
 * @property string $subscription_uuid
 * @property string $from
 * @property string $to
 * @property float $total_amount
 * @property float $cost_amount
 * @property int $created_by
 * @property int|null $updated_by
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property Admin $createdBy
 * @property StoreDomainSubscription $subscriptionUu
 * @property Admin $updatedBy
 */
class StoreDomainSubscriptionPayment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'store_domain_subscription_payment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //'store_domain_subscription_payment_uuid', 'created_by', 'created_at'
            [['subscription_uuid', 'from', 'to', 'total_amount', 'cost_amount'], 'required'],
            [['from', 'to', 'created_at', 'updated_at'], 'safe'],
            [['total_amount', 'cost_amount'], 'number'],
            [['created_by', 'updated_by'], 'integer'],
            [['store_domain_subscription_payment_uuid', 'subscription_uuid'], 'string', 'max' => 60],
            [['subscription_uuid'], 'unique'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => Admin::class, 'targetAttribute' => ['created_by' => 'admin_id']],
            [['subscription_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => StoreDomainSubscription::class, 'targetAttribute' => ['subscription_uuid' => 'subscription_uuid']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => Admin::class, 'targetAttribute' => ['updated_by' => 'admin_id']],
        ];
    }

    /**
     *
     * @return array
     */
    public function behaviors() {
        return [
            [
                'class' => \yii\behaviors\AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'store_domain_subscription_payment_uuid',
                ],
                'value' => function() {
                    if (!$this->store_domain_subscription_payment_uuid)
                        $this->store_domain_subscription_payment_uuid = 'sdsp_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->store_domain_subscription_payment_uuid;
                }
            ],
            [
                'class' => BlameableBehavior::className()
            ],
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => "updated_at",
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'store_domain_subscription_payment_uuid' => Yii::t('app', 'Store Domain Subscription Payment Uuid'),
            'subscription_uuid' => Yii::t('app', 'Subscription Uuid'),
            'from' => Yii::t('app', 'From'),
            'to' => Yii::t('app', 'To'),
            'total_amount' => Yii::t('app', 'Total Amount (Vendor will pay in KWD)'),
            'cost_amount' => Yii::t('app', 'Cost Amount (Plugn will pay in KWD)'),
            'created_by' => Yii::t('app', 'Created By'),
            'updated_by' => Yii::t('app', 'Updated By'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(Admin::class, ['admin_id' => 'created_by']);
    }

    /**
     * Gets query for [[Subscription]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscription()
    {
        return $this->hasOne(StoreDomainSubscription::class, ['subscription_uuid' => 'subscription_uuid']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(Admin::class, ['admin_id' => 'updated_by']);
    }
}
