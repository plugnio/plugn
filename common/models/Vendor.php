<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "vendor".
 *
 * @property int $vendor_id
 * @property string|null $restaurant_uuid
 * @property string $vendor_name
 * @property string $vendor_email
 * @property string $vendor_auth_key
 * @property string $vendor_password_hash
 * @property string|null $vendor_password_reset_token
 * @property int $vendor_status
 * @property string $vendor_created_at
 * @property string $vendor_updated_at
 *
 * @property Restaurant $restaurant
 */
class Vendor extends \yii\db\ActiveRecord {

    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'vendor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['vendor_name', 'vendor_email'], 'required'],
            [['vendor_status'], 'integer'],
            [['vendor_created_at', 'vendor_updated_at'], 'safe'],
            [['restaurant_uuid'], 'string', 'max' => 36],
            [['vendor_name', 'vendor_email', 'vendor_password_hash', 'vendor_password_reset_token'], 'string', 'max' => 255],
            [['vendor_auth_key'], 'string', 'max' => 32],
            [['vendor_email'], 'unique'],
            [['vendor_password_reset_token'], 'unique'],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'vendor_id' => 'Vendor ID',
            'restaurant_uuid' => 'Restaurant Uuid',
            'vendor_name' => 'Vendor Name',
            'vendor_email' => 'Vendor Email',
            'vendor_auth_key' => 'Vendor Auth Key',
            'vendor_password_hash' => 'Vendor Password Hash',
            'vendor_password_reset_token' => 'Vendor Password Reset Token',
            'vendor_status' => 'Vendor Status',
            'vendor_created_at' => 'Vendor Created At',
            'vendor_updated_at' => 'Vendor Updated At',
        ];
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant() {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

}
