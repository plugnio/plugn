<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "restaurant_branch".
 *
 * @property int $restaurant_branch_id
 * @property string|null $restaurant_uuid
 * @property string|null $branch_name_en
 * @property string|null $branch_name_ar
 *
 * @property Restaurant $restaurantUu
 */
class RestaurantBranch extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'restaurant_branch';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['branch_name_en', 'branch_name_ar'], 'string', 'max' => 255],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'restaurant_branch_id' => 'Restaurant Branch ID',
            'restaurant_uuid' => 'Restaurant Uuid',
            'branch_name_en' => 'Branch Name En',
            'branch_name_ar' => 'Branch Name Ar',
        ];
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
