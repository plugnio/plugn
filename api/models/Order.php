<?php


namespace api\models;

use Yii;

/**
 * This is the model class for table "Order".
 * It extends from \common\models\Order but with custom functionality for Order module
 *
 */
class Order extends \common\models\Order
{
    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = parent::fields ();

        // remove fields that contain sensitive information
        unset($fields['armada_order_status']);
        unset($fields['armada_delivery_code']);
        unset($fields['mashkor_order_number']);
        unset($fields['mashkor_tracking_link']);
        unset($fields['mashkor_driver_name']);
        unset($fields['mashkor_driver_phone']);
        unset($fields['mashkor_order_status']);
        unset($fields['armada_tracking_link']);
        unset($fields['reminder_sent']);
        unset($fields['sms_sent']);
        unset($fields['items_has_been_restocked']);
        unset($fields['subtotal_before_refund']);
        unset($fields['total_price_before_refund']);

        return $fields;

    }
}
