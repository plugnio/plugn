<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "area_delivery_zone".
 *
 * @property int $area_delivery_zone
 * @property int $delivery_zone_id
 * @property int $city_id
 * @property int $area_id
 * @property string $restaurant_uuid
 *
 * @property Area $area
 * @property Restaurant $restaurant
 * @property DeliveryZone $deliveryZone
 */
class AreaDeliveryZone extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'area_delivery_zone';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['delivery_zone_id', 'restaurant_uuid'], 'required'],
            [['delivery_zone_id', 'area_id'], 'integer'],
            [['delivery_zone_id', 'area_id'], 'unique', 'targetAttribute' => ['delivery_zone_id', 'area_id']],
            [['area_id'], 'exist', 'skipOnError' => true, 'targetClass' => Area::className(), 'targetAttribute' => ['area_id' => 'area_id']],
            [['delivery_zone_id'], 'exist', 'skipOnError' => true, 'targetClass' => DeliveryZone::className(), 'targetAttribute' => ['delivery_zone_id' => 'delivery_zone_id']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'area_delivery_zone_id' => 'Area Delivery Zone ID',
            'delivery_zone_id' => 'Delivery Zone ID',
            'area_id' => 'Area ID',
            'restaurant_uuid' => 'Restaurant Uuid',
        ];
    }

    /**
     *
     * @param type $insert
     * @param type $changedAttributes
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);

        if ($insert) {

          if( $this->area_id){
            $this->country_id = $this->area->country->country_id;
            $this->city_id = $this->area->city_id;
          } else {
            $this->country_id = $this->deliveryZone->country_id;
          }

          return $this->save();

        }

        return true;
    }


    public function extraFields() {
        return [
            'area',
            'city',
            'deliveryZone'
        ];
    }

    /**
     * Gets query for [[Area]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArea()
    {
        return $this->hasOne(Area::className(), ['area_id' => 'area_id']);
    }


      /**
       * Gets query for [[City]].
       *
       * @return \yii\db\ActiveQuery
       */
      public function getCity()
      {
          return $this->hasOne(City::className(), ['city_id' => 'city_id'])->via('area');

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
     * Gets query for [[DeliveryZone]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryZone()
    {
        return $this->hasOne(DeliveryZone::className(), ['delivery_zone_id' => 'delivery_zone_id']);
    }



    /**
     * Gets query for [[BusinessLocation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessLocation()
    {
        return $this->hasOne(BusinessLocation::className(), ['business_location_id' => 'business_location_id'])->via('deliveryZone');
    }


}
