<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "payment_method".
 *
 * @property int $payment_method_id
 * @property string|null $payment_method_name
 * @property string|null $payment_method_name_ar
 * @property string $payment_method_code
 * @property string|null $source_id
 *
 * @property RestaurantPaymentMethod[] $restaurantPaymentMethods
 * @property Restaurant[] $restaurantUus
 */
class PaymentMethod extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_method';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_method_name' , 'payment_method_name_ar', 'source_id','payment_method_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'payment_method_id' => 'Payment Method ID',
            'payment_method_name' => 'Payment Method Name',
            'payment_method_name_ar' => 'Payment Method Name [Arabic]',
        ];
    }

    /**
     * Gets query for [[RestaurantPaymentMethods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantPaymentMethods()
    {
        return $this->hasMany(RestaurantPaymentMethod::className(), ['payment_method_id' => 'payment_method_id']);
    }

    /**
     * Gets query for [[RestaurantUus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantUus()
    {
        return $this->hasMany(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid'])->viaTable('restaurant_payment_method', ['payment_method_id' => 'payment_method_id']);
    }
}
