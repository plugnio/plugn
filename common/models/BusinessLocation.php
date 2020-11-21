<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "business_location".
 *
 * @property int $business_location_id
 * @property string $restaurant_uuid
 * @property string $business_location_name
 * @property string $business_location_name_ar
 * @property int $support_delivery
 * @property int $support_pick_up
 *
 * @property Restaurant $restaurant
  * @property Country $country
 * @property DeliveryZone[] $deliveryZones
 */
class BusinessLocation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business_location';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid', 'country_id', 'business_location_name', 'business_location_name_ar', 'support_delivery', 'support_pick_up'], 'required'],
            [['support_delivery', 'country_id' , 'support_pick_up'], 'integer'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['business_location_name', 'business_location_name_ar'], 'string', 'max' => 255],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'country_id']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'business_location_id' => 'Business Location ID',
            'restaurant_uuid' => 'Restaurant Uuid',
            'country_id' => 'Country',
            'business_location_name' => 'Business Location Name',
            'business_location_name_ar' => 'Business Location Name Ar',
            'support_delivery' => 'Support Delivery',
            'support_pick_up' => 'Support Pick Up',
        ];
    }


    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['country_id' => 'country_id']);
    }


    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[DeliveryZones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryZones()
    {
        return $this->hasMany(DeliveryZone::className(), ['business_location_id' => 'business_location_id'])->joinWith(['areas']);
    }

     /**
     * Gets query for [[AreaDeliveryZones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreaDeliveryZones()
    {
        return $this->hasMany(AreaDeliveryZone::className(), ['delivery_zone_id' => 'delivery_zone_id'])->via('deliveryZones');
    }


}
