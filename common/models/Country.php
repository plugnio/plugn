<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "country".
 *
 * @property int $country_id
 * @property string|null $country_name
 * @property string|null $iso
 * @property string|null $iso3
 * @property int|null $country_code
 *
 * @property Restaurant[] $restaurants
 */
class Country extends \yii\db\ActiveRecord
{
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
            [['country_code'], 'integer'],
            [['country_name'], 'string', 'max' => 80],
            [['iso'], 'string', 'max' => 2],
            [['iso3'], 'string', 'max' => 3],
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
            'iso' => 'Iso',
            'iso3' => 'Iso3',
            'country_code' => 'Country Code',
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
