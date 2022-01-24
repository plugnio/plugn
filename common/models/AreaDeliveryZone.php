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
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'country_id']],
            [['area_id'], 'exist', 'skipOnError' => true, 'targetClass' => Area::className(), 'targetAttribute' => ['area_id' => 'area_id']],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'city_id']],
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

          self::updateAll([
              'country_id' => $this->country_id,
              'city_id' => $this->city_id
          ], [
              'area_delivery_zone' => $this->area_delivery_zone
          ]);
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
    public function getArea($modelClass = "\common\models\Area")
    {
        return $this->hasOne($modelClass::className(), ['area_id' => 'area_id']);
    }

      /**
       * Gets query for [[City]].
       *
       * @return \yii\db\ActiveQuery
       */
      public function getCity($modelClass = "\common\models\City")
      {
          return $this->hasOne($modelClass::className(), ['city_id' => 'city_id'])->via('area');
      }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant")
    {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[DeliveryZone]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryZone($modelClass = "\common\models\DeliveryZone")
    {
        return $this->hasOne($modelClass::className(), ['delivery_zone_id' => 'delivery_zone_id']);
    }

    /**
     * Gets query for [[BusinessLocation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessLocation($modelClass = "\common\models\BusinessLocation")
    {
        return $this->hasOne($modelClass::className(), ['business_location_id' => 'business_location_id'])->via('deliveryZone');
    }
}
