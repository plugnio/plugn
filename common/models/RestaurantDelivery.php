<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "restaurant_delivery".
 *
 * @property string $restaurant_uuid
 * @property int $area_id
 * @property int $delivery_time
 * @property int $delivery_time_ar
 * @property int $delivery_fee
 * @property float $min_charge
 *
 * @property Area $area
 * @property City $city
 * @property Restaurant $restaurant
 */
class RestaurantDelivery extends \yii\db\ActiveRecord {

    public $restaurant_delivery_area_array;
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'restaurant_delivery';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['restaurant_uuid', 'area_id'], 'required'],
            [['restaurant_delivery_area_array'], 'safe'],
            [['area_id', 'delivery_time','delivery_time_ar'], 'integer', 'min' => 0],
            [['delivery_fee', 'min_charge'], 'number', 'min' => 0],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['restaurant_uuid', 'area_id'], 'unique', 'targetAttribute' => ['restaurant_uuid', 'area_id']],
            [['area_id'], 'exist', 'skipOnError' => true, 'targetClass' => Area::className(), 'targetAttribute' => ['area_id' => 'area_id']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'restaurant_uuid' => 'Restaurant Uuid',
            'area_id' => 'Area ID',
            'delivery_time' => 'Delivery time',
            'delivery_time_ar' => 'Delivery time Ar',
            'delivery_fee' => 'Delivery fee',
            'restaurant_delivery_area_array' => 'Delivery Areas',
            'min_charge' => 'Min Charge',
        ];
    }

    /**
     * save restaurant delivery areas
     */
    public function saveRestaurantDeliveryArea($delivery_areas) {
        
        $stored_restaurant_delivery_areas = RestaurantDelivery::find()
                ->where(['restaurant_uuid' => $this->restaurant_uuid])
                ->all();

        foreach ($stored_restaurant_delivery_areas as $restaurant_delivery_area) {
            if (!in_array($restaurant_delivery_area->area_id, $delivery_areas)) {
                RestaurantDelivery::deleteAll(['restaurant_uuid' => $this->restaurant_uuid, 'area_id' => $restaurant_delivery_area->area_id]);
            }
        }
        
        foreach ($delivery_areas as $area_id) {
            $delivery_area = new RestaurantDelivery();
            $delivery_area->area_id = $area_id;
            $delivery_area->restaurant_uuid = $this->restaurant_uuid;
            $delivery_area->save();
        }
        
        return true;
    }

    /**
     * Gets query for [[Area]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArea($modelClass = "\common\models\Area") {
        return $this->hasOne($modelClass::className(), ['area_id' => 'area_id']);
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity($modelClass = "\common\models\City") {
        return $this->hasOne($modelClass::className(), ['city_id' => 'city_id'])->via('area');
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant") {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

}
