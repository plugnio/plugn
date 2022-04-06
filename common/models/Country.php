<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "country".
 *
 * @property int $country_id
 * @property string|null $country_name
 * @property string|null $country_name_ar
 * @property string|null $iso
 * @property string|null $emoji
 * @property int|null $country_code

 * @property BusinessLocation[] $businessLocations
 * @property City[] $cities
 * @property CountryPaymentMethod[] $countryPaymentMethods
 * @property PaymentMethod[] $paymentMethods
 * @property DeliveryZone[] $deliveryZones
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
            [['country_name'], 'required'],
            [['country_code'], 'integer'],
            [['country_name','country_name_ar'], 'string', 'max' => 80],
            [['iso'], 'string', 'max' => 2],
            [['emoji'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'country_id' => Yii::t('app','Country ID'),
            'country_name' => Yii::t('app','Country Name'),
            'iso' => Yii::t('app','Iso'),
            'emoji' => Yii::t('app','Emoji'),
            'country_code' => Yii::t('app','Country Code'),
        ];
    }

    /**
     * Gets query for [[Cities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCities($modelClass = "\common\models\City")
    {
        return $this->hasMany($modelClass::className(), ['country_id' => 'country_id']);
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreas($modelClass = "\common\models\Area")
    {
        return $this->hasMany($modelClass::className(), ['city_id' => 'city_id'])->via('cities');
    }

    /**
     * Gets query for [[DeliveryZones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryZones($modelClass = "\common\models\DeliveryZone")
    {
        return $this->hasMany($modelClass::className(), ['country_id' => 'country_id']);
    }

    /**
     * Gets query for [[CountryPaymentMethods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountryPaymentMethods($modelClass = "\common\models\CountryPaymentMethod")
    {
        return $this->hasMany($modelClass::className(), ['country_id' => 'country_id']);
    }

    /**
     * Gets query for [[PaymentMethods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethods($modelClass = "\common\models\PaymentMethod")
    {
        return $this->hasMany($modelClass::className(), ['payment_method_id' => 'payment_method_id'])
            ->viaTable('country_payment_method', ['country_id' => 'country_id']);
    }

    /**
     * Gets query for [[Restaurants]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurants($modelClass = "\common\models\Restaurant")
    {
        return $this->hasMany($modelClass::className(), ['country_id' => 'country_id']);
    }
}
