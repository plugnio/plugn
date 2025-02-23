<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "agent_email_verify_attempt".
 *
 * @property string $aeva_uuid
 * @property string $code
 * @property string $agent_email
 * @property string $ip_address
 * @property string $created_at
 */
class AgentEmailVerifyAttempt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'agent_email_verify_attempt';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agent_email','code','ip_address'], 'required'],
            ['agent_email', 'email'],
            [['created_at'], 'safe'],
            [['aeva_uuid'], 'string', 'max' => 60],
            [['code'], 'string', 'max' => 32],
            [['agent_email'], 'string', 'max' => 50],
            [['ip_address'], 'string', 'max' => 45],
            [['aeva_uuid'], 'unique'],
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
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'aeva_uuid',
                ],
                'value' => function() {
                    if(!$this->aeva_uuid)
                        $this->aeva_uuid = 'ceva_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->aeva_uuid;
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
            'aeva_uuid' => Yii::t('app', 'Agent Email Verify Attempt UUID'),
            'agent_email' => Yii::t('app', 'Email'),
            'code' => Yii::t('app', 'Code'),
            'ip_address' => Yii::t('app', 'Ip Address'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }
}
