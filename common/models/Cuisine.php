<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "cuisine".
 *
 * @property int $cuisine_id
 * @property string $cuisine_name
 * @property string|null $cuisine_name_ar
 *
 * @property RestaurantCuisine[] $restaurantCuisines
 * @property Restaurant[] $restaurantUus
 */
class Cuisine extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cuisine';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cuisine_name'], 'required'],
            [['cuisine_name', 'cuisine_name_ar'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'cuisine_id' => 'Cuisine ID',
            'cuisine_name' => 'Cuisine Name',
            'cuisine_name_ar' => 'Cuisine Name Ar',
        ];
    }

    /**
     * Gets query for [[RestaurantCuisines]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantCuisines()
    {
        return $this->hasMany(RestaurantCuisine::className(), ['cuisine_id' => 'cuisine_id']);
    }

    /**
     * Gets query for [[RestaurantUus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantUus()
    {
        return $this->hasMany(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid'])->viaTable('restaurant_cuisine', ['cuisine_id' => 'cuisine_id']);
    }
}
