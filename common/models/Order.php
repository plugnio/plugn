<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\AttributeBehavior;
use borales\extensions\phoneInput\PhoneInputValidator;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "order".
 *
 * @property string $order_uuid
 * @property string $payment_uuid
 * @property int $customer_id
 * @property string|null $restaurant_uuid
 * @property int $area_id
 * @property int|null $delivery_zone_id
 * @property int|null $shipping_country_id
 * @property string|null $country_name
 * @property string|null $country_name_ar
 * @property string|null $business_location_name
 * @property string|null $floor
 * @property string|null $apartment
 * @property string|null $office
 * @property string|null $postalcode
 * @property string|null $address_1
 * @property string|null $address_2
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
 * @property string $customer_phone_country_code
 * @property string $customer_email
 * @property int $payment_method_id
 * @property string $payment_method_name
 * @property string $payment_method_name_ar
 * @property int|null $order_status
 * @property int $order_mode
 * @property int $subtotal
 * @property int $tax
 * @property int $sms_sent
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
 * @property int $pickup_location_id
 * @property datetime $order_created_at
 * @property datetime $order_updated_at
 * @property int|null $bank_discount_id
 * @property string $mashkor_tracking_link
 * @property string $mashkor_driver_name
 * @property string $mashkor_driver_phone
 * @property string $mashkor_order_status
 * @property string $recipient_name
 * @property string $sender_name
 * @property string $recipient_phone_number
 * @property string $gift_message
 * @property boolean $reminder_sent
 * @property boolean estimated_time_of_arrival
 *
 * @property Area
 * @property BankDiscount $bankDiscount
 * @property Country $country
 * @property DeliveryZone $deliveryZone
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
class Order extends \yii\db\ActiveRecord
{
    //Values for `order_status`
    const STATUS_DRAFT = 0;
    const STATUS_PENDING = 1;
    const STATUS_BEING_PREPARED = 2;
    const STATUS_OUT_FOR_DELIVERY = 3;
    const STATUS_COMPLETE = 4;
    const STATUS_CANCELED = 5;
    const STATUS_PARTIALLY_REFUNDED = 6;
    const STATUS_REFUNDED = 7;
    const STATUS_ABANDONED_CHECKOUT = 9;
    const STATUS_ACCEPTED = 10;

    //Values for `order_mode`
    const ORDER_MODE_DELIVERY = 1;
    const ORDER_MODE_PICK_UP = 2;

    //Values for `mashkor_order_status`
    const MASHKOR_ORDER_STATUS_NEW = 0;
    const MASHKOR_ORDER_STATUS_CONFIRMED = 1;
    const MASHKOR_ORDER_STATUS_ASSIGNED = 2;
    const MASHKOR_ORDER_STATUS_PICKUP_STARTED = 3;
    const MASHKOR_ORDER_STATUS_PICKED_UP = 4;
    const MASHKOR_ORDER_STATUS_IN_DELIVERY = 5;
    const MASHKOR_ORDER_STATUS_ARRIVED_DESTINATION = 6;
    const MASHKOR_ORDER_STATUS_DELIVERED = 10;
    const MASHKOR_ORDER_STATUS_CANCELED = 11;

