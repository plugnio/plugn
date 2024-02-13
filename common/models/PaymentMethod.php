<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "payment_method".
 *
 * @property int $payment_method_id
 * @property string|null $payment_method_name
 * @property string|null $payment_method_name_ar
 * @property int $vat
 * @property string $payment_method_code
 * @property string|null $source_id
 *
 * @property RestaurantPaymentMethod[] $restaurantPaymentMethods
 * @property Restaurant[] $restaurantUus
 */
class PaymentMethod extends \yii\db\ActiveRecord
{
    const CODE_FREE_CHECKOUT = 'free-checkout';
    const CODE_BENEFIT = 'benefit';
    const CODE_MADA = 'mada';
    const CODE_CASH = 'cash';
    const CODE_CREDIT_CARD = 'credit-card';
    const CODE_KNET = 'kn';
    const CODE_APPLE_PAY = 'apple-pay';
    const CODE_MOYASAR = "Moyasar";
    const CODE_STRIPE = "Stripe";
    const CODE_UPAYMENT = "UPayment";

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'payment_method';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['payment_method_name'], 'required'],
            [['payment_method_name' , 'payment_method_name_ar', 'source_id','payment_method_code'], 'string', 'max' => 255],
            [['vat'], 'number']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'payment_method_id' => Yii::t('app','Payment Method ID'),
            'payment_method_name' => Yii::t('app','Payment Method Name'),
            'payment_method_name_ar' => Yii::t('app','Payment Method Name [Arabic]'),
        ];
    }

    /**
     * Gets query for [[RestaurantPaymentMethods]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantPaymentMethods($modelClass = "\common\models\RestaurantPaymentMethod")
    {
        return $this->hasMany($modelClass::className(), ['payment_method_id' => 'payment_method_id']);
    }

    /**
     * Gets query for [[PaymentMethodCurrencies]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethodCurrencies($modelClass = "\common\models\PaymentMethodCurrency")
    {
        return $this->hasMany($modelClass::className(), ['payment_method_id' => 'payment_method_id']);
    }

    /**
     * Gets query for [[RestaurantUus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurants($modelClass = "\common\models\Restaurant")
    {
        return $this->hasMany($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid'])
            ->viaTable('restaurant_payment_method', ['payment_method_id' => 'payment_method_id']);
    }
}
