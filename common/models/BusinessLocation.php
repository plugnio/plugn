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
 * @property int $support_pick_up
 * @property float $business_location_tax
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
            [['restaurant_uuid', 'country_id', 'business_location_name', 'business_location_name_ar'], 'required'],
            [['country_id' , 'support_pick_up'], 'integer'],
            [['support_pick_up'], 'default', 'value' => 0],
            [['business_location_tax'], 'default', 'value' => 0],
            [['business_location_tax'], 'number', 'min' => 0, 'max' => 100],
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
            'country_id' => 'Located in',
            'business_location_name' => 'Location Name',
            'business_location_name_ar' => 'Location Name in Arabic',
            'support_pick_up' => 'Support Pick Up',
            'business_location_tax' => 'Tax / VAT',
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
    //
    // /**
    //  * Gets query for [[DeliveryZones]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getDeliveryZones()
    // {
    //     return $this->hasMany(DeliveryZone::className(), ['business_location_id' => 'business_location_id'])->joinWith(['areas']);
    // }
    public function getDeliveryZones()
    {
        return $this->hasMany(DeliveryZone::className(), ['business_location_id' => 'business_location_id']);
    }

    public function getDeliveryZoneByCountryId($countryId)
    {
        return $this->hasMany(DeliveryZone::className(), ['business_location_id' => 'business_location_id'])->where(['delivery_zone.country_id' => 1]);
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
