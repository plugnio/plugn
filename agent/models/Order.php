<?php


namespace agent\models;

use Yii;

/**
 * This is the model class for table "Order".
 * It extends from \common\models\Order but with custom functionality for Order module
 *
 */
class Order extends \common\models\Order
{
    public function fields()
    {
        $fields = parent::fields();

        $fields['status_txt'] = function ($model) {
            return $model->orderStatusInEnglish;
        };

        $fields['total_price_txt'] = function ($model) {
            if ($model->currency)
                return Yii::$app->formatter->asCurrency($model->total_price, $model->currency->code, [
                    \NumberFormatter::MAX_FRACTION_DIGITS => $model->currency->decimal_place
                ]);
            else
                return $model->total_price;
        };

        $fields['delivery_fee_txt'] = function ($model) {
            if ($model->currency)
                return Yii::$app->formatter->asCurrency($model->delivery_fee, $model->currency->code, [
                    \NumberFormatter::MAX_FRACTION_DIGITS => $model->currency->decimal_place
                ]);
            else
                return $model->delivery_fee;
        };

        /*$fields['payment_txt'] =  function($model) {
            return ($model->payment_uuid) ?
                $model->payment->payment_current_status :
                $model->paymentMethod->payment_method_name;
        };*/

        return $fields;
    }

    /**
     * @inheritdoc
     */
    public function extraFields()
    {
        return [
            'orderStatusInEnglish',
            'orderStatusInArabic',
            'restaurant',
            'restaurantBranch',
            'deliveryZone',
            'pickupLocation',
            'businessLocation',
            'payment',
            'orderItems',
            'totalOrderItems',
            'voucher',
            'bankDiscount',
            'refunds',
            'area',
            'currency',
            'customer',
            'itemImage'//first image in first order item 
        ];
    }

    /**
     * Gets query for [[Country]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCountry($modelClass = "\agent\models\Country")
    {
        return parent::getCountry($modelClass);
    }

    /**
     * Gets query for [[DeliveryZone]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeliveryZone($modelClass = "\agent\models\DeliveryZone")
    {
        return parent::getDeliveryZone($modelClass);
    }

    /**
     * Gets query for [[BankDiscount]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBankDiscount($modelClass = "\agent\models\BankDiscount")
    {
        return parent::getBankDiscount($modelClass);
    }

    /**
     * Gets query for [[Restaurant]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurant($modelClass = "\agent\models\Restaurant")
    {
        return parent::getRestaurant($modelClass);
    }

    /**
     * Gets query for [[Currency]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency($modelClass = "\agent\models\Currency")
    {
        return parent::getCurrency($modelClass);
    }

    /**
     * Gets query for [[RestaurantDelivery]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantDelivery($modelClass = "\agent\models\RestaurantDelivery")
    {
        return parent::getRestaurantDelivery($modelClass);
    }

    /**
     * Gets query for [[Area]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getArea($modelClass = "\agent\models\Area")
    {
        return parent::getArea($modelClass);
    }

    /**
     * Gets query for [[PaymentMethod]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentMethod($modelClass = "\agent\models\PaymentMethod")
    {
        return parent::getPaymentMethod($modelClass);
    }

    /**
     * Gets query for [[OrderItemExtraOption]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItemExtraOptions($modelClass = "\agent\models\OrderItemExtraOption")
    {
        return parent::getOrderItemExtraOptions($modelClass);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderItems($modelClass = "\agent\models\OrderItem")
    {
        return parent::getOrderItems($modelClass);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTotalOrderItems($modelClass = "\agent\models\OrderItem")
    {
        return parent::getOrderItems($modelClass)->count();
    }


    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSelectedItems($modelClass = "\agent\models\OrderItem")
    {
        return parent::getSelectedItems($modelClass);
    }

    /**
     * Gets query for [[Items]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItems($modelClass = "\agent\models\Item")
    {
        return parent::getItems($modelClass);
    }

    public function getItemImage($modelClass = "\agent\models\ItemImage")
    {
        return parent::getItemImage($modelClass);
    }

    /**
     * Gets query for [[Customer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomer($modelClass = "\agent\models\Customer")
    {
        return parent::getCustomer($modelClass);
    }

    /**
     * Gets query for [[RestaurantBranch]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRestaurantBranch($modelClass = "\agent\models\RestaurantBranch")
    {
        return parent::getRestaurantBranch($modelClass);
    }

    /**
     * Gets query for [[PaymentUu]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPayment($modelClass = "\agent\models\Payment")
    {
        return parent::getPayment($modelClass);
    }

    /**
     * Gets query for [[Refunds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefunds($modelClass = "\agent\models\Refund")
    {
        return parent::getRefunds($modelClass);
    }

    /**
     * Gets query for [[BusinessLocation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessLocation($modelClass = "\agent\models\BusinessLocation")
    {
        return parent::getBusinessLocation($modelClass);
    }

    /**
     * Gets query for [[PickupLocation]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPickupLocation($modelClass = "\agent\models\BusinessLocation")
    {
        return parent::getPickupLocation($modelClass);
    }

    /**
     * Gets query for [[Voucher]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVoucher($modelClass = "\agent\models\Voucher")
    {
        return parent::getVoucher($modelClass);
    }

    /**
     * Gets query for [[RefundedItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefundedItems($modelClass = "\agent\models\RefundedItem")
    {
        return parent::getRefundedItems($modelClass);
    }
}
