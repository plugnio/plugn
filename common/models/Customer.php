<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
 * This is the model class for table "customer".
 *
 * @property int $customer_id
 * @property string $customer_name
 * @property string $customer_phone_number
 * @property string|null $customer_email
 * @property string $customer_created_at
 * @property string $customer_updated_at 
 *
 * @property Order[] $orders
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
            [['customer_name', 'customer_phone_number'], 'required'],
            [['customer_created_at','customer_updated_at '], 'safe'],
            [['customer_name', 'customer_phone_number', 'customer_email'], 'string', 'max' => 255],
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

}
