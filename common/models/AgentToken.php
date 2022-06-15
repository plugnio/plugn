<?php

namespace common\models;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "agent_token".
 *
 * @property string $token_uuid
 * @property int $agent_id
 * @property string|null $token_value
 * @property string|null $token_device
 * @property string|null $token_device_id
 * @property int|null $token_status
 * @property string|null $token_last_used_datetime
 * @property string|null $token_expiry_datetime
 * @property string|null $token_created_datetime
 *
 * @property Agent $agent
 */
class AgentToken extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const STATUS_EXPIRED = 5;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agent_token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['agent_id', 'token_value', 'token_status'], 'required'],
            [['token_status'], 'integer'],
            [['token_last_used_datetime', 'token_expiry_datetime', 'token_created_datetime'], 'safe'],
            [['token_value', 'token_device', 'token_device_id'], 'string', 'max' => 255],
            [
                ['agent_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Agent::className(),
                'targetAttribute' => ['agent_id' => 'agent_id']
            ],
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'token_uuid',
                ],
                'value' => function() {
                    if(!$this->token_uuid)
                        $this->token_uuid = 'agt_token_'. Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->token_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'token_created_datetime',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'token_uuid' => Yii::t('app', 'Token UUID'),
            'agent_id' => Yii::t('app', 'Agent ID'),
            'token_value' => Yii::t('app', 'Token Value'),
            'token_device' => Yii::t('app', 'Token Device'),
            'token_device_id' => Yii::t('app', 'Token Device ID'),
            'token_status' => Yii::t('app', 'Token Status'),
            'token_last_used_datetime' => Yii::t('app', 'Token Last Used Datetime'),
            'token_expiry_datetime' => Yii::t('app', 'Token Expiry Datetime'),
            'token_created_datetime' => Yii::t('app', 'Token Created Datetime'),
        ];
    }

    /**
     * Generates unique access token to be used as value
     * @return string
     */
    public static function generateUniqueTokenString(){
        $randomString = Yii::$app->getSecurity()->generateRandomString();
        if(!static::findOne(['token_value' => $randomString ])){
                return $randomString;

        }else return static::generateUniqueTokenString();
    }

    /**
     * Gets query for [[Agent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAgent($modelClass = "\common\models\Agent")
    {
        return $this->hasOne($modelClass::className(), ['agent_id' => 'agent_id']);
    }
}
