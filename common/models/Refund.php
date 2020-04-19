<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "refund".
 *
 * @property int $refund_id
 * @property string $restaurant_uuid
 * @property string $order_uuid
 * @property float $refund_amount
 * @property string $reason
 * @property string $refund_status
 *
 * @property Order $order
 * @property Payment $payment
 * @property Restaurant $restaurant
 */
class Refund extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'refund';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['restaurant_uuid', 'order_uuid', 'refund_amount', 'reason'], 'required'],
            [['refund_amount'], 'number'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['order_uuid'], 'string', 'max' => 40],
            [['reason', 'refund_status'], 'string', 'max' => 255],
            [['order_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_uuid' => 'order_uuid']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'refund_id' => 'Refund ID',
            'restaurant_uuid' => 'Restaurant Uuid',
            'order_uuid' => 'Order Uuid',
            'refund_amount' => 'Refund Amount',
            'reason' => 'Reason',
            'refund_status' => 'Refund Status',
        ];
    }

    /**
     * Gets query for [[OrderUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrder() {
        return $this->hasOne(Order::className(), ['order_uuid' => 'order_uuid']);
    }

    /**
     * Gets query for [[Payment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayment() {
        return $this->hasOne(Payment::className(), ['payment_uuid' => 'payment_uuid'])->via('order');
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
