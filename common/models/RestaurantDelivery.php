<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "restaurant_delivery".
 *
 * @property string $restaurant_uuid
 * @property int $area_id
 *
 * @property Area $area
 * @property Restaurant $restaurantUu
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
            [['area_id'], 'integer'],
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
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantUu()
    {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }
}
