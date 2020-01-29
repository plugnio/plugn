<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "area".
 *
 * @property int $area_id
 * @property int $city_id
 * @property string $area_name
 * @property string $area_name_ar
 * @property float|null $latitude
 * @property float|null $longitude
 *
 * @property City $city
 * @property RestaurantDelivery[] $restaurantDeliveries
 * @property Restaurant[] $restaurantUus
 */
class Area extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['city_id', 'area_name', 'area_name_ar'], 'required'],
            [['city_id'], 'integer'],
            [['latitude', 'longitude'], 'number'],
            [['area_name', 'area_name_ar'], 'string', 'max' => 255],
            [['city_id'], 'exist', 'skipOnError' => true, 'targetClass' => City::className(), 'targetAttribute' => ['city_id' => 'city_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'area_id' => 'Area ID',
            'city_id' => 'City ID',
            'area_name' => 'Area Name',
            'area_name_ar' => 'Area Name Ar',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
        ];
    }

    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['city_id' => 'city_id'])->where(['city_id'=>1]);
    }

    /**
     * Gets query for [[RestaurantDeliveries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantDeliveries()
    {
        return $this->hasMany(RestaurantDelivery::className(), ['area_id' => 'area_id']);
    }

    /**
     * Gets query for [[RestaurantUus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantUus()
    {
        return $this->hasMany(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid'])->viaTable('restaurant_delivery', ['area_id' => 'area_id']);
    }
}
