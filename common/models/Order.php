<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $order_id
 * @property int $area_id
 * @property string $area_name
 * @property string $area_name_ar
 * @property string $unit_type
 * @property string $block
 * @property string $street
 * @property string|null $avenue
 * @property string $house_number
 * @property string|null $special_directions
 * @property string $customer_name
 * @property string $customer_phone_number
 * @property string|null $customer_email
 * @property int $payment_method_id
 * @property string $payment_method
 * @property int|null $order_status
 *
 * @property Area $area
 * @property PaymentMethod $paymentMethod
 * @property OrderItem[] $orderItems
 */
class Order extends \yii\db\ActiveRecord {

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['area_id', 'area_name', 'area_name_ar', 'unit_type', 'block', 'street', 'house_number', 'customer_name', 'customer_phone_number', 'payment_method_id', 'payment_method'], 'required'],
            [['area_id', 'payment_method_id', 'order_status'], 'integer'],
            [['area_name', 'area_name_ar', 'unit_type', 'block', 'street', 'avenue', 'house_number', 'special_directions', 'customer_name', 'customer_phone_number', 'customer_email', 'payment_method'], 'string', 'max' => 255],
            [['area_id'], 'exist', 'skipOnError' => true, 'targetClass' => Area::className(), 'targetAttribute' => ['area_id' => 'area_id']],
            [['payment_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentMethod::className(), 'targetAttribute' => ['payment_method_id' => 'payment_method_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'order_id' => 'Order ID',
            'area_id' => 'Area ID',
            'area_name' => 'Area Name',
            'area_name_ar' => 'Area Name Ar',
            'unit_type' => 'Unit Type',
            'block' => 'Block',
            'street' => 'Street',
            'avenue' => 'Avenue',
            'house_number' => 'House Number',
            'special_directions' => 'Special Directions',
            'customer_name' => 'Customer Name',
            'customer_phone_number' => 'Customer Phone Number',
            'customer_email' => 'Customer Email',
            'payment_method_id' => 'Payment Method ID',
            'payment_method' => 'Payment Method',
            'order_status' => 'Order Status',
        ];
    }

    /**
     * Gets query for [[Area]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArea() {
        return $this->hasOne(Area::className(), ['area_id' => 'area_id']);
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
     * Gets query for [[OrderItemExtraOptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItemExtraOptions() {
        return $this->hasMany(OrderItemExtraOptions::className(), ['order_item_id' => 'order_item_id'])->via('orderItems');
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems() {
        return $this->hasMany(OrderItem::className(), ['order_id' => 'order_id']);
    }

}
