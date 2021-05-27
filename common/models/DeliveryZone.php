<?php


namespace common\models;

use Yii;

/**
 * This is the model class for table "delivery_zone".
 *
 * @property int $delivery_zone_id
 * @property int $country_id
 * @property int $restaurant_uuid
 * @property int $business_location_id
 * @property int|null $delivery_time
 * @property float|null $delivery_fee
 * @property float|null $min_charge
 * @property float|null $delivery_zone_tax
 * @property string $time_unit
 *
 * @property AreaDeliveryZone[] $areaDeliveryZones
 * @property Area[] $areas
 * @property BusinessLocation $businessLocation
 * @property Restaurant $restaurant
 * @property Country $country
 */
class DeliveryZone extends \yii\db\ActiveRecord
{

    public $selectedAreas;

    //Values for `time_unit`
    const TIME_UNIT_MIN = 'min';
    const TIME_UNIT_HRS = 'hrs';
    const TIME_UNIT_DAY = 'day';



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
            [['country_id','business_location_id', 'restaurant_uuid','time_unit', 'delivery_fee', 'min_charge', 'delivery_time'], 'required'],
            ['time_unit', 'in', 'range' => [self::TIME_UNIT_MIN,self::TIME_UNIT_HRS, self::TIME_UNIT_DAY]],
            ['time_unit', 'string','min' => 3  , 'max' => 3],
            [['business_location_id', 'delivery_time','country_id'], 'integer'],
            [['delivery_zone_tax'], 'number', 'max' => 100],
            [['delivery_fee', 'min_charge'], 'number'],
            [['selectedAreas'], 'safe'],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
            [['business_location_id'], 'exist', 'skipOnError' => true, 'targetClass' => BusinessLocation::className(), 'targetAttribute' => ['business_location_id' => 'business_location_id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'country_id']]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'delivery_zone_id' => 'Delivery Zone ID',
            'country_id' => 'Country ID',
            'business_location_id' => 'Business Location',
            'delivery_time' => 'Delivery Time',
            'delivery_fee' => 'Delivery Fee',
            'min_charge' => 'Min Charge',
            'delivery_zone_tax' => 'Tax Override',
        ];
    }

    /**
     * Returns String value of current status
     * @return string
     */
    public function getTimeUnit() {
        switch ($this->time_unit) {
          case self::TIME_UNIT_MIN:
              return "Minutes";
              break;
          case self::TIME_UNIT_HRS:
            return  $this->delivery_time == 1 ?  "Hour" : "Hours";
              break;
          case self::TIME_UNIT_DAY:
              return  $this->delivery_time == 1 ?  "Day" : "Days";
              break;
        }
    }

    /**
     * Gets query for [[AreaDeliveryZones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreaDeliveryZones()
    {
        return $this->hasMany(AreaDeliveryZone::className(), ['delivery_zone_id' => 'delivery_zone_id'])->joinWith(['area']);
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreas()
    {
        return $this->hasMany(Area::className(), ['area_id' => 'area_id'])->viaTable('area_delivery_zone', ['delivery_zone_id' => 'delivery_zone_id']);
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
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['currency_id' => 'currency_id'])->via('restaurant');
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

}
