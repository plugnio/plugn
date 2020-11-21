<?php


namespace common\models;

use Yii;

/**
 * This is the model class for table "delivery_zone".
 *
 * @property int $delivery_zone_id
 * @property int $business_location_id
 * @property int|null $delivery_time
 * @property float|null $delivery_fee
 * @property float|null $min_charge
 *
 * @property AreaDeliveryZone[] $areaDeliveryZones
 * @property Area[] $areas
 * @property BusinessLocation $businessLocation
 */
class DeliveryZone extends \yii\db\ActiveRecord
{

    public $selectedAreas;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'delivery_zone';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['business_location_id'], 'required'],
            [['business_location_id', 'delivery_time'], 'integer'],
            [['delivery_fee', 'min_charge'], 'number'],
            [['selectedAreas'], 'safe'],
            [['business_location_id'], 'exist', 'skipOnError' => true, 'targetClass' => BusinessLocation::className(), 'targetAttribute' => ['business_location_id' => 'business_location_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'delivery_zone_id' => 'Delivery Zone ID',
            'business_location_id' => 'Business Location',
            'delivery_time' => 'Delivery Time',
            'delivery_fee' => 'Delivery Fee',
            'min_charge' => 'Min Charge',
        ];
    }

    /**
     * Gets query for [[AreaDeliveryZones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreaDeliveryZones()
    {
        return $this->hasMany(AreaDeliveryZone::className(), ['delivery_zone_id' => 'delivery_zone_id']);
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreas()
    {
        return $this->hasMany(Area::className(), ['area_id' => 'area_id'])->viaTable('area_delivery_zone', ['delivery_zone_id' => 'delivery_zone_id'])->joinWith('city');
    }


      /**
       * Gets query for [[Country]].
       *
       * @return \yii\db\ActiveQuery
       */
      public function getCountry()
      {
          return $this->hasOne(Country::className(), ['country_id' => 'country_id'])->via('businessLocation');
      }


      /**
       * Gets query for [[City]].
       *
       * @return \yii\db\ActiveQuery
       */
      public function getCities()
      {
          return $this->hasMany(City::className(), ['city_id' => 'city_id'])->via('areas');
      }




    /**
     * Gets query for [[BusinessLocation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessLocation()
    {
        return $this->hasOne(BusinessLocation::className(), ['business_location_id' => 'business_location_id']);
    }


    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant()
    {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid'])->via('businessLocation');
    }

}
