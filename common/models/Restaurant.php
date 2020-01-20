<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\behaviors\AttributeBehavior;

/**
 * This is the model class for table "restaurant".
 *
 * @property string $restaurant_uuid
 * @property int $vendor_id
 * @property string $name
 * @property string|null $name_ar
 * @property string|null $tagline
 * @property string|null $tagline_ar
 * @property int $restaurant_status
 * @property string $thumbnail_image
 * @property string $logo
 * @property int $support_delivery
 * @property int $support_pick_up
 * @property string|null $min_delivery_time
 * @property string|null $min_pickup_time
 * @property string|null $operating_from
 * @property string|null $operating_to
 * @property float $delivery_fee
 * @property float $min_charge
 * @property float $min_order
 * @property string|null $location
 * @property string|null $location_ar
 * @property float|null $location_latitude
 * @property float|null $location_longitude
 * @property string|null $phone_number
 * @property string|null $restaurant_created_at
 * @property string|null $restaurant_updated_at
 *
 * @property Item[] $items
 * @property Vendor $vendor
 * @property RestaurantDelivery[] $restaurantDeliveries
 * @property Area[] $areas
 * @property RestaurantPaymentMethod[] $restaurantPaymentMethods
 * @property PaymentMethod[] $paymentMethods
 */
class Restaurant extends \yii\db\ActiveRecord {

    //Values for `restaurant_status`
    const RESTAURANT_STATUS_OPEN = 1;
    const RESTAURANT_STATUS_BUSY = 2;
    const RESTAURANT_STATUS_CLOSED = 3;

