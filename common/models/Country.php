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
            'country_id' => 'Country ID',
            'country_name' => 'Country Name',
            'iso' => 'Iso',
            'emoji' => 'Emoji',
            'country_code' => 'Country Code',
        ];
    }

    /**
     * Gets query for [[Cities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCities()
    {
        return $this->hasMany(City::className(), ['country_id' => 'country_id']);
    }

    /**
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreas()
    {
        return $this->hasMany(Area::className(), ['city_id' => 'city_id'])->via('cities');
    }



    /**
     * Gets query for [[DeliveryZones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryZones()
    {
        return $this->hasMany(DeliveryZone::className(), ['country_id' => 'country_id']);
    }


    /**
     * Gets query for [[CountryPaymentMethods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountryPaymentMethods()
    {
        return $this->hasMany(CountryPaymentMethod::className(), ['country_id' => 'country_id']);
    }

    /**
     * Gets query for [[PaymentMethods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethods()
    {
        return $this->hasMany(PaymentMethod::className(), ['payment_method_id' => 'payment_method_id'])->viaTable('country_payment_method', ['country_id' => 'country_id']);
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
