<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "working_hours".
 *
 * @property int $working_day_id
 * @property string $restaurant_uuid
 * @property string|null $operating_from
 * @property string|null $operating_to
 *
 * @property Restaurant $restaurantUu
 * @property WorkingDay $workingDay
 */
class WorkingHours extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'working_hours';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['working_day_id', 'restaurant_uuid'], 'required'],
            [['working_day_id'], 'integer'],
            [['operating_from', 'operating_to'], 'safe'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['working_day_id', 'restaurant_uuid'], 'unique', 'targetAttribute' => ['working_day_id', 'restaurant_uuid']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
            [['working_day_id'], 'exist', 'skipOnError' => true, 'targetClass' => WorkingDay::className(), 'targetAttribute' => ['working_day_id' => 'working_day_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'working_day_id' => 'Working Day ID',
            'restaurant_uuid' => 'Restaurant Uuid',
            'operating_from' => 'Operating From',
            'operating_to' => 'Operating To',
        ];
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantUu()
    {
        return $this->hasOne(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid'])->inverseOf('workingHours');
    }

    /**
     * Gets query for [[WorkingDay]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkingDay()
    {
        return $this->hasOne(WorkingDay::className(), ['working_day_id' => 'working_day_id'])->inverseOf('workingHours');
    }
}