    public $restaurant_delivery_area;
    public $restaurant_payments_method;

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'restaurant';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['vendor_id', 'name', 'thumbnail_image', 'logo', 'support_delivery', 'support_pick_up','restaurant_payments_method', 'restaurant_delivery_area'], 'required'],
            ['min_delivery_time', 'required', 'when' => function ($model) {
                    return $model->support_delivery == 1;
                }, 'whenClient' => "function (attribute, value) {
                return $('#supportDeliveryInput').val() == 1;
            }"],
            ['min_pickup_time', 'required', 'when' => function ($model) {
                    return $model->support_pick_up == 1;
                }, 'whenClient' => "function (attribute, value) {
                return $('#supportPickupInput').val() == 1;
            }"],
            [['restaurant_delivery_area','restaurant_payments_method'], 'safe'],
            [['vendor_id', 'restaurant_status', 'support_delivery', 'support_pick_up'], 'integer'],
            [['min_delivery_time', 'min_pickup_time', 'operating_from', 'operating_to', 'restaurant_created_at', 'restaurant_updated_at'], 'safe'],
            [['delivery_fee', 'min_charge', 'location_latitude', 'location_longitude'], 'number'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['name', 'name_ar', 'tagline', 'tagline_ar', 'thumbnail_image', 'logo', 'location', 'location_ar', 'phone_number'], 'string', 'max' => 255],
            [['restaurant_uuid'], 'unique'],
            [['vendor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vendor::className(), 'targetAttribute' => ['vendor_id' => 'vendor_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'restaurant_uuid' => 'Restaurant Uuid',
            'vendor_id' => 'Vendor ID',
            'name' => 'Name',
            'name_ar' => 'Name Ar',
            'tagline' => 'Tagline',
            'tagline_ar' => 'Tagline Ar',
            'restaurant_status' => 'Restaurant Status',
            'thumbnail_image' => 'Thumbnail Image',
            'logo' => 'Logo',
            'support_delivery' => 'Support Delivery',
            'support_pick_up' => 'Support Pick Up',
            'min_delivery_time' => 'Min Delivery Time',
            'min_pickup_time' => 'Min Pickup Time',
            'operating_from' => 'Operating From',
            'operating_to' => 'Operating To',
            'delivery_fee' => 'Delivery Fee',
            'min_charge' => 'Min Charge',
            'restaurant_delivery_area' => 'Delivery Areas',
            'location' => 'Location',
            'location_ar' => 'Location Ar',
            'location_latitude' => 'Location Latitude',
            'location_longitude' => 'Location Longitude',
            'phone_number' => 'Phone Number',
            'restaurant_created_at' => 'Restaurant Created At',
            'restaurant_updated_at' => 'Restaurant Updated At',
        ];
    }

    /**
     * 
     * @return type
     */
    public function behaviors() {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    \yii\db\ActiveRecord::EVENT_BEFORE_INSERT => 'restaurant_uuid',
                ],
                'value' => function() {
                    if (!$this->restaurant_uuid)
                        $this->restaurant_uuid = 'rest_' . Yii::$app->db->createCommand('SELECT uuid()')->queryScalar();

                    return $this->restaurant_uuid;
                }
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'restaurant_created_at',
                'updatedAtAttribute' => 'restaurant_updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * Returns String value of current status
     * @return string
     */
    public function getStatus() {
        switch ($this->restaurant_status) {
            case self::RESTAURANT_STATUS_OPEN:
                return "Open";
                break;
            case self::RESTAURANT_STATUS_BUSY:
                return "Busy";
                break;
            case self::RESTAURANT_STATUS_CLOSED:
                return "Closed";
                break;
        }

        return "Couldnt find a status";
    }

    /**
     * Promotes current restaurant to open restaurant while disabling rest
     */
    public function promoteToOpenRestaurant() {
        $this->restaurant_status = Restaurant::RESTAURANT_STATUS_OPEN;
        $this->save(false);
    }

    /**
     * Promotes current restaurant to close restaurant while disabling rest
     */
    public function promoteToCloseRestaurant() {
        $this->restaurant_status = Restaurant::RESTAURANT_STATUS_CLOSED;
        $this->save(false);
    }

    /**
     * Promotes current restaurant to busy restaurant while disabling rest
     */
    public function promoteToBusyRestaurant() {
        $this->restaurant_status = Restaurant::RESTAURANT_STATUS_BUSY;
        $this->save(false);
    }

    /**
     * save restaurant delivery areas
     */
    public function saveRestaurantDeliveryArea($delivery_areas) {
        
        RestaurantDelivery::deleteAll(['restaurant_uuid' => $this->restaurant_uuid]);
        
        foreach ($delivery_areas as $area_id) {
            $delivery_area = new RestaurantDelivery();
            $delivery_area->area_id = $area_id;
            $delivery_area->restaurant_uuid = $this->restaurant_uuid;
            $delivery_area->save();
        }
    }
    
    /**
     * save restaurant payment method
     */
    public function saveRestaurantPaymentMethod($payments_method) {
        
        RestaurantPaymentMethod::deleteAll(['restaurant_uuid' => $this->restaurant_uuid]);
        
        foreach ($payments_method as $payment_method_id) {
            $payments_method = new RestaurantPaymentMethod();
            $payments_method->payment_method_id = $payment_method_id;
            $payments_method->restaurant_uuid = $this->restaurant_uuid;
            $payments_method->save();
        }
    }

    /**
     * Gets query for [[Items]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems() {
        return $this->hasMany(Item::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Vendor]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVendor() {
        return $this->hasOne(Vendor::className(), ['vendor_id' => 'vendor_id']);
    }

    /**
     * Gets query for [[RestaurantDeliveries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantDeliveries() {
        return $this->hasMany(RestaurantDelivery::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreas() {
        return $this->hasMany(Area::className(), ['area_id' => 'area_id'])->viaTable('restaurant_delivery', ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[RestaurantPaymentMethods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantPaymentMethods() {
        return $this->hasMany(RestaurantPaymentMethod::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[PaymentMethods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethods() {
        return $this->hasMany(PaymentMethod::className(), ['payment_method_id' => 'payment_method_id'])->viaTable('restaurant_payment_method', ['restaurant_uuid' => 'restaurant_uuid']);
    }

}
