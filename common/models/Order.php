<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use common\models\Customer;
use common\models\Agent;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "order".
 *
 * @property string $order_uuid
 * @property string $payment_uuid
 * @property int $customer_id
 * @property string|null $restaurant_uuid
 * @property int $area_id
 * @property string $area_name
 * @property string $area_name_ar
 * @property string $unit_type
 * @property string $block
 * @property string $street
 * @property string $avenue
 * @property string $house_number
 * @property string $special_directions
 * @property string $customer_name
 * @property string $customer_phone_number
 * @property string $customer_email
 * @property int $payment_method_id
 * @property string $payment_method_name
 * @property string $payment_method_name_ar
 * @property int|null $order_status
 * @property int $order_mode
 * @property int $subtotal
 * @property int $total_price
 * @property int $items_has_been_restocked
 * @property int $latitude
 * @property int $longitude
 * @property boolean $is_order_scheduled
 * @property datetime $scheduled_time_start_from
 * @property datetime $scheduled_time_to
 * @property string $armada_tracking_link
 * @property string $armada_qr_code_link
* @property int|null $voucher_id
 * @property int $subtotal_before_refund
 * @property int $total_price_before_refund
 * @property int $restaurant_branch_id
 * @property datetime $order_created_at
 * @property datetime $order_updated_at
 *
 * @property Area $area
 * @property RestaurantBranch $restaurantBranch
 * @property Customer $customer
 * @property PaymentMethod $paymentMethod
 * @property Restaurant $restaurant
 * @property RestaurantDelivery $restaurantDelivery
 * @property Payment $payment
 * @property Voucher $voucher
 * @property Refund[] $refunds
 * @property RefundedItem[] $refundedItems
 * @property OrderItem[] $orderItems
 */
class Order extends \yii\db\ActiveRecord {

