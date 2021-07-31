<?php


namespace agent\models;


class Voucher extends \common\models\Voucher
{

    public function fields()
    {
        $field = parent::fields();

        $field['redeemed'] = function($model) {
            return $model->getActiveOrders()->count();
        };

        /**
         * todo: refactor with https://www.pivotaltracker.com/story/show/178910985
         * - should show correct value for old data
         * @param $model
         * @return string
         * @throws \yii\base\InvalidConfigException
         */
        $field['totalSpent'] = function($model) {

            $totalSpent = 0;

            if($model->discount_type == self::DISCOUNT_TYPE_PERCENTAGE)
            {
                $totalSpent = $model->getOrders()
                    ->sum('subtotal_before_refund * ' . $model->discount_amount/100);
            }
            else if($model->discount_type == self::DISCOUNT_TYPE_AMOUNT)
            {
                $totalSpent = $model->discount_amount * $model->getOrders()->count();
            }
            else if($model->discount_type == self::DISCOUNT_TYPE_FREE_DELIVERY)
            {
                $totalSpent = $model->getOrders()
                    ->sum('delivery_fee');
            }

            if(!$totalSpent) {
                $totalSpent = 0;
            }

            return  \Yii::$app->formatter->asCurrency(
                $totalSpent,
                $model->restaurant->currency->code,
                [
                    \NumberFormatter::MIN_FRACTION_DIGITS => 0,
                    \NumberFormatter::MAX_FRACTION_DIGITS => 3  ,
                ]
            ) ;
        };

        return $field;
    }

    /**
     * Gets query for [[CustomerVouchers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerVouchers($modelClass = "\agent\models\CustomerVoucher") {
        return $this->hasMany($modelClass::className(), ['voucher_id' => 'voucher_id']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrders($modelClass = "\agent\models\Order") {
        return $this->hasMany($modelClass::className(), ['voucher_id' => 'voucher_id']);
    }

    /**
     * Gets query for [[Orders]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActiveOrders($modelClass = "\agent\models\Order") {
        return $this->hasMany($modelClass::className(), ['voucher_id' => 'voucher_id'])
            ->activeOrders($this->restaurant_uuid);
    }

    /**
     * Gets query for [[RestaurantUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\agent\models\Restaurant") {
        return $this->hasOne($modelClass::className(), ['restaurant_uuid' => 'restaurant_uuid']);
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency($modelClass = "\agent\models\Currency")
    {
        return $this->hasOne($modelClass::className(), ['currency_id' => 'currency_id'])->via('restaurant');
    }
}
