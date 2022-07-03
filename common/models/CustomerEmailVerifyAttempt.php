<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "customer_email_verify_attempt".
 *
 * @property string $ceva_uuid
 * @property string $code
 * @property string $customer_email
 * @property string $ip_address
 * @property string $created_at
 */
class CustomerEmailVerifyAttempt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'customer_email_verify_attempt';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_email','code','ip_address'], 'required'],
            ['customer_email', 'email'],
            [['created_at'], 'safe'],
            [['ceva_uuid'], 'string', 'max' => 60],
            [['code'], 'string', 'max' => 32],
            [['customer_email'], 'string', 'max' => 50],
            [['ip_address'], 'string', 'max' => 45],
            [['ceva_uuid'], 'unique'],
        ];
    }

    /**
     * @return array
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => null,
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'ceva_uuid',
                ],
                'value' => function() {
                    if(!$this->ceva_uuid)
                        $this->ceva_uuid = 'ceva_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->ceva_uuid;
                }
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = parent::fields();

        unset($fields['code']);

        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ceva_uuid' => Yii::t('app', 'Customer Email Verify Attempt UUID'),
            'customer_email' => Yii::t('app', 'Email'),
            'code' => Yii::t('app', 'Code'),
            'ip_address' => Yii::t('app', 'Ip Address'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }
}
