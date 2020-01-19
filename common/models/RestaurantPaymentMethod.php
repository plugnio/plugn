<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "restaurant_payment_method".
 *
 * @property string $restaurant_uuid
 * @property int $payment_method_id
 *
 * @property PaymentMethod $paymentMethod
 * @property Restaurant $restaurantUu
 */
class RestaurantPaymentMethod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurant_payment_method';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid', 'payment_method_id'], 'required'],
            [['payment_method_id'], 'integer'],
            [['restaurant_uuid'], 'string', 'max' => 36],
            [['restaurant_uuid', 'payment_method_id'], 'unique', 'targetAttribute' => ['restaurant_uuid', 'payment_method_id']],
            [['payment_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentMethod::className(), 'targetAttribute' => ['payment_method_id' => 'payment_method_id']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'restaurant_uuid' => 'Restaurant Uuid',
            'payment_method_id' => 'Payment Method ID',
        ];
    }

    /**
     * Gets query for [[PaymentMethod]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod()
    {
        return $this->hasOne(PaymentMethod::className(), ['payment_method_id' => 'payment_method_id']);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantUu()
    {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
}