    const SCENARIO_CREATE_ORDER_BY_ADMIN = 'manual';
    const SCENARIO_OLD_VERSION = 'old_version';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['customer_name', 'order_mode'], 'required'],
            [['is_order_scheduled'], 'required', 'on' => 'create'],
            [['payment_method_id'], 'required', 'except' => self::SCENARIO_CREATE_ORDER_BY_ADMIN],
            [['order_uuid'], 'string', 'max' => 40],
            [['order_uuid'], 'unique'],
            [['area_id', 'payment_method_id', 'order_status', 'mashkor_order_status', 'customer_id'], 'integer', 'min' => 0],
            [['items_has_been_restocked', 'is_order_scheduled', 'voucher_id', 'reminder_sent', 'sms_sent', 'customer_phone_country_code', 'delivery_zone_id', 'shipping_country_id', 'pickup_location_id'], 'integer'],
            ['mashkor_order_status', 'in', 'range' => [
                self::MASHKOR_ORDER_STATUS_NEW,
                self::MASHKOR_ORDER_STATUS_CONFIRMED,
                self::MASHKOR_ORDER_STATUS_ASSIGNED,
                self::MASHKOR_ORDER_STATUS_PICKUP_STARTED,
                self::MASHKOR_ORDER_STATUS_PICKED_UP,
                self::MASHKOR_ORDER_STATUS_IN_DELIVERY,
                self::MASHKOR_ORDER_STATUS_ARRIVED_DESTINATION,
                self::MASHKOR_ORDER_STATUS_DELIVERED,
                self::MASHKOR_ORDER_STATUS_CANCELED,
            ]],

            [['customer_phone_number'], 'required', 'on' => self::SCENARIO_CREATE_ORDER_BY_ADMIN],
            [['customer_phone_number'], PhoneInputValidator::className (), 'message' => 'Please insert a valid phone number', 'except' => self::SCENARIO_OLD_VERSION],
            ['order_status', 'in', 'range' => [self::STATUS_PENDING, self::STATUS_BEING_PREPARED, self::STATUS_OUT_FOR_DELIVERY, self::STATUS_COMPLETE, self::STATUS_REFUNDED, self::STATUS_PARTIALLY_REFUNDED, self::STATUS_CANCELED, self::STATUS_DRAFT, self::STATUS_ABANDONED_CHECKOUT, self::STATUS_ACCEPTED]],

            ['order_mode', 'in', 'range' => [self::ORDER_MODE_DELIVERY, self::ORDER_MODE_PICK_UP]],
            ['pickup_location_id', function ($attribute, $params, $validator) {
                if (!$this->pickup_location_id && $this->order_mode == Order::ORDER_MODE_PICK_UP)
                    $this->addError ($attribute, 'Branch name cannot be blank.');
            }, 'skipOnError' => false, 'skipOnEmpty' => false],
            ['delivery_zone_id', function ($attribute, $params, $validator) {
                if (!$this->delivery_zone_id && $this->order_mode == Order::ORDER_MODE_DELIVERY)
                    $this->addError ($attribute, 'Delivery zone cannot be blank.');
            }, 'skipOnError' => false, 'skipOnEmpty' => false],
            [['scheduled_time_start_from', 'scheduled_time_to'], function ($attribute, $params, $validator) {
                if ($this->is_order_scheduled && (!$this->scheduled_time_start_from || !$this->scheduled_time_to))
                    $this->addError ($attribute, $attribute . ' cannot be blank.');
            }, 'skipOnError' => false, 'skipOnEmpty' => false],

            //TODO
            // ['area_id', function ($attribute, $params, $validator) {
            //         if ($this->order_mode == Order::ORDER_MODE_DELIVERY && $this->shipping_country_id && !$this->area_id)
            //             $this->addError($attribute, 'Area name cannot be blank.');
            //     }, 'skipOnError' => false, 'skipOnEmpty' => false],
            // ['shipping_country_id', function ($attribute, $params, $validator) {
            //         if ($this->order_mode == Order::ORDER_MODE_DELIVERY && $this->shipping_country_id && !$this->area_id)
            //             $this->addError($attribute, 'Area name cannot be blank.');
            //     }, 'skipOnError' => false, 'skipOnEmpty' => false],
            ['shipping_country_id', 'validateCountry', 'when' => function ($model) {
                return $model->order_mode == static::ORDER_MODE_DELIVERY;
            }
            ],
            [['area_id'], 'validateArea'],
            ['unit_type', function ($attribute, $params, $validator) {
                if ($this->area_id && !$this->unit_type && $this->order_mode == Order::ORDER_MODE_DELIVERY)
                    $this->addError ($attribute, 'Unit type cannot be blank.');
            }, 'skipOnError' => false, 'skipOnEmpty' => false],
            ['block', function ($attribute, $params, $validator) {
                if ($this->area_id && $this->block == null && $this->order_mode == Order::ORDER_MODE_DELIVERY)
                    $this->addError ($attribute, 'Block cannot be blank.');
            }, 'skipOnError' => false, 'skipOnEmpty' => false],
            ['street', function ($attribute, $params, $validator) {
                if ($this->area_id && $this->street == null && $this->order_mode == Order::ORDER_MODE_DELIVERY)
                    $this->addError ($attribute, 'Street cannot be blank.');
            }, 'skipOnError' => false, 'skipOnEmpty' => false],
            ['house_number', function ($attribute, $params, $validator) {
                if ($this->area_id && $this->house_number == null && $this->order_mode == Order::ORDER_MODE_DELIVERY)
                    $this->addError ($attribute, 'House number cannot be blank.');
            }, 'skipOnError' => false, 'skipOnEmpty' => false],
            ['order_mode', 'validateOrderMode', 'except' => self::SCENARIO_CREATE_ORDER_BY_ADMIN],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['customer_phone_number'], 'string', 'min' => 6, 'max' => 20],
            [['customer_phone_number'], 'number'],
            [['total_price', 'total_price_before_refund', 'delivery_fee', 'subtotal', 'subtotal_before_refund', 'tax'], 'number', 'min' => 0],
            ['subtotal', 'validateMinCharge', 'except' => self::SCENARIO_CREATE_ORDER_BY_ADMIN, 'when' => function ($model) {
                return $model->order_mode == static::ORDER_MODE_DELIVERY;
            }
            ],
            [['floor'], 'required', 'when' => function($model) {
                    return ($model->unit_type == 'Office' ||  $model->unit_type == 'Apartment') && ($model->restaurant->version == 2 || $model->restaurant->version == 3 || $model->restaurant->version == 4);
                }
            ],
            [['office'], 'required', 'when' => function($model) {
                    return $model->unit_type == 'Office' && ($model->restaurant->version == 2 || $model->restaurant->version == 3 || $model->restaurant->version == 4);
                }
            ],
            [['apartment'], 'required', 'when' => function($model) {
                    return $model->unit_type == 'Apartment' && ($model->restaurant->version == 2 || $model->restaurant->version == 3 || $model->restaurant->version == 4);
                }
            ],
            [['postalcode', 'city', 'address_1', 'address_2'], 'required', 'when' => function ($model) {
                return $model->shipping_country_id;
            }
            ],
            [
                'subtotal', function ($attribute, $params, $validator) {
                if ($this->voucher && $this->voucher->minimum_order_amount !== 0 && $this->calculateOrderItemsTotalPrice () >= $this->voucher->minimum_order_amount)
                    $this->addError ('voucher_id', "We can't apply this code until you reach the minimum order amount");
            }, 'skipOnError' => false, 'skipOnEmpty' => false
            ],
            [['customer_email'], 'email'],
            [['payment_method_id'], 'validatePaymentMethodId', 'except' => self::SCENARIO_CREATE_ORDER_BY_ADMIN],
            [['payment_method_id'], 'default', 'value' => 3, 'on' => self::SCENARIO_CREATE_ORDER_BY_ADMIN],
            [['voucher_id'], 'validateVoucherId', 'except' => self::SCENARIO_CREATE_ORDER_BY_ADMIN],
            [['payment_uuid'], 'string', 'max' => 36],
            [['estimated_time_of_arrival', 'scheduled_time_start_from', 'scheduled_time_to', 'latitude', 'longitude'], 'safe'],
            [['payment_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Payment::className (), 'targetAttribute' => ['payment_uuid' => 'payment_uuid']],

            [
              [
                 'area_name', 'area_name_ar', 'unit_type', 'block', 'street', 'avenue', 'house_number', 'special_directions',
                 'customer_name', 'customer_email',
                 'payment_method_name', 'payment_method_name_ar',
                 'armada_tracking_link', 'armada_qr_code_link', 'armada_delivery_code',
                 'country_name','country_name_ar', 'business_location_name',
                 'building', 'apartment', 'city',  'address_1' , 'address_2','postalcode', 'floor', 'office',
                 'recipient_name', 'recipient_phone_number', 'gift_message', 'sender_name','armada_order_status'
             ],
             'string', 'max' => 255],
             [['postalcode'], 'string', 'max' => 10],

            [['mashkor_order_number' , 'mashkor_tracking_link' ,'mashkor_driver_name','mashkor_driver_phone'], 'string', 'max' => 255],
            [['area_id'], 'exist', 'skipOnError' => false, 'targetClass' => Area::className(), 'targetAttribute' => ['area_id' => 'area_id']],
            [['bank_discount_id'], 'exist', 'skipOnError' => true, 'targetClass' => BankDiscount::className(), 'targetAttribute' => ['bank_discount_id' => 'bank_discount_id']],
            [['customer_id'], 'exist', 'skipOnError' => false, 'targetClass' => Customer::className(), 'targetAttribute' => ['customer_id' => 'customer_id']],
            [['payment_method_id'], 'exist', 'skipOnError' => false, 'targetClass' => PaymentMethod::className(), 'targetAttribute' => ['payment_method_id' => 'payment_method_id']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => false, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
            [['restaurant_branch_id'], 'exist', 'skipOnError' => false, 'targetClass' => RestaurantBranch::className(), 'targetAttribute' => ['restaurant_branch_id' => 'restaurant_branch_id']],
            [['pickup_location_id'], 'exist', 'skipOnError' => true, 'targetClass' => BusinessLocation::className(), 'targetAttribute' => ['pickup_location_id' => 'business_location_id']],
            [['voucher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Voucher::className(), 'targetAttribute' => ['voucher_id' => 'voucher_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className (),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'order_uuid',
                ],
                'value' => function () {
                    if (!$this->order_uuid) {
                        // Get a unique uuid from payment table
                        $this->order_uuid = strtoupper (Order::getUniqueOrderUuid ());
                    }

                    return $this->order_uuid;
                }
            ],
            [

                'class' => \borales\extensions\phoneInput\PhoneInputBehavior::className (),
                // 'attributes' => [
                //           ActiveRecord::EVENT_BEFORE_INSERT => ['customer_phone_number', 'customer_phone_country_code'],
                //       ],
                'countryCodeAttribute' => 'customer_phone_country_code',
                'phoneAttribute' => 'customer_phone_number',
            ],
            [
                'class' => TimestampBehavior::className (),
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
    private static function getUniqueOrderUuid($length = 6)
    {
        $uuid = \ShortCode\Random::get ($length);

        $isNotUnique = static::find ()->where (['order_uuid' => $uuid])->exists ();

        // If not unique, try again recursively
        if ($isNotUnique) {
            return static::getUniqueOrderUuid ($length);
        }

        return $uuid;
    }


    /**
     * @inheritdoc
     */
    // public function fields()
    // {
    //     $fields = parent::fields ();
    //
    //     // remove fields that contain sensitive information
    //     unset($fields['armada_delivery_code']);
    //     unset($fields['mashkor_order_number']);
    //     unset($fields['mashkor_tracking_link']);
    //     unset($fields['mashkor_driver_name']);
    //     unset($fields['mashkor_driver_phone']);
    //     unset($fields['mashkor_order_status']);
    //     unset($fields['armada_tracking_link']);
    //     unset($fields['reminder_sent']);
    //     unset($fields['sms_sent']);
    //     unset($fields['items_has_been_restocked']);
    //     unset($fields['subtotal_before_refund']);
    //     unset($fields['total_price_before_refund']);
    //
    //     return $fields;
    //
    // }

    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        return [
            'orderStatusInEnglish',
            'orderStatusInArabic',
            'restaurant',
            'orderItems' => function ($order) {
                return $order->getOrderItems ()->with ('orderItemExtraOptions')->asArray ()->all ();
            },
            'restaurantBranch',
            'deliveryZone',
            'pickupLocation',
            'payment',
            'currency'
        ];
    }

    /**
     * Returns String value of current status
     * @return string
     */
    public function getOrderStatusInEnglish()
    {
        switch ($this->order_status) {
            case self::STATUS_DRAFT:
                return "Draft";
                break;
            case self::STATUS_PENDING:
                return "Pending";
                break;
            case self::STATUS_BEING_PREPARED:
                return "Being Prepared";
                break;
            case self::STATUS_OUT_FOR_DELIVERY:
                return "Out for Delivery";
                break;
            case self::STATUS_COMPLETE:
                return "Complete";
                break;
            case self::STATUS_CANCELED:
                return "Canceled";
                break;
            case self::STATUS_PARTIALLY_REFUNDED:
                return "Partially Refunded";
                break;
            case self::STATUS_REFUNDED:
                return "Refunded";
                break;
            case self::STATUS_ACCEPTED:
                return "Accepted";
                break;
            case self::STATUS_ABANDONED_CHECKOUT:
                return "Abandoned";
                break;
        }
    }

    /**
     * Returns String value of current status
     * @return string
     */
    public function getorderStatusInArabic()
    {
        switch ($this->order_status) {
            case self::STATUS_PENDING:
                return "قيد الانتظار";
                break;
            case self::STATUS_BEING_PREPARED:
                return "يجري الاستعداد للطلب";
                break;
            case self::STATUS_OUT_FOR_DELIVERY:
                return "خارج للتوصيل";
                break;
            case self::STATUS_COMPLETE:
                return "تم الاستلام";
                break;
            case self::STATUS_CANCELED:
                return "تم إلغاء الطلب";
                break;
            case self::STATUS_PARTIALLY_REFUNDED:
                return "مسترد جزئيا";
                break;
            case self::STATUS_REFUNDED:
                return "مسترد";
                break;
            case self::STATUS_ACCEPTED:
                return "تم قبول الطلب";
                break;
        }
    }

    /**
     * Check if the selected payment method id is exist in restaurant_payment_method
     * @param type $attribute
     */
    public function validatePaymentMethodId($attribute)
    {
        if (!RestaurantPaymentMethod::find ()->where (['restaurant_uuid' => $this->restaurant_uuid, 'payment_method_id' => $this->payment_method_id])->one ())
            $this->addError ($attribute, "Payment method id invalid.");
    }

    /**
     * Validate promo code
     * @param type $attribute
     */
    public function validateVoucherId($attribute)
    {

        $voucher = Voucher::find ()->where (['restaurant_uuid' => $this->restaurant_uuid, 'voucher_id' => $this->voucher_id, 'voucher_status' => Voucher::VOUCHER_STATUS_ACTIVE])->exists ();

        if (!$voucher || !$this->voucher->isValid ($this->customer_phone_number))
            $this->addError ($attribute, "Voucher code is invalid or expired");
    }

    /**
     * Check if the selected area delivery by the restaurant or no
     * @param type $attribute
     */
    public function validateArea($attribute)
    {
        if (!AreaDeliveryZone::find ()->where (['restaurant_uuid' => $this->restaurant_uuid, 'area_id' => $this->area_id, 'delivery_zone_id' => $this->delivery_zone_id])->one ())
            $this->addError ($attribute, "Store does not deliver to this delivery zone.");
    }


    /**
     * Check if  store deliver to the selected country
     * @param type $attribute
     */
    public function validateCountry($attribute)
    {

        $areaDeliveryZone = AreaDeliveryZone::find ()->where (['country_id' => $this->shipping_country_id, 'delivery_zone_id' => $this->delivery_zone_id])->one ();

        if (!$areaDeliveryZone || $areaDeliveryZone->area_id != null || ($areaDeliveryZone && $areaDeliveryZone->businessLocation->restaurant_uuid != $this->restaurant_uuid))
            $this->addError ($attribute, "Store does not deliver to this area. ");
    }


    /**
     * Validate order mode attribute
     * @param type $attribute
     */
    public function validateOrderMode($attribute)
    {

        if ($this->$attribute == static::ORDER_MODE_DELIVERY && !$this->delivery_zone_id)
            $this->addError ($attribute, "Store doesn't accept delviery");

        else if ($this->$attribute == static::ORDER_MODE_PICK_UP && $this->pickup_location_id && !$this->pickupLocation->support_pick_up)
            $this->addError ($attribute, "Store doesn't accept pick up");
    }

    /**
     * Validates min charge
     * This method serves as the inline validation for min_charge.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validateMinCharge($attribute)
    {
        if (!$this->deliveryZone) {
            return $this->addError (
                $attribute,
                Yii::t('yii', "{attribute} is invalid.", [
                    'attribute' => Yii::t('app', 'Delivery Zone')
                ])
            );
        }

        if ($this->deliveryZone->min_charge > $this->$attribute) {
            $this->addError ($attribute, "Minimum Order Amount: " . \Yii::$app->formatter->asCurrency ($this->deliveryZone->min_charge, $this->currency->code));
        }
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'order_uuid' => 'Order UUID',
            'payment_uuid' => 'Payment Uuid',
            'restaurant_uuid' => 'Restaurant Uuid',
            'area_id' => 'Area ID',
            'shipping_country_id' => 'Country ID',
            'delivery_zone_id' => 'Delivery Zone ID',
            'country_name' => 'Country Name',
            'country_name_ar' => 'Country Name Ar',
            'business_location_name' => 'Branch',
            'floor' => 'Floor',
            'apartment' => 'Apartment',
            'office' => 'Office',
            'building' => 'Building',
            'postalcode' => 'Postal code',
            'address_1' => 'Address line 1',
            'address_2' => 'Address line 2',
            'area_name' => 'Area name',
            'area_name_ar' => 'Area name in Arabic',
            'unit_type' => 'Unit type',
            'block' => 'Block',
            'street' => 'Street',
            'customer_id' => 'Customer ID',
            'avenue' => 'Avenue',
            'house_number' => 'House number',
            'special_directions' => 'Special directions',
            'customer_name' => 'Customer name',
            'customer_phone_country_code' => 'Country Code',
            'customer_phone_number' => 'Phone number',
            'customer_email' => 'Customer email',
            'payment_method_id' => 'Payment method ID',
            'payment_method_name' => 'Payment method name',
            'payment_method_name_ar' => 'Payment method name [Arabic]',
            'order_status' => 'Status',
            'total_price' => 'Price',
            'total_price_before_refund' => 'Total price before refund',
            'subtotal' => 'Subtotal',
            'order_created_at' => 'Created at',
            'order_updated_at' => 'Updated at',
            'armada_tracking_link' => 'Tracking link',
            'armada_delivery_code' => 'Armada delivery code',
            'armada_qr_code_link' => 'QR code link',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'estimated_time_of_arrival' => 'Expected at',
            'is_order_scheduled' => 'Is order scheduled',
            'voucher_id' => 'Voucher ID',
            'tax' => 'Tax',
            'recipient_name' => 'Recipient name',
            'sender_name' => 'Sender name',
            'recipient_phone_number' => 'Recipient phone number',
            'gift_message' => 'Gift Message',
            'bank_discount_id' => 'Bank discount ID',
            'mashkor_order_number' => 'Mashkor order number',
            'mashkor_tracking_link' => 'Mashkor order tracking link',
            'mashkor_driver_name' => 'Name of the driver',
            'mashkor_driver_phone' => 'Driver phone number',
            'mashkor_order_status' => 'Mashkor order status'
        ];
    }

    public function sendPaymentConfirmationEmail()
    {

        $replyTo = [];
        if ($this->restaurant->restaurant_email) {
            $replyTo = [
                $this->restaurant->restaurant_email => $this->restaurant->name
            ];
        }


        if ($this->customer_email) {

            \Yii::$app->mailer->compose ([
                'html' => 'payment-confirm-html',
            ], [
                'order' => $this
            ])
                ->setFrom ([\Yii::$app->params['supportEmail'] => $this->restaurant->name])
                ->setTo ($this->customer_email)
                ->setSubject ('Order #' . $this->order_uuid . ' from ' . $this->restaurant->name)
                ->setReplyTo ($replyTo)
                ->send ();
        }

        foreach ($this->restaurant->getAgentAssignments ()->all () as $agentAssignment) {


            if ($agentAssignment->email_notification) {

                \Yii::$app->mailer->compose ([
                    'html' => 'payment-confirm-html',
                ], [
                    'order' => $this
                ])
                    ->setFrom ([\Yii::$app->params['supportEmail'] => $this->restaurant->name])
                    ->setTo ($agentAssignment->agent->agent_email)
                    ->setSubject ('Order #' . $this->order_uuid . ' from ' . $this->restaurant->name)
                    ->setReplyTo ($replyTo)
                    ->send ();
            }
        }


        if ($this->restaurant->restaurant_email_notification) {

            \Yii::$app->mailer->compose ([
                'html' => 'payment-confirm-html',
            ], [
                'order' => $this
            ])
                ->setFrom ([\Yii::$app->params['supportEmail'] => $this->restaurant->name])
                ->setTo ($this->restaurant->restaurant_email)
                ->setSubject ('Order #' . $this->order_uuid . ' from ' . $this->restaurant->name)
                ->setReplyTo ($replyTo)
                ->send ();
        }

    }

    /**
     * Update order status to pending
     */
    public function restockItems()
    {

        $orderItems = $this->getOrderItems ();
        $orderItemExtraOptions = $this->getOrderItemExtraOptions ();

        if ($orderItems->count () > 0) {
            foreach ($orderItems->all () as $orderItem)
                if ($orderItem->item_uuid) {

                    $orderItemExtraOptions = $orderItem->getOrderItemExtraOptions ();

                    if ($orderItemExtraOptions->count() > 0) {
                        foreach ($orderItemExtraOptions->all() as $orderItemExtraOption){
                          if ($orderItemExtraOption->order_item_extra_option_id && $orderItemExtraOption->order_item_extra_option_id && $orderItemExtraOption->extra_option_id)
                              $orderItemExtraOption->extraOption->increaseStockQty($orderItem->qty);
                        }
                    }


                    $orderItem->item->increaseStockQty ($orderItem->qty);
                    $this->items_has_been_restocked = true;
                    $this->save (false);
                }
        }


    }

    /**
     * Update order status to pending
     */
    public function changeOrderStatusToPending()
    {
        $this->order_status = self::STATUS_PENDING;
        $this->save (false);

        $productsList = null;

        foreach ($this->orderItems as $orderedItem) {
            $productsList[] = [
                'product_id' => $orderedItem->item_uuid,
                'sku' => $orderedItem->item->sku ? $orderedItem->item->sku : null,
                'name' => $orderedItem->item_name,
                'price' => $orderedItem->item_price,
                'quantity' => $orderedItem->qty,
                'url' => $this->restaurant->restaurant_domain . '/product/' . $orderedItem->item_uuid,
            ];
        }

        if(YII_ENV == 'prod') {

        $plugn_fee = 0;
        $payment_gateway_fee = 0;
        $total_price = $this->total_price;
        $delivery_fee = $this->delivery_fee;
        $subtotal = $this->subtotal;

        if($this->payment_uuid){
          if($this->currency->code == 'KWD'){
            $plugn_fee = ($this->payment->plugn_fee + $this->payment->partner_fee) * 3.28;
            $total_price = $total_price * 3.28;
            $delivery_fee = $delivery_fee * 3.28;
            $subtotal = $subtotal * 3.28;
            $payment_gateway_fee = $this->payment->payment_gateway_fee * 3.28;

          }
          else if($this->currency->code == 'SAR'){
            $plugn_fee = ($this->payment->plugn_fee + $this->payment->partner_fee) * 0.27;
            $total_price = $total_price *  0.27;
            $delivery_fee = $delivery_fee * 0.27;
            $subtotal = $subtotal * 0.27;
            $payment_gateway_fee = $this->payment->payment_gateway_fee * 0.27;
          }
          else if($this->currency->code == 'BHD'){
            $plugn_fee = ($this->payment->plugn_fee + $this->payment->partner_fee) * 2.65;
            $total_price = $total_price  * 2.65;
            $delivery_fee = $delivery_fee * 2.65;
            $subtotal = $subtotal * 2.65;
            $payment_gateway_fee = $this->payment->payment_gateway_fee * 2.65;

          }
        }



          \Segment::init('2b6WC3d2RevgNFJr9DGumGH5lDRhFOv5');
          \Segment::track([
              'userId' => $this->restaurant_uuid,

              'event' => 'Order Completed',
              'properties' => [
                  'checkout_id' => $this->order_uuid,
                  'order_id' => $this->order_uuid,
                  'total' => $total_price,
                  'revenue' => $plugn_fee,
                  'gateway_fee' => $payment_gateway_fee,
                  'payment_method' => $this->payment_method_name,
                  'gateway' => $this->payment_uuid ? $this->payment->payment_gateway_name : null,
                  'shipping' => $delivery_fee,
                  'subtotal' => $subtotal,
                  'currency' => 'USD',
                  'coupon' => $this->voucher && $this->voucher->code  ? $this->voucher->code : null,
                  'products' => $productsList ? $productsList : null
              ]
          ]);
        }


            $this->sendOrderNotification();
        }
    }

    /**
     * Update order total price and items total price
     */
    public function updateOrderTotalPrice()
    {
        if ($this->order_mode == static::ORDER_MODE_DELIVERY)
            $this->delivery_fee = $this->deliveryZone->delivery_fee;


        if ($this->order_status != Order::STATUS_REFUNDED && $this->order_status != Order::STATUS_PARTIALLY_REFUNDED) {
            $this->subtotal_before_refund = $this->calculateOrderItemsTotalPrice ();
            $this->total_price_before_refund = $this->calculateOrderTotalPrice ();
        }


        $this->subtotal = $this->calculateOrderItemsTotalPrice ();
        $this->total_price = $this->calculateOrderTotalPrice ();


        $this->save (false);
    }

    /**
     * Calculate order item's total price
     */
    public function calculateOrderItemsTotalPrice()
    {
        $totalPrice = 0;

        foreach ($this->getOrderItems ()->all () as $item) {

            if ($item) {
                $totalPrice += $item->calculateOrderItemPrice ();

            }
        }
        return $totalPrice;
    }

    /**
     * Calculate order's total price
     */
    public function calculateOrderTotalPrice()
    {
        $totalPrice = $this->calculateOrderItemsTotalPrice ();

        if ($totalPrice > 0) {
            if ($this->voucher) {
                $discountAmount = $this->voucher->discount_type == Voucher::DISCOUNT_TYPE_PERCENTAGE ? ($totalPrice * ($this->voucher->discount_amount / 100)) : $this->voucher->discount_amount;
                $totalPrice -= $discountAmount;

                $totalPrice = $totalPrice > 0 ? $totalPrice : 0;
            } else if ($this->bank_discount_id && $this->bankDiscount->minimum_order_amount <= $totalPrice) {
                $discountAmount = $this->bankDiscount->discount_type == BankDiscount::DISCOUNT_TYPE_PERCENTAGE ? ($totalPrice * ($this->bankDiscount->discount_amount / 100)) : $this->bankDiscount->discount_amount;
                $totalPrice -= $discountAmount;

                $totalPrice = $totalPrice > 0 ? $totalPrice : 0;

            }
        }

        if ($this->order_mode == static::ORDER_MODE_DELIVERY && (!$this->voucher || ($this->voucher && $this->voucher->discount_type !== Voucher::DISCOUNT_TYPE_FREE_DELIVERY))) {
            $totalPrice += $this->deliveryZone->delivery_fee;
        }

        if ($this->delivery_zone_id) {
            if ($this->deliveryZone->delivery_zone_tax) {
                $this->tax = $totalPrice * ($this->deliveryZone->delivery_zone_tax / 100);
                $totalPrice += $this->tax;
            } else if ($this->deliveryZone->businessLocation->business_location_tax) {
                $this->tax = $totalPrice * ($this->deliveryZone->businessLocation->business_location_tax / 100);
                $totalPrice += $this->tax;

            }

        } else if (!$this->delivery_zone_id && $this->pickup_location_id && $this->pickupLocation->business_location_tax) {
            $this->tax = $totalPrice * ($this->pickupLocation->business_location_tax / 100);
            $totalPrice += $this->tax;
        }

        return $totalPrice;
    }

    public function beforeDelete()
    {

        if (!$this->items_has_been_restocked) {
            $orderItems = OrderItem::find ()->where (['order_uuid' => $this->order_uuid])->all ();

            foreach ($orderItems as $model)
                $model->delete ();
        }

        return parent::beforeDelete ();
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave ($insert)) {
            return false;
        }

        if ($insert && $this->scenario == self::SCENARIO_CREATE_ORDER_BY_ADMIN) {
            $this->order_status = self::STATUS_DRAFT;
        }

        if ($this->scenario != self::SCENARIO_CREATE_ORDER_BY_ADMIN) {
            if ($this->order_mode == static::ORDER_MODE_DELIVERY) {
                //set ETA value
                \Yii::$app->timeZone = 'Asia/Kuwait';

                if ($this->is_order_scheduled)
                    $this->estimated_time_of_arrival = date ("Y-m-d H:i:s", strtotime ($this->scheduled_time_start_from));
                else {
                    if ($this->delivery_zone_id) {
                        $this->estimated_time_of_arrival =
                            date ("Y-m-d H:i:s", strtotime ('+' . $this->deliveryZone->delivery_time . ' ' . $this->deliveryZone->timeUnit, Yii::$app->formatter->asTimestamp (!$insert ? date ('Y-m-d H:i:s', strtotime ($this->order_created_at)) : date ('Y-m-d H:i:s'))));
                    }

                }

            } else {
                $this->estimated_time_of_arrival = !$insert ? date ('Y-m-d H:i:s', strtotime ($this->order_created_at)) : date ('Y-m-d H:i:s');
            }

            // if($this->orderItems){
            //   foreach ($this->orderItems as $key => $orderItem) {
            //
            //     if($orderItem->item_uuid && $orderItem->item->prep_time){
            //       $this->estimated_time_of_arrival = date("c", strtotime('+' . $orderItem->item->prep_time  . ' ' . $orderItem->item->timeUnit,  Yii::$app->formatter->asTimestamp(date('Y-m-d H:i:s', strtotime($this->estimated_time_of_arrival)))));
            //     }
            //   }
            // }


        if($this->restaurant->version == 4){
          if(!$this->is_order_scheduled && $this->orderItems){

              $maxPrepTime = 0;

              foreach ($this->orderItems as $key => $orderItem) {

                  if($orderItem->item_uuid && $orderItem->item->prep_time){

                      if($orderItem->item->prep_time_unit == Item::TIME_UNIT_MIN)
                        $prep_time  = intval($orderItem->item->prep_time) ;
                      else if($orderItem->item->prep_time_unit == Item::TIME_UNIT_HRS)
                        $prep_time =  intval($orderItem->item->prep_time) * 60;
                      else if($orderItem->item->prep_time_unit == Item::TIME_UNIT_DAY)
                        $prep_time =  intval($orderItem->item->prep_time) * 24 * 60;

                        if($prep_time  >=  $maxPrepTime)
                          $maxPrepTime = $prep_time;
                  }

              }


              $this->estimated_time_of_arrival = date("c", strtotime('+' . $maxPrepTime  . ' min' ,  Yii::$app->formatter->asTimestamp(date('Y-m-d H:i:s', strtotime($this->estimated_time_of_arrival)))));

          }
        } else {
          if( $this->orderItems ){

              $maxPrepTime = 0;

              foreach ($this->orderItems as $key => $orderItem) {

                  if($orderItem->item_uuid && $orderItem->item->prep_time){

                      if($orderItem->item->prep_time_unit == Item::TIME_UNIT_MIN)
                        $prep_time  = intval($orderItem->item->prep_time) ;
                      else if($orderItem->item->prep_time_unit == Item::TIME_UNIT_HRS)
                        $prep_time =  intval($orderItem->item->prep_time) * 60;
                      else if($orderItem->item->prep_time_unit == Item::TIME_UNIT_DAY)
                        $prep_time =  intval($orderItem->item->prep_time) * 24 * 60;

                        if($prep_time  >=  $maxPrepTime)
                          $maxPrepTime = $prep_time;
                  }

              }


              $this->estimated_time_of_arrival = date("c", strtotime('+' . $maxPrepTime  . ' min' ,  Yii::$app->formatter->asTimestamp(date('Y-m-d H:i:s', strtotime($this->estimated_time_of_arrival)))));

          }
        }


      }


        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave ($insert, $changedAttributes);

        //Send SMS To customer
        if ($this->customer_phone_country_code == 965 && !$insert && $this->restaurant_uuid != 'rest_7351b2ff-c73d-11ea-808a-0673128d0c9c' && $this->restaurant_uuid != 'rest_085f7a5f-19db-11eb-b97d-0673128d0c9c' && !$this->sms_sent && isset($changedAttributes['order_status']) && $changedAttributes['order_status'] == self::STATUS_PENDING && $this->order_status == self::STATUS_ACCEPTED) {

            try {

                $response = Yii::$app->smsComponent->sendSms ($this->customer_phone_number, $this->order_uuid);

                if (!$response->isOk)
                    Yii::error ('Error while Sending SMS' . json_encode ($response->data));
                else {
                    $this->sms_sent = 1;
                    $this->save (false);
                }

            if(!$response->isOk)
              Yii::error('Error while Sending SMS' . json_encode($response->data));
            else {
              $this->sms_sent = 1;
              $this->save(false);
            }
        } catch (\Exception $err) {
            Yii::error('Error while Sending SMS.' . json_encode($err));
          }


      }




      //Update delivery area
      if ((!$insert &&  $this->order_mode == static::ORDER_MODE_DELIVERY && isset($changedAttributes['area_id']) && $changedAttributes['area_id'] != $this->getOldAttribute('area_id')  && $this->area_id) || ($insert && $this->order_mode == static::ORDER_MODE_DELIVERY && $this->area_id) ) {
            $area_model = Area::findOne($this->area_id);
            $this->area_name = $area_model->area_name;
            $this->area_name_ar = $area_model->area_name_ar;
            $this->save(false);
      }


      if((!$insert &&  (isset($changedAttributes['area_id']) && ($changedAttributes['area_id'] != $this->area_id)) ||  (isset($changedAttributes['pickup_location_id']) && ($changedAttributes['pickup_location_id'] != $this->pickup_location_id) )) || $insert ) {

          if ($this->order_mode == static::ORDER_MODE_DELIVERY) {

              if($this->delivery_zone_id){

                $deliveryZone = DeliveryZone::findOne($this->delivery_zone_id);

                if($deliveryZone){

                  $this->country_name = $deliveryZone->country->country_name;
                  $this->country_name_ar = $deliveryZone->country->country_name_ar;

                  if($deliveryZone->business_location_id)
                    $this->business_location_name = $deliveryZone->businessLocation->business_location_name;

                  $this->save(false);

                }

              }

          } else if ($this->order_mode == Order::ORDER_MODE_PICK_UP){

            if ($this->pickup_location_id){
              $pickupLocation = BusinessLocation::findOne($this->pickup_location_id);

              if($pickupLocation){
                $this->country_name = $pickupLocation->country->country_name;
                $this->country_name_ar = $pickupLocation->country->country_name_ar;
                $this->business_location_name = $pickupLocation->business_location_name;

                $this->save(false);

              }

            }
          }


        }


        if (!$insert && $this->payment && $this->items_has_been_restocked && isset($changedAttributes['order_status']) && $changedAttributes['order_status'] == self::STATUS_ABANDONED_CHECKOUT) {

            $orderItems = $this->getOrderItems ();

            foreach ($orderItems->all () as $orderItem) {

                if ($orderItem->item_uuid) {

                    if (($orderItem->item->track_quantity && $orderItem->item->stock_qty >= $orderItem->qty) || !$orderItem->item->track_quantity) {
                        $orderItemExtraOptions = $orderItem->getOrderItemExtraOptions ();


                        if ($orderItemExtraOptions->count () > 0) {
                            foreach ($orderItemExtraOptions->all () as $orderItemExtraOption) {
                                if ($orderItemExtraOption->extraOption && $orderItemExtraOption->extraOption->stock_qty >= $orderItemExtraOption->qty)
                                    $orderItemExtraOption->extraOption->decreaseStockQty ($orderItemExtraOption->qty);
                                else {

                                    if ($orderItemExtraOption->extraOption->stock_qty !== null) {
                                        \Yii::$app->mailer->compose ([
                                            'html' => 'out-of-stock-order-html',
                                        ], [
                                            'order' => $this
                                        ])
                                            ->setFrom ([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                                            ->setTo ([$this->restaurant->restaurant_email])
                                            ->setSubject ('Order #' . $this->order_uuid)
                                            ->send ();
                                    }
                                }
                            }
                        }

                        $orderItem->item->decreaseStockQty ($orderItem->qty);

                    } else {
                        \Yii::$app->mailer->compose ([
                            'html' => 'out-of-stock-order-html',
                        ], [
                            'order' => $this
                        ])
                            ->setFrom ([\Yii::$app->params['supportEmail'] => \Yii::$app->name])
                            ->setTo ([$this->restaurant->restaurant_email])
                            ->setSubject ('Order #' . $this->order_uuid)
                            ->send ();
                    }
                }
            }

            $this->items_has_been_restocked = false;
            $this->save (false);
        }

        if ($insert) {

            if ($this->order_mode == static::ORDER_MODE_DELIVERY) {

                $this->delivery_time = $this->deliveryZone->delivery_time;
                $this->save (false);
            }



            $this->customer_phone_number = str_replace (' ', '', $this->customer_phone_number);
            //Save Customer data
            $customer_model = Customer::find ()->where (['customer_phone_number' => $this->customer_phone_number, 'restaurant_uuid' => $this->restaurant_uuid])->one ();


            if (!$customer_model) {//new customer
                $customer_model = new Customer();
                $customer_model->restaurant_uuid = $this->restaurant_uuid;
                $customer_model->customer_name = $this->customer_name;
                $customer_model->country_code = $this->customer_phone_country_code;
                $customer_model->customer_phone_number = $this->customer_phone_number;
            } else {
                $customer_model->customer_name = $this->customer_name;
            }


            if ($this->customer_email != null)
                $customer_model->customer_email = $this->customer_email;

            $customer_model->save (false);


            $this->customer_id = $customer_model->customer_id;

            if ($this->voucher_id) {

                $voucher_model = Voucher::findOne ($this->voucher_id);

                if ($voucher_model->isValid ($this->customer_phone_number)) {
                    $customerVoucher = new CustomerVoucher();
                    $customerVoucher->customer_id = $this->customer_id;
                    $customerVoucher->voucher_id = $this->voucher_id;
                    $customerVoucher->save ();
                }
            }


            if ($this->order_mode == static::ORDER_MODE_DELIVERY) {

                if ($this->area_id) {
                    $area_model = Area::findOne ($this->area_id);
                    $this->area_name = $area_model->area_name;
                    $this->area_name_ar = $area_model->area_name_ar;
                }


            }

            $payment_method_model = PaymentMethod::findOne ($this->payment_method_id);

            if ($payment_method_model) {
                $this->payment_method_name = $payment_method_model->payment_method_name;
                $this->payment_method_name_ar = $payment_method_model->payment_method_name_ar;
            }

            $this->save (false);
        }

        if (!$insert && $this->customer_id) {

            //Save Customer data
            $customer_model = Customer::findOne ($this->customer_id);

            $customer_model->customer_name = $this->customer_name;

            if ($this->customer_email != null)
                $customer_model->customer_email = $this->customer_email;

            $customer_model->save (false);
        }

        //todo : notification based on order status

        //if (isset($changedAttributes['order_status']) && $this->order_status != self::STATUS_PENDING)

    }

    /**
     * mobile notification on order marked as paid
     */
    public function sendOrderNotification()
    {
        $itemNames = ArrayHelper::getColumn ($this->orderItems, 'item_name');

        $heading = "New order received";
        $subtitle = "@ " . $this->restaurant->name;
        $content = "For " . implode (", ", $itemNames);

        /*Yii::t('app', "{currency} {amount}", [
            "amount" => number_format($this->total_price, 3),
            "currency" => $this->currency->code
        ]);*/

        foreach($this->restaurant->agentAssignments as $agentAssignment) {

            $filters = [
                [
                    "field" => "tag",
                    "key" => "agent_id",
                    "relation" => "=",
                    "value" => $agentAssignment->agent_id
            ]
            ];

            $data = [
                'subject' => 'order_uuid',
                'transfer_id' => $this->order_uuid
            ];

            MobileNotification::notifyAgent ($heading, $data, $filters, $subtitle, $content);
        }
    }

    public static function find()
    {
        return new query\OrderQuery(get_called_class ());
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry($modelClass = "\common\models\Country")
    {
        return $this->hasOne ($modelClass::className (), ['country_id' => 'shipping_country_id']);
    }

    /**
     * Gets query for [[DeliveryZone]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryZone($modelClass = "\common\models\DeliveryZone")
    {
        return $this->hasOne ($modelClass::className (), ['delivery_zone_id' => 'delivery_zone_id']);
    }

    /**
     * Gets query for [[BankDiscount]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBankDiscount($modelClass = "\common\models\BankDiscount")
    {
        return $this->hasOne ($modelClass::className (), ['bank_discount_id' => 'bank_discount_id']);
    }

    /**
     * Gets query for [[Restaurant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant")
    {
        return $this->hasOne ($modelClass::className (), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency($modelClass = "\common\models\Currency")
    {
        return $this->hasOne ($modelClass::className (), ['currency_id' => 'currency_id'])
            ->via ('restaurant');
    }

    /**
     * Gets query for [[RestaurantDelivery]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantDelivery($modelClass = "\common\models\RestaurantDelivery")
    {
        return $this->hasOne ($modelClass::className (), ['area_id' => 'area_id'])
            ->via ('area')
            ->andWhere (['restaurant_uuid' => $this->restaurant_uuid]);
    }

    /**
     * Gets query for [[Area]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArea($modelClass = "\common\models\Area")
    {
        return $this->hasOne ($modelClass::className (), ['area_id' => 'area_id']);
    }

    /**
     * Gets query for [[PaymentMethod]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod($modelClass = "\common\models\PaymentMethod")
    {
        return $this->hasOne ($modelClass::className (), ['payment_method_id' => 'payment_method_id']);
    }

    /**
     * Gets query for [[OrderItemExtraOption]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItemExtraOptions($modelClass = "\common\models\OrderItemExtraOption")
    {
        return $this->hasMany ($modelClass::className (), ['order_item_id' => 'order_item_id'])
            ->via ('orderItems');
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems($modelClass = "\common\models\OrderItem")
    {
        return $this->hasMany ($modelClass::className (), ['order_uuid' => 'order_uuid'])
            ->with ('orderItemExtraOptions');
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSelectedItems($modelClass = "\common\models\OrderItem")
    {
        return $this->hasMany ($modelClass::className (), ['order_uuid' => 'order_uuid']);
    }

    /**
     * Gets query for [[Items]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems($modelClass = "\common\models\Item")
    {
        return $this->hasMany ($modelClass::className (), ['item_uuid' => 'item_uuid'])
            ->via ('orderItems');
    }


    public function getItemImage($modelClass = "\agent\models\ItemImage")
    {
        return $this->hasOne ($modelClass::className (), ['item_uuid' => 'item_uuid'])
            ->via ('orderItems');
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer($modelClass = "\common\models\Customer")
    {
        return $this->hasOne ($modelClass::className (), ['customer_id' => 'customer_id']);
    }

    /**
     * Gets query for [[RestaurantBranch]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantBranch($modelClass = "\common\models\RestaurantBranch")
    {
        return $this->hasOne ($modelClass::className (), ['restaurant_branch_id' => 'restaurant_branch_id']);
    }

    /**
     * Gets query for [[PaymentUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayment($modelClass = "\common\models\Payment")
    {
        return $this->hasOne ($modelClass::className (), ['payment_uuid' => 'payment_uuid']);
    }

    /**
     * Gets query for [[Refunds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefunds($modelClass = "\common\models\Refund")
    {
        return $this->hasMany ($modelClass::className (), ['order_uuid' => 'order_uuid']);
    }

    /**
     * Gets query for [[BusinessLocation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessLocation($modelClass = "\common\models\BusinessLocation")
    {
        return $this->hasOne ($modelClass::className (), ['business_location_id' => 'business_location_id'])->via ('deliveryZone');
    }

    /**
     * Gets query for [[PickupLocation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPickupLocation($modelClass = "\common\models\BusinessLocation")
    {
        return $this->hasOne ($modelClass::className (), ['business_location_id' => 'pickup_location_id']);
    }

    /**
     * Gets query for [[Voucher]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVoucher($modelClass = "\common\models\Voucher")
    {
        return $this->hasOne ($modelClass::className (), ['voucher_id' => 'voucher_id']);
    }

    /**
     * Gets query for [[RefundedItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefundedItems($modelClass = "\common\models\RefundedItem")
    {
        return $this->hasMany ($modelClass::className (), ['order_uuid' => 'order_uuid']);
    }
}
