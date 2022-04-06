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
 * @property Order[] $orders
 * @property Country $country
 * @property RestaurantDelivery[] $restaurantDeliveryAreas
 * @property Restaurant[] $restaurant
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
            'area_id' => Yii::t('app', 'Area ID'),
            'city_id' => Yii::t('app', 'City ID'),
            'area_name' => Yii::t('app', 'Area Name'),
            'area_name_ar' => Yii::t('app', 'Area Name in Arabic'),
            'latitude' => Yii::t('app', 'Latitude'),
            'longitude' => Yii::t('app', 'Longitude'),
        ];
    }

    public function extraFields() {
        return [
            'city'
        ];
    }
    
    /**
     * Gets query for [[City]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCity($modelClass = "\common\models\City")
    {
        return $this->hasOne($modelClass::className(), ['city_id' => 'city_id']);
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry($modelClass = "\common\models\Country")
    {
        return $this->hasOne($modelClass::className(), ['country_id' => 'country_id'])->via('city');
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders($modelClass = "\common\models\Order")
    {
        return $this->hasMany($modelClass::className(), ['area_id' => 'area_id']);
    }

    /**
     * Gets query for [[RestaurantDeliveryAreas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreaDeliveryZones($modelClass = "\common\models\AreaDeliveryZone")
    {
        return $this->hasMany($modelClass::className(), ['area_id' => 'area_id']);
    }

    /**
     * Gets query for [[RestaurantDeliveryAreas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantDeliveryAreas($modelClass = "\common\models\RestaurantDelivery")
    {
        return $this->hasMany($modelClass::className(), ['area_id' => 'area_id']);
    }

    /**
     * Gets query for [[RestaurantUus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])->viaTable('area_delivery_zone', ['area_id' => 'area_id']);
    }
}
