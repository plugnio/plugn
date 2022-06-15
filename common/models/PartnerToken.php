<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "partner_token".
 *
 * @property string $token_uuid
 * @property string $partner_uuid
 * @property string|null $token_value
 * @property string|null $token_device
 * @property string|null $token_device_id
 * @property int|null $token_status
 * @property string|null $token_last_used_datetime
 * @property string|null $token_expiry_datetime
 * @property string|null $token_created_datetime
 *
 * @property Partner $partnerUu
 */
class PartnerToken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'partner_token';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['token_uuid', 'partner_uuid', 'token_value', 'token_status'], 'required'],
            [['token_status'], 'integer'],
            [['token_last_used_datetime', 'token_expiry_datetime', 'token_created_datetime'], 'safe'],
            [['token_uuid', 'partner_uuid'], 'string', 'max' => 60],
            [['token_value', 'token_device', 'token_device_id'], 'string', 'max' => 255],
            [['token_uuid'], 'unique'],
            [['partner_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Partner::className(), 'targetAttribute' => ['partner_uuid' => 'partner_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'token_uuid' => Yii::t('app','Token Uuid'),
            'partner_uuid' => Yii::t('app','Partner Uuid'),
            'token_value' => Yii::t('app','Token Value'),
            'token_device' => Yii::t('app','Token Device'),
            'token_device_id' => Yii::t('app','Token Device ID'),
            'token_status' => Yii::t('app','Token Status'),
            'token_last_used_datetime' => Yii::t('app','Token Last Used Datetime'),
            'token_expiry_datetime' => Yii::t('app','Token Expiry Datetime'),
            'token_created_datetime' => Yii::t('app','Token Created Datetime')
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
     * Gets query for [[PartnerUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPartnerUu()
    {
        return $this->hasOne(Partner::className(), ['partner_uuid' => 'partner_uuid']);
    }
}
