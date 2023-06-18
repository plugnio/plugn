<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "city".
 *
 * @property int $city_id
 * @property int $state_id
 * @property int $country_id
 * @property string $city_name
 * @property string $city_name_ar
 *
 * @property Area[] $areas
  * @property Country $country
 */
class City extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'city';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['city_name', 'city_name_ar', 'country_id'], 'required'],
            [['city_name', 'city_name_ar'], 'string', 'max' => 255],
            [['state_id'], 'exist', 'skipOnError' => true, 'targetClass' => State::className (), 'targetAttribute' => ['state_id' => 'state_id']],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className (), 'targetAttribute' => ['country_id' => 'country_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'city_id' => Yii::t('app','City ID'),
            'state_id' => Yii::t('app','State ID'),
            'country_id' => Yii::t('app','Country ID'),
            'city_name' => Yii::t('app','City Name'),
            'city_name_ar' => Yii::t('app','City Name in Arabic'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function extraFields() {
        return [
           'areas',
           'state',
           'country'
        ];
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry($modelClass = "\common\models\Country")
    {
        return $this->hasOne($modelClass::className(), ['country_id' => 'country_id']);
    }

    /**
     * Gets query for [[State]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getState($modelClass = "\common\models\State")
    {
        return $this->hasMany($modelClass::className(), ['state_id' => 'state_id']);
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreas($modelClass = "\common\models\Area")
    {
        return $this->hasMany($modelClass::className(), ['city_id' => 'city_id']);
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreaDeliveryZones($modelClass = "\common\models\AreaDeliveryZone")
    {
        return $this->hasMany($modelClass::className(), ['city_id' => 'city_id']);
    }

        /**
     * Gets query for [[RestaurantDeliveryAreas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantDeliveryAreas($modelClass = "\common\models\RestaurantDelivery")
    {
        return $this->hasMany ($modelClass::className (), ['area_id' => 'area_id'])->via ('areas')->with ('area');
    }
}
