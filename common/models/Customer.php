<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
 * This is the model class for table "customer".
 *
 * @property int $customer_id
 * @property int $restaurant_uuid
 * @property string $customer_name
 * @property string $customer_phone_number
 * @property string|null $customer_email
 * @property string $customer_created_at
 * @property string $customer_updated_at 
 *
 * @property Order[] $orders
 * @property Restaurant $restaurant
 */
class Customer extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'customer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['customer_name', 'customer_phone_number','restaurant_uuid'], 'required'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['customer_phone_number'], 'unique'],
            [['customer_email'], 'unique'],
            [['customer_phone_number'], 'string', 'min' => 8, 'max' => 8],
            [['customer_created_at','customer_updated_at'], 'safe'],
            [['customer_name', 'customer_email'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'customer_created_at',
                'updatedAtAttribute' => 'customer_updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'customer_id' => 'Customer ID',
            'restaurant_uuid' => 'Restaurant UUID',
            'customer_name' => 'Customer Name',
            'customer_phone_number' => 'Customer Phone Number',
            'customer_email' => 'Customer Email',
            'customer_created_at' => 'Customer Created At',
            'customer_updated_at' => 'Customer Updated At',
        ];
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders() {
        return $this->hasMany(Order::className(), ['customer_id' => 'customer_id']);
    }
    
    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant() {
        return $this->hasMany(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

}
