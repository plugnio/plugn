<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "restaurant_cuisine".
 *
 * @property string $restaurant_uuid
 * @property int $cuisine_id
 *
 * @property Cuisine $cuisine
 * @property Restaurant $restaurantUu
 */
class RestaurantCuisine extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurant_cuisine';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid', 'cuisine_id'], 'required'],
            [['cuisine_id'], 'integer'],
            [['restaurant_uuid'], 'string', 'max' => 36],
            [['restaurant_uuid', 'cuisine_id'], 'unique', 'targetAttribute' => ['restaurant_uuid', 'cuisine_id']],
            [['cuisine_id'], 'exist', 'skipOnError' => true, 'targetClass' => Cuisine::className(), 'targetAttribute' => ['cuisine_id' => 'cuisine_id']],
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
            'cuisine_id' => 'Cuisine ID',
        ];
    }

    /**
     * Gets query for [[Cuisine]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCuisine()
    {
        return $this->hasOne(Cuisine::className(), ['cuisine_id' => 'cuisine_id']);
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
