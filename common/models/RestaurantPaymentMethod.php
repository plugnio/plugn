<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "restaurant_payment_method".
 *
 * @property string $restaurant_uuid
 * @property int $payment_method_id
 * @property int $status
 *
 * @property PaymentMethod $paymentMethod
 * @property Restaurant $restaurant
 */
class RestaurantPaymentMethod extends \yii\db\ActiveRecord {

    const STATUS_ACTIVE = '1';
    const STATUS_INACTIVE = '0';

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return 'restaurant_payment_method';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['restaurant_uuid', 'payment_method_id'], 'required'],
            [['payment_method_id','status'], 'integer'],
            [['restaurant_uuid'], 'string', 'max' => 60],
            [['restaurant_uuid', 'payment_method_id'], 'unique', 'targetAttribute' => ['restaurant_uuid', 'payment_method_id']],
            [['payment_method_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentMethod::className(), 'targetAttribute' => ['payment_method_id' => 'payment_method_id']],
            [['restaurant_uuid'], 'exist', 'skipOnError' => true, 'targetClass' => Restaurant::className(), 'targetAttribute' => ['restaurant_uuid' => 'restaurant_uuid']],
        ];
    }

    public function extraFields()
    {
        return [
            'paymentMethod'
        ];
    }

    /**
     * @return query\RestaurantPaymentMethodQuery
     */
    public static function find() {
        return new query\RestaurantPaymentMethodQuery(get_called_class());
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'restaurant_uuid' => Yii::t('app','Restaurant Uuid'),
            'payment_method_id' => Yii::t('app','Payment Method ID'),
            'status' => Yii::t('app','Status'),
        ];
    }

    /**
     * @param $insert
     * @param $changedAttributes
     * @return bool
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

            $isCODEnabled = RestaurantPaymentMethod::find()
                ->joinWith(['paymentMethod'])
                ->andWhere([
                    'restaurant_payment_method.restaurant_uuid' => $this->restaurant_uuid,
                    'payment_method_code' => PaymentMethod::CODE_CASH
                ])
                ->exists();

            $isFreeCheckoutnabled = RestaurantPaymentMethod::find()
                ->joinWith(['paymentMethod'])
                ->andWhere([
                    'restaurant_payment_method.restaurant_uuid' => $this->restaurant_uuid,
                    'payment_method_code' => PaymentMethod::CODE_FREE_CHECKOUT
                ])
                ->exists();

            $isStripeEnabled = RestaurantPaymentMethod::find()
                ->joinWith(['paymentMethod'])
                ->andWhere([
                    'restaurant_payment_method.restaurant_uuid' => $this->restaurant_uuid,
                    'payment_method_code' => PaymentMethod::CODE_STRIPE
                ])
                ->exists();

            $isMoyasarEnabled = RestaurantPaymentMethod::find()
                ->joinWith(['paymentMethod'])
                ->andWhere([
                    'restaurant_payment_method.restaurant_uuid' => $this->restaurant_uuid,
                    'payment_method_code' => PaymentMethod::CODE_MOYASAR
                ])
                ->exists();

            Yii::$app->eventManager->track('Payment Method Added',  [
                "tap_payment_enabled" => $this->restaurant->is_tap_enable,
                "tap_plugn_commission" => $this->restaurant->platform_fee,

                "moyasar_payment_enabled" => $isMoyasarEnabled,
                "moyasr_plugn_commission" => $this->restaurant->platform_fee,

                "stripe_payment_enabled" => $isStripeEnabled,
                "stripe_plugn_commission" => $this->restaurant->platform_fee,

                "cod_enabled" => $isCODEnabled,

                "free_checkout_enabled" => $isFreeCheckoutnabled,
            ],
                null,
                $this->restaurant_uuid
            );

            //check if first product

            $count = self::find()
                ->andWhere(['restaurant_uuid' => $this->restaurant_uuid])
                ->count();

            if($count == 1) {
                Yii::$app->eventManager->track('Store Setup Step Complete', [
                    'step_name' => "Payment Method Added",
                    'step_number' => 4
                ], null, $this->restaurant_uuid);
            }

        return true;
    }

    /**
     * Gets query for [[PaymentMethod]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod($modelClass = "\common\models\PaymentMethod") {
        return $this->hasOne($modelClass::className(), ['payment_method_id' => 'payment_method_id']);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\common\models\Restaurant") {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

}