    const STATUS_DRAFT = 0;
    const STATUS_PENDING = 1;
    const STATUS_BEING_PREPARED = 2;
    const STATUS_OUT_FOR_DELIVERY = 3;
    const STATUS_COMPLETE = 4;
    const STATUS_CANCELED = 5;
    const STATUS_PARTIALLY_REFUNDED = 6;
    const STATUS_REFUNDED = 7;
    const STATUS_ABANDONED_CHECKOUT = 9;
    const ORDER_MODE_DELIVERY = 1;
    const ORDER_MODE_PICK_UP = 2;
    const SCENARIO_CREATE_ORDER_BY_ADMIN = 'manual';

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
            [['customer_name', 'customer_phone_number', 'order_mode', 'is_order_scheduled'], 'required'],
            [['payment_method_id'], 'required', 'except' => self::SCENARIO_CREATE_ORDER_BY_ADMIN],
            [['order_uuid'], 'string', 'max' => 40],
            [['order_uuid'], 'unique'],
            [['area_id', 'payment_method_id', 'order_status', 'customer_id'], 'integer', 'min' => 0],
            [['items_has_been_restocked', 'is_order_scheduled', 'voucher_id'], 'integer'],
            ['order_status', 'in', 'range' => [self::STATUS_PENDING, self::STATUS_BEING_PREPARED, self::STATUS_OUT_FOR_DELIVERY, self::STATUS_COMPLETE, self::STATUS_REFUNDED, self::STATUS_PARTIALLY_REFUNDED, self::STATUS_CANCELED, self::STATUS_DRAFT, self::STATUS_ABANDONED_CHECKOUT]],
            ['order_mode', 'in', 'range' => [self::ORDER_MODE_DELIVERY, self::ORDER_MODE_PICK_UP]],
            ['restaurant_branch_id', function ($attribute, $params, $validator) {
                    if (!$this->restaurant_branch_id && $this->order_mode == Order::ORDER_MODE_PICK_UP)
                        $this->addError($attribute, 'Branch name cannot be blank.');
                }, 'skipOnError' => false, 'skipOnEmpty' => false],
            [['scheduled_time_start_from', 'scheduled_time_to'], function ($attribute, $params, $validator) {
                    if ($this->is_order_scheduled && (!$this->scheduled_time_start_from || !$this->scheduled_time_to))
                        $this->addError($attribute, $attribute . ' cannot be blank.');
                }, 'skipOnError' => false, 'skipOnEmpty' => false],
            ['area_id', function ($attribute, $params, $validator) {
                    if (!$this->area_id && $this->order_mode == Order::ORDER_MODE_DELIVERY)
                        $this->addError($attribute, 'Area name cannot be blank.');
                }, 'skipOnError' => false, 'skipOnEmpty' => false],
            [['area_id'], 'validateArea'],
            ['unit_type', function ($attribute, $params, $validator) {
                    if (!$this->unit_type && $this->order_mode == Order::ORDER_MODE_DELIVERY)
                        $this->addError($attribute, 'Unit type cannot be blank.');
                }, 'skipOnError' => false, 'skipOnEmpty' => false],
            ['block', function ($attribute, $params, $validator) {
                    if (!$this->block && $this->order_mode == Order::ORDER_MODE_DELIVERY)
                        $this->addError($attribute, 'Block cannot be blank.');
                }, 'skipOnError' => false, 'skipOnEmpty' => false],
            ['street', function ($attribute, $params, $validator) {
                    if (!$this->street && $this->order_mode == Order::ORDER_MODE_DELIVERY)
                        $this->addError($attribute, 'Street cannot be blank.');
                }, 'skipOnError' => false, 'skipOnEmpty' => false],
            ['house_number', function ($attribute, $params, $validator) {
                    if (!$this->house_number && $this->order_mode == Order::ORDER_MODE_DELIVERY)
                        $this->addError($attribute, 'House number cannot be blank.');
                }, 'skipOnError' => false, 'skipOnEmpty' => false],
            ['order_mode', 'validateOrderMode'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['customer_phone_number'], 'string', 'min' => 8, 'max' => 8],
            [['customer_phone_number'], 'number'],
            [['total_price', 'total_price_before_refund', 'delivery_fee', 'subtotal', 'subtotal_before_refund'], 'number', 'min' => 0],
            ['subtotal', 'validateMinCharge', 'except' => self::SCENARIO_CREATE_ORDER_BY_ADMIN, 'when' => function($model) {
                    return $model->order_mode == static::ORDER_MODE_DELIVERY;
                }],
            [['customer_email'], 'email'],
            [['payment_method_id'], 'validatePaymentMethodId', 'except' => self::SCENARIO_CREATE_ORDER_BY_ADMIN],
            [['voucher_id'], 'validateVoucherId', 'except' => self::SCENARIO_CREATE_ORDER_BY_ADMIN],
            [['payment_uuid'], 'string', 'max' => 36],
            [['estimated_time_of_arrival', 'scheduled_time_start_from', 'scheduled_time_to', 'latitude', 'longitude'], 'safe'],
            [['payment_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Payment::className(), 'targetAttribute' => ['payment_uuid' => 'payment_uuid']],
            [['area_name', 'area_name_ar', 'unit_type', 'block', 'street', 'avenue', 'house_number', 'special_directions', 'customer_name', 'customer_email', 'payment_method_name', 'payment_method_name_ar', 'armada_tracking_link', 'armada_qr_code_link', 'armada_delivery_code'], 'string', 'max' => 255],
            [['area_id'], 'exist', 'skipOnError' => false, 'targetClass' => Area::className(), 'targetAttribute' => ['area_id' => 'area_id']],
            [['customer_id'], 'exist', 'skipOnError' => false, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
            [['payment_method_id'], 'exist', 'skipOnError' => false, 'targetClass' => PaymentMethod::className(), 'targetAttribute' => ['payment_method_id' => 'payment_method_id']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => false, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
            [['restaurant_branch_id'], 'exist', 'skipOnError' => false, 'targetClass' => RestaurantBranch::className(), 'targetAttribute' => ['restaurant_branch_id' => 'restaurant_branch_id']],
            [['voucher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Voucher::className(), 'targetAttribute' => ['voucher_id' => 'voucher_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'order_uuid',
                ],
                'value' => function() {
                    if (!$this->order_uuid) {
                        // Get a unique uuid from payment table
                        $this->order_uuid = strtoupper(Order::getUniqueOrderUuid());
                    }

                    return $this->order_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'order_created_at',
                'updatedAtAttribute' => 'order_updated_at',
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * Get a unique alphanumeric uuid to be used for a payment
     * @return string uuid
     */
    private static function getUniqueOrderUuid($length = 6) {
        $uuid = \ShortCode\Random::get($length);

        $isNotUnique = static::find()->where(['order_uuid' => $uuid])->exists();

        // If not unique, try again recursively
        if ($isNotUnique) {
            return static::getUniqueOrderUuid($length);
        }

        return $uuid;
    }

    /**
     * Check if the selected payment method id is exist in restaurant_payment_method
     * @param type $attribute
     */
    public function validatePaymentMethodId($attribute) {
        if (!RestaurantPaymentMethod::find()->where(['restaurant_uuid' => $this->restaurant_uuid, 'payment_method_id' => $this->payment_method_id])->one())
            $this->addError($attribute, "Payment method id invalid.");
    }

    /**
     * Validate promo code
     * @param type $attribute
     */
    public function validateVoucherId($attribute) {

        $voucher = Voucher::find()->where(['restaurant_uuid' => $this->restaurant_uuid, 'voucher_id' => $this->voucher_id, 'voucher_status' => Voucher::VOUCHER_STATUS_ACTIVE])->one();

        if (!$voucher || !$response = $voucher->isValid($this->customer_phone_number))
            $this->addError($attribute, "Voucher code is invalid or expired");
    }

    /**
     * Check if the selected area delivery by the restaurant or no
     * @param type $attribute
     */
    public function validateArea($attribute) {
        if (!RestaurantDelivery::find()->where(['restaurant_uuid' => $this->restaurant_uuid, 'area_id' => $this->area_id])->one())
            $this->addError($attribute, "Store does not deliver to this Area.");
    }

    /**
     * Validate order mode attribute
     * @param type $attribute
     */
    public function validateOrderMode($attribute) {
        if ($this->$attribute == static::ORDER_MODE_DELIVERY && !$this->restaurant->support_delivery)
            $this->addError($attribute, "Store doesn't accept delviery");

        else if ($this->$attribute == static::ORDER_MODE_PICK_UP && !$this->restaurant->support_pick_up)
            $this->addError($attribute, "Store doesn't accept pick up");
    }

    /**
     * Validates min charge
     * This method serves as the inline validation for min_charge.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validateMinCharge($attribute) {
        if ($this->restaurantDelivery->min_charge > $this->$attribute)
            $this->addError($attribute, "Minimum Order Amount: " . \Yii::$app->formatter->asCurrency($this->restaurantDelivery->min_charge));
    }

    /**
     * @inheritdoc
     */
    public function extraFields() {
        return [
            'orderStatus'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'order_uuid' => 'Order UUID',
            'payment_uuid' => 'Payment Uuid',
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
            'customer_phone_number' => 'Phone Number',
            'customer_email' => 'Customer Email',
            'payment_method_id' => 'Payment Method ID',
            'payment_method_name' => 'Payment Method Name',
            'payment_method_name_ar' => 'Payment Method Name [Arabic]',
            'order_status' => 'Status',
            'total_price' => 'Price',
            'total_price_before_refund' => 'Total price before refund',
            'subtotal' => 'Subtotal',
            'order_created_at' => 'Created At',
            'order_updated_at' => 'Updated At',
            'armada_tracking_link' => 'Tracking link',
            'armada_delivery_code' => 'Armada Delivery Code',
            'armada_qr_code_link' => 'QR Code link',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'estimated_time_of_arrival' => 'Expected at',
            'is_order_scheduled' => 'Is order scheduled',
            'voucher_id' => 'Voucher ID',
        ];
    }

    public function sendPaymentConfirmationEmail() {


        if ($this->customer_email) {

            \Yii::$app->mailer->compose([
                        'html' => 'payment-confirm-html',
                            ], [
                        'order' => $this
                    ])
                    ->setFrom([\Yii::$app->params['supportEmail'] => $this->restaurant->name])
                    ->setTo($this->customer_email)
                    ->setSubject('Order #' . $this->order_uuid . ' from ' . $this->restaurant->name)
                    ->setReplyTo([$this->restaurant->restaurant_email => $this->restaurant->name])
                    ->send();
        }

        foreach ($this->restaurant->getAgents()->all() as $agent) {


            if ($agent->email_notification) {

                \Yii::$app->mailer->compose([
                            'html' => 'payment-confirm-html',
                                ], [
                            'order' => $this
                        ])
                        ->setFrom([\Yii::$app->params['supportEmail'] => $this->restaurant->name])
                        ->setTo($agent->agent_email)
                        ->setSubject('Order #' . $this->order_uuid . ' from ' . $this->restaurant->name)
                        ->setReplyTo([$this->restaurant->restaurant_email => $this->restaurant->name])
                        ->send();
            }
        }


        if ($this->restaurant->restaurant_email_notification) {

            \Yii::$app->mailer->compose([
                        'html' => 'payment-confirm-html',
                            ], [
                        'order' => $this
                    ])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                    ->setTo($this->restaurant->restaurant_email)
                    ->setSubject('Order #' . $this->order_uuid . ' from ' . $this->restaurant->name)
                    ->setReplyTo([$this->restaurant->restaurant_email => $this->restaurant->name])
                    ->send();
        }
    }

    /**
     * Update order status to pending
     */
    public function restockAllItems() {

        $orderItems = $this->getOrderItems();
        // die($orderItems->count());

        if ($orderItems->count() > 0) {
            foreach ($orderItems->all() as $orderItem)
                if ($orderItem->item_uuid) {



                    $orderItem->item->increaseStockQty($orderItem->qty);
                    $this->items_has_been_restocked = true;
                    $this->save(false);
                }
        }
    }

    /**
     * Update order status to pending
     */
    public function changeOrderStatusToPending() {
        $this->order_status = self::STATUS_PENDING;
        $this->save(false);
    }

    /**
     * Update order total price and items total price
     */
    public function updateOrderTotalPrice() {
        if ($this->order_mode == static::ORDER_MODE_DELIVERY)
            $this->delivery_fee = $this->restaurantDelivery->delivery_fee;


        if ($this->order_status != Order::STATUS_REFUNDED && $this->order_status != Order::STATUS_PARTIALLY_REFUNDED) {
            $this->subtotal_before_refund = $this->calculateOrderItemsTotalPrice();
            $this->total_price_before_refund = $this->calculateOrderTotalPrice();
        }


        $this->subtotal = $this->calculateOrderItemsTotalPrice();
        $this->total_price = $this->calculateOrderTotalPrice();
        $this->save(false);
    }

    /**
     * @return string text explaining Order Status
     */
    public function getOrderStatus() {
        if ($this->order_status == self::STATUS_PENDING)
            return 'Pending';
        else if ($this->order_status == self::STATUS_BEING_PREPARED)
            return 'Being Prepared';
        else if ($this->order_status == self::STATUS_OUT_FOR_DELIVERY)
            return 'Out for Delivery';
        else if ($this->order_status == self::STATUS_COMPLETE)
            return 'Complete';
        else if ($this->order_status == self::STATUS_CANCELED)
            return 'Canceled';
        else if ($this->order_status == self::STATUS_REFUNDED)
            return 'Refunded';
        else if ($this->order_status == self::STATUS_PARTIALLY_REFUNDED)
            return 'Partially refunded';
        else if ($this->order_status == self::STATUS_ABANDONED_CHECKOUT)
            return 'Abandoned checkouts';
        else if ($this->order_status == self::STATUS_DRAFT)
            return 'Draft';
    }

    /**
     * Calculate order item's total price
     */
    public function calculateOrderItemsTotalPrice() {
        $totalPrice = 0;

        foreach ($this->getOrderItems()->all() as $item) {
            if ($item) {
                $totalPrice += $item->calculateOrderItemPrice();
            }
        }

        return $totalPrice;
    }

    /**
     * Calculate order's total price
     */
    public function calculateOrderTotalPrice() {
        $totalPrice = $this->calculateOrderItemsTotalPrice();

        if($this->voucher){
          $discountAmount = $this->voucher->discount_type == Voucher::DISCOUNT_TYPE_PERCENTAGE ? ($totalPrice * ($this->voucher->discount_amount /100)) : $this->voucher->discount_amount;
          $totalPrice -= $discountAmount ;
        }


        if ($this->order_mode == static::ORDER_MODE_DELIVERY)
            $totalPrice += $this->restaurantDelivery->delivery_fee;


        return $totalPrice;
    }

    public function beforeDelete() {

        $orderItems = OrderItem::find()->where(['order_uuid' => $this->order_uuid])->all();

        foreach ($orderItems as $model) {
            $model->delete();
        }

        return parent::beforeDelete();
    }

    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert && $this->scenario == self::SCENARIO_CREATE_ORDER_BY_ADMIN)
            $this->order_status = self::STATUS_DRAFT;



        return true;
    }

    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        if (!$insert && $this->payment && $this->items_has_been_restocked && isset($changedAttributes['order_status']) && $changedAttributes['order_status'] == self::STATUS_ABANDONED_CHECKOUT) {

            $orderItems = $this->getOrderItems();

            foreach ($orderItems->all() as $orderItem) {

                if ($orderItem->item_uuid) {

                    if ($orderItem->item->stock_qty >= $orderItem->qty) {
                        $orderItem->item->decreaseStockQty($orderItem->qty);
                    } else {

                        \Yii::$app->mailer->compose([
                                    'html' => 'out-of-stock-order-html',
                                        ], [
                                    'order' => $this
                                ])
                                ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                                ->setTo([$this->restaurant->restaurant_email, \Yii::$app->params['supportEmail']])
                                ->setSubject('Order #' . $this->order_uuid)
                                ->send();
                    }
                }
            }


            $this->items_has_been_restocked = false;
            $this->save(false);
        }

        if ($insert) {

            if ($this->order_mode == static::ORDER_MODE_DELIVERY) {
                //set ETA value
                \Yii::$app->timeZone = 'Asia/Kuwait';

                if ($this->is_order_scheduled)
                    $this->estimated_time_of_arrival = date("Y-m-d H:i:s", strtotime($this->scheduled_time_start_from));
                else
                    $this->estimated_time_of_arrival = date("Y-m-d H:i:s", strtotime('+' . $this->restaurantDelivery->delivery_time . ' minutes', Yii::$app->formatter->asTimestamp(date('Y-m-d H:i:s'))));




                $this->delivery_time = $this->restaurantDelivery->delivery_time;
                $this->save(false);
            } else {
                //set ETA value
                \Yii::$app->timeZone = 'Asia/Kuwait';
                $this->estimated_time_of_arrival = date("Y-m-d H:i:s", strtotime('+' . $this->restaurantBranch->prep_time . ' minutes', Yii::$app->formatter->asTimestamp(date('Y-m-d H:i:s'))));

                $this->delivery_time = $this->restaurantBranch->prep_time;
            }


            //Save Customer data
            $customer_model = Customer::find()->where(['customer_phone_number' => $this->customer_phone_number, 'restaurant_uuid' => $this->restaurant_uuid])->one();

            if (!$customer_model) {//new customer
                $customer_model = new Customer();
                $customer_model->restaurant_uuid = $this->restaurant_uuid;
                $customer_model->customer_name = $this->customer_name;
                $customer_model->customer_phone_number = $this->customer_phone_number;
                if ($this->customer_email != null)
                    $customer_model->customer_email = $this->customer_email;

                $customer_model->save(false);
            } else {
                //Make sure customer name & email are correct
                $this->customer_name = $customer_model->customer_name;
                $this->customer_phone_number = $customer_model->customer_phone_number;
                if ($customer_model->customer_email != null)
                    $this->customer_email = $customer_model->customer_email;
            }

            $this->customer_id = $customer_model->customer_id;

            if($this->voucher_id){

                $voucher_model = Voucher::findOne($this->voucher_id);

                if($voucher_model->isValid($this->customer_phone_number)){
                  $customerVoucher = new CustomerVoucher();
                  $customerVoucher->customer_id = $this->customer_id;
                  $customerVoucher->voucher_id = $this->voucher_id;
                  $customerVoucher->save();
                }
            }


            if ($this->order_mode == static::ORDER_MODE_DELIVERY) {
                $area_model = Area::findOne($this->area_id);
                $this->area_name = $area_model->area_name;
                $this->area_name_ar = $area_model->area_name_ar;
            }

            $payment_method_model = PaymentMethod::findOne($this->payment_method_id);

            if ($payment_method_model) {
                $this->payment_method_name = $payment_method_model->payment_method_name;
                $this->payment_method_name_ar = $payment_method_model->payment_method_name_ar;
            }

            $this->save(false);
        }
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
     * Gets query for [[RestaurantDelivery]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantDelivery() {
        return $this->hasOne(RestaurantDelivery::className(), ['area_id' => 'area_id'])->via('area')->andWhere(['restaurant_uuid' => $this->restaurant_uuid]);
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
     * Gets query for [[OrderItemExtraOption]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItemExtraOptions() {
        return $this->hasMany(OrderItemExtraOption::className(), ['order_item_id' => 'order_item_id'])->via('orderItems');
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems() {
        return $this->hasMany(OrderItem::className(), ['order_uuid' => 'order_uuid'])->with('item', 'orderItemExtraOptions');
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer() {
        return $this->hasOne(Customer::className(), ['customer_id' => 'customer_id']);
    }

    /**
     * Gets query for [[RestaurantBranch]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantBranch() {
        return $this->hasOne(RestaurantBranch::className(), ['restaurant_branch_id' => 'restaurant_branch_id']);
    }

    /**
     * Gets query for [[PaymentUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayment() {
        return $this->hasOne(Payment::className(), ['payment_uuid' => 'payment_uuid']);
    }

    /**
     * Gets query for [[Refunds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefunds() {
        return $this->hasMany(Refund::className(), ['order_uuid' => 'order_uuid']);
    }

    /**
     * Gets query for [[Voucher]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVoucher()
    {
        return $this->hasOne(Voucher::className(), ['voucher_id' => 'voucher_id']);
    }

    /**
     * Gets query for [[RefundedItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefundedItems() {
        return $this->hasMany(RefundedItem::className(), ['order_uuid' => 'order_uuid']);
    }

}
