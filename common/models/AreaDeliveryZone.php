<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "area_delivery_zone".
 *
 * @property int $area_delivery_zone
 * @property int $delivery_zone_id
 * @property int $state_id
 * @property int $city_id
 * @property int $area_id
 * @property string $restaurant_uuid
 * @property boolean $is_deleted
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
            [['delivery_zone_id', 'area_id', 'is_deleted'], 'integer'],
            [['delivery_zone_id', 'area_id'], 'unique', 'targetAttribute' => ['delivery_zone_id', 'area_id', 'is_deleted']],
            [['state_id'], 'exist', 'skipOnError' => true, 'targetClass' => State::className(), 'targetAttribute' => ['state_id' => 'state_id']],
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
            'area_delivery_zone_id' => Yii::t('app', 'Area Delivery Zone ID'),
            'delivery_zone_id' => Yii::t('app', 'Delivery Zone ID'),
            'area_id' => Yii::t('app', 'Area ID'),
            'state_id' => Yii::t('app', 'State ID'),
            'country_id' => Yii::t('app', 'Country ID'),
            'restaurant_uuid' => Yii::t('app', 'Restaurant Uuid'),
            'is_deleted' => Yii::t('app', 'Is Deleted?'),
        ];
    }

    public function beforeSave($insert)
    {
        if ($insert) {

            if($this->area_id) {
                $this->country_id = $this->area->city? $this->area->city->country_id: null;
                $this->city_id = $this->area->city_id;
            } else {
                $this->country_id = $this->deliveryZone->country_id;
            }
        }

        return parent::beforeSave($insert);
    }

    /**
     *
     * @param type $insert
     * @param type $changedAttributes
     */
    public function afterSave($insert, $changedAttributes) {

        parent::afterSave($insert, $changedAttributes);

        return true;
    }

    public function extraFields() {
        return [
            'area',
            'city',
            'state',
            'country',
            'deliveryZone',
            'businessLocation'
        ];
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry($modelClass = "\common\models\Country")
    {
        return $this->hasOne($modelClass::className(), ['country_id' => 'country_id']);
    }

    /**
     * Gets query for [[State]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getState($modelClass = "\common\models\State")
    {
        return $this->hasOne($modelClass::className(), ['state_id' => 'state_id']);
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
        return $this->hasOne($modelClass::className(), ['delivery_zone_id' => 'delivery_zone_id'])
            ->andWhere(['delivery_zone.is_deleted' => 0]);
    }

    /**
     * Gets query for [[BusinessLocation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessLocation($modelClass = "\common\models\BusinessLocation")
    {
        return $this->hasOne($modelClass::className(), ['business_location_id' => 'business_location_id'])
            ->andWhere(['business_location.is_deleted' => 0])
            ->via('deliveryZone');
    }

    /**
     * @return query\AreaDeliveryZoneQuery
     */
    public static function find() {
        return new query\AreaDeliveryZoneQuery(get_called_class());
    }
}
