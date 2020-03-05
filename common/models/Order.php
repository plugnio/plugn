<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "order".
 *
 * @property int $order_id
 * @property int $customer_id
 * @property string|null $restaurant_uuid 
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
 * @property string $payment_method_name
 * @property int|null $order_status
 * @property int $order_mode
 * @property datetime $order_created_at
 *
 * @property Area $area
 * @property Customer $customer 
 * @property PaymentMethod $paymentMethod
 * @property Restaurant $restaurant
 * @property OrderItem[] $orderItems
 */
class Order extends \yii\db\ActiveRecord {

    const STATUS_SUBMITTED = 1;
    const STATUS_BEING_PREPARED = 2;
    const STATUS_OUT_FOR_DELIVERY = 3;
    const STATUS_COMPLETE = 4;
    const ORDER_MODE_DELIVERY = 1;
    const ORDER_MODE_PICK_UP = 2;

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
            [['area_id', 'customer_id', 'area_name', 'area_name_ar', 'unit_type', 'block', 'street', 'house_number', 'customer_name', 'customer_phone_number', 'payment_method_id', 'payment_method_name', 'order_mode'], 'required'],
            [['area_id', 'payment_method_id', 'order_status', 'customer_id'], 'integer'],
            ['order_status', 'in', 'range' => [self::STATUS_SUBMITTED, self::STATUS_BEING_PREPARED, self::STATUS_OUT_FOR_DELIVERY, self::STATUS_COMPLETE]],
            ['order_mode', 'in', 'range' => [self::ORDER_MODE_DELIVERY, self::ORDER_MODE_PICK_UP]],
            ['order_mode', 'validateOrderMode'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['customer_phone_number'], 'number'],
            [['customer_phone_number'], 'unique'],
            [['customer_email'], 'unique'],
            [['area_name', 'area_name_ar', 'unit_type', 'block', 'street', 'avenue', 'house_number', 'special_directions', 'customer_name', 'customer_email', 'payment_method_name'], 'string', 'max' => 255],
            [['area_id'], 'exist', 'skipOnError' => true, 'targetClass' => Area::className(), 'targetAttribute' => ['area_id' => 'area_id']],
            [['customer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
            [['payment_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentMethod::className(), 'targetAttribute' => ['payment_method_id' => 'payment_method_id']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'order_created_at',
                'updatedAtAttribute' => null,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * Validate order mode attribute
     * @param type $attribute
     */
    public function validateOrderMode($attribute) {
        if ($this->$attribute == static::ORDER_MODE_DELIVERY && !$this->restaurant->support_delivery)
            $this->addError($attribute, "Restaurant doesn't accept delviery");

        else if ($this->$attribute == static::ORDER_MODE_PICK_UP && !$this->restaurant->support_pick_up)
            $this->addError($attribute, "Restaurant doesn't accept pick up");
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'order_id' => 'Order ID',
            'restaurant_uuid' => 'Restaurant Uuid',
            'area_id' => 'Area ID',
            'area_name' => 'Area Name',
            'area_name_ar' => 'Area Name Ar',
            'unit_type' => 'Unit Type',
            'block' => 'Block',
            'street' => 'Street',
            'customer_id' => 'Customer ID',
            'avenue' => 'Avenue',
            'house_number' => 'House Number',
            'special_directions' => 'Special Directions',
            'customer_name' => 'Customer Name',
            'customer_phone_number' => 'Customer Phone Number',
            'customer_email' => 'Customer Email',
            'payment_method_id' => 'Payment Method ID',
            'payment_method_name' => 'Payment Method Name',
            'order_status' => 'Order Status',
            'order_created_at' => 'Order Created At',
        ];
    }

    /**
     * @return string text explaining Order Status
     */
    public function getOrderStatus() {
        if ($this->order_status == self::STATUS_SUBMITTED)
            return 'Order Submitted';
        else if ($this->order_status == self::STATUS_BEING_PREPARED)
            return 'Order Being Prepared';
        else if ($this->order_status == self::STATUS_OUT_FOR_DELIVERY)
            return 'Out for Delivery';
        else if ($this->order_status == self::STATUS_COMPLETE)
            return 'Complete';
    }

    /**
     * Gets query for [[Restaurant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant() {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
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
        return $this->hasMany(OrderItemExtraOptions::className(), ['order_item_id' => 'order_item_id']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems() {
        return $this->hasMany(OrderItem::className(), ['order_id' => 'order_id']);
    }

    /**
     * Gets query for [[Customer]]. 
     * 
     * @return \yii\db\ActiveQuery 
     */
    public function getCustomer() {
        return $this->hasOne(Customer::className(), ['customer_id' => 'customer_id']);
    }

}
