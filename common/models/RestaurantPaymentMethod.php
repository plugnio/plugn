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
class RestaurantPaymentMethod extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'restaurant_payment_method';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['restaurant_uuid', 'payment_method_id'], 'required'],
            [['payment_method_id'], 'integer'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['restaurant_uuid', 'payment_method_id'], 'unique', 'targetAttribute' => ['restaurant_uuid', 'payment_method_id']],
            [['payment_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentMethod::className(), 'targetAttribute' => ['payment_method_id' => 'payment_method_id']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'restaurant_uuid' => 'Restaurant Uuid',
            'payment_method_id' => 'Payment Method ID',
        ];
    }

    /**
     * save restaurant payment method
     */
    public function saveRestaurantPaymentMethod($payments_method) {

        $sotred_restaurant_payment_method = RestaurantPaymentMethod::find()
                ->where(['restaurant_uuid' => $this->restaurant_uuid])
                ->all();

        foreach ($sotred_restaurant_payment_method as $restaurant_payment_method) {
            if (!in_array($restaurant_payment_method->payment_method_id, $payments_method)) {
                RestaurantPaymentMethod::deleteAll(['restaurant_uuid' => $this->restaurant_uuid, 'payment_method_id' => $restaurant_payment_method->payment_method_id]);
            }
        }

        foreach ($payments_method as $payment_method_id) {
            $payments_method = new RestaurantPaymentMethod();
            $payments_method->payment_method_id = $payment_method_id;
            $payments_method->restaurant_uuid = $this->restaurant_uuid;
            $payments_method->save();
        }
    }

    /**
     * Gets query for [[PaymentMethod]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod() {
        return $this->hasOne(PaymentMethod::className(), ['payment_method_id' => 'payment_method_id']);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantUu() {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

}
