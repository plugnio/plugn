<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "partner".
 *
 * @property string $partner_uuid
 * @property string $username
 * @property string $partner_auth_key
 * @property string $partner_password_hash
 * @property string|null $partner_password_reset_token
 * @property string $partner_email
 * @property int $partner_status
 * @property string $referral_code
 * @property float|null $commission
 * @property string|null $partner_created_at
 * @property string|null $partner_updated_at
 *
 * @property PartnerPayout[] $partnerPayouts
 * @property PartnerToken[] $partnerTokens
 * @property Restaurant[] $restaurants
 */
class Partner extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'partner';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['partner_uuid', 'username', 'partner_auth_key', 'partner_password_hash', 'partner_email', 'referral_code'], 'required'],
            [['partner_status'], 'integer'],
            [['commission'], 'number'],
            [['partner_created_at', 'partner_updated_at'], 'safe'],
            [['partner_uuid'], 'string', 'max' => 60],
            [['username', 'partner_password_hash', 'partner_password_reset_token', 'partner_email'], 'string', 'max' => 255],
            [['partner_auth_key'], 'string', 'max' => 32],
            [['referral_code'], 'string', 'max' => 6],
            [['username'], 'unique'],
            [['partner_email'], 'unique'],
            [['partner_password_reset_token'], 'unique'],
            [['partner_uuid', 'referral_code'], 'unique', 'targetAttribute' => ['partner_uuid', 'referral_code']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'partner_uuid' => 'Partner Uuid',
            'username' => 'Username',
            'partner_auth_key' => 'Partner Auth Key',
            'partner_password_hash' => 'Partner Password Hash',
            'partner_password_reset_token' => 'Partner Password Reset Token',
            'partner_email' => 'Partner Email',
            'partner_status' => 'Partner Status',
            'referral_code' => 'Referral Code',
            'commission' => 'Commission',
            'partner_created_at' => 'Partner Created At',
            'partner_updated_at' => 'Partner Updated At',
        ];
    }

    /**
     * Gets query for [[PartnerPayouts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPartnerPayouts()
    {
        return $this->hasMany(PartnerPayout::className(), ['partner_uuid' => 'partner_uuid']);
    }

    /**
     * Gets query for [[PartnerTokens]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPartnerTokens()
    {
        return $this->hasMany(PartnerToken::className(), ['partner_uuid' => 'partner_uuid']);
    }

    /**
     * Gets query for [[Restaurants]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurants()
    {
        return $this->hasMany(Restaurant::className(), ['referral_code' => 'referral_code']);
    }
}
