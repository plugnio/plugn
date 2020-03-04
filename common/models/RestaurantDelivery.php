<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "restaurant_delivery".
 *
 * @property string $restaurant_uuid
 * @property int $area_id
 * @property int $min_delivery_time
 * @property int $delivery_fee
 *
 * @property Area $area
 * @property City $city
 * @property Restaurant $restaurant
 */
class RestaurantDelivery extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurant_delivery';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid', 'area_id'], 'required'],
            [['area_id','min_delivery_time','delivery_fee'], 'integer'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['restaurant_uuid', 'area_id'], 'unique', 'targetAttribute' => ['restaurant_uuid', 'area_id']],
            [['area_id'], 'exist', 'skipOnError' => true, 'targetClass' => Area::className(), 'targetAttribute' => ['area_id' => 'area_id']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'restaurant_uuid' => 'Restaurant Uuid',
            'area_id' => 'Area ID',
            'min_delivery_time' => 'Min Delivery time',
            'delivery_fee' => 'Delivery fee',
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
        return $this->hasOne(City::className(), ['city_id' => 'city_id'])->via('area')->distinct();
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
}
