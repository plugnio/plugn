<?php


namespace agent\models;

use Yii;

/**
 * This is the model class for table "Order".
 * It extends from \common\models\Order but with custom functionality for Order module
 *
 */
class Order extends \common\models\Order {

  /**
   * @inheritdoc
   */
  public function extraFields()
  {
    return [
        'orderStatusInEnglish',
        'orderStatusInArabic',
        'restaurant',
        /*'orderItems' => function($order){
            return $order->getOrderItems()->with('orderItemExtraOptions')->asArray()->all();
        },*/
        'restaurantBranch',
        'deliveryZone',
        'pickupLocation',
        'payment',
        'orderItems',
        'voucher',
        'bankDiscount',
        'refunds',
        'area'
    ];
  }


}
