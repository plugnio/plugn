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
 * @property int $prep_time
 *
 * @property Restaurant $restaurant
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
            [['branch_name_en', 'branch_name_ar', 'restaurant_uuid'], 'required'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['prep_time'], 'integer' , 'min'=> 0],
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
            'branch_name_en' => 'Branch Name English',
            'branch_name_ar' => 'Branch Name in Arabic',
            'prep_time' => 'Preparation time',
        ];
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
}
