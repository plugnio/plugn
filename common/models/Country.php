<?php

namespace common\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "country".
 *
 * @property int $country_id
 * @property string|null $country_name
 * @property string|null $country_name_ar
 * @property string|null $iso
 * @property string|null $currency_code
 * @property string|null $emoji
 * @property int|null $country_code

 * @property BusinessLocation[] $businessLocations
 * @property City[] $cities
 * @property CountryPaymentMethod[] $countryPaymentMethods
 * @property PaymentMethod[] $paymentMethods
 * @property DeliveryZone[] $deliveryZones
 * @property Restaurant[] $restaurants
 * @property Currency $currency
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
            [['currency_code'], 'string', "max" => 3],
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
            "currency_code" => Yii::t('app','Currency Code'),
        ];
    }

    /**
     * @return mixed
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     */
    public function getCashOrderTotal() {

        $paymentMethod = PaymentMethod::find()
            ->andWhere(['payment_method_code' => PaymentMethod::CODE_CASH])
            ->one();

        $cacheDuration = 60 * 60 * 24 * 7;// 7 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM `order` where payment_method_id="'.$paymentMethod->payment_method_id.'"',
        ]);

        return Restaurant::getDb()->cache(function($db) {

            return Order::find()
                ->andWhere(['shipping_country_id' => $this->country_id])
                ->select(new Expression("currency_code, SUM(total_price) as total"))
                ->checkoutCompleted()
                ->groupBy('order.currency_code')
                ->asArray()
                ->all();

        }, $cacheDuration, $cacheDependency);
    }

    /**
     * @return mixed
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     */
    public function getCSV() {

        $cacheDuration = 60 * 60 * 24 * 7;// 7 day then delete from cache

        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM payment',
        ]);

        return Payment::getDb()->cache(function($db) {

            return Payment::find()
                ->select(new Expression("currency_code, SUM(payment_net_amount) as payment_net_amount, SUM(payment_gateway_fee) as payment_gateway_fees,
                            SUM(plugn_fee) as plugn_fees, SUM(partner_fee) as partner_fees"))
                ->joinWith(['order', 'restaurant'])
                ->filterPaid()
                ->groupBy('order.currency_code')
                ->andWhere(['restaurant.country_id' => $this->country_id])
                //->andWhere(['order.shipping_country_id' => $this->country_id])
                ->asArray()
                //->cache($cacheDuration)
                ->all();

        }, $cacheDuration, $cacheDependency);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders($modelClass = "\common\models\Order")
    {
        return $this->hasMany($modelClass::className(), ['shipping_country_id' => 'country_id']);
    }

    /**
     * Gets query for [[States]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStates($modelClass = "\common\models\State")
    {
        return $this->hasMany($modelClass::className(), ['country_id' => 'country_id']);
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
        return $this->hasMany($modelClass::className(), ['country_id' => 'country_id'])
            ->andWhere(['delivery_zone.is_deleted' => 0]);
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
     * Gets query for [[Areas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAreaDeliveryZones($modelClass = "\common\models\AreaDeliveryZone")
    {
        return $this->hasMany($modelClass::className(), ['country_id' => 'country_id']);
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

    /**
     * @param $modelClass
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency($modelClass = "\common\models\Currency")
    {
        return $this->hasOne($modelClass::className(), ['code' => 'currency_code']);
    }

    /**
     * Gets query for [[Payments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayments($modelClass = "\common\models\Payment")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->via('restaurants');
    }
}

