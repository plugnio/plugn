<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "working_day".
 *
 * @property int $working_day_id
 * @property string|null $name
 * @property string|null $name_ar
 *
 * @property WorkingHours[] $workingHours
 * @property Restaurant[] $restaurantUus
 */
class WorkingDay extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'working_day';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'name_ar'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'working_day_id' => 'Working Day ID',
            'name' => 'Name',
            'name_ar' => 'Name Ar',
        ];
    }

    /**
     * Gets query for [[WorkingHours]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkingHours()
    {
        return $this->hasMany(WorkingHours::className(), ['working_day_id' => 'working_day_id'])->inverseOf('workingDay');
    }

    /**
     * Gets query for [[RestaurantUus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantUus()
    {
        return $this->hasMany(Restaurant::className(), ['restaurant_uuid' => 'restaurant_uuid'])->viaTable('working_hours', ['working_day_id' => 'working_day_id']);
    }
}
