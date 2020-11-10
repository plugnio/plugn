<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "country".
 *
 * @property int $country_id
 * @property string $country_name
 * @property string $country_code
 * @property int $country_status
 *
 * @property Restaurant[] $restaurants
 */
class Country extends \yii\db\ActiveRecord
{


    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country_name', 'country_code'], 'required'],
            [['country_status'], 'integer'],
            [['country_name', 'country_code'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'country_id' => 'Country ID',
            'country_name' => 'Country Name',
            'country_code' => 'Country Code',
            'country_status' => 'Country Status',
        ];
    }

    /**
     * Gets query for [[Restaurants]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurants()
    {
        return $this->hasMany(Restaurant::className(), ['country_id' => 'country_id']);
    }
}
