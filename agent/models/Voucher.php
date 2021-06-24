<?php


namespace agent\models;


class Voucher extends \common\models\Voucher
{

    public function fields()
    {
        $field = parent::fields();
        $field['redeemed_txt'] = function($model) {
            return sizeof($model->activeOrders);
        };

        $field['total_spent_txt'] = function($model) {
            $totalSpent = $model->getOrders()->andWhere(['restaurant_uuid' => $model->restaurant_uuid])->sum('total_price');

            return  \Yii::$app->formatter->asCurrency($totalSpent ? $totalSpent : 0, $model->restaurant->currency->code) ;
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
