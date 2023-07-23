<?php


namespace common\models\query;


use common\models\Order;

class OrderItemQuery extends \yii\db\ActiveQuery
{
    /**
     * filter items with shipping
     * @return void
     */
    public function filterShipping() {
        $this->andWhere (['order_item.shipping' => true]);
    }

    /**
     * Active records only
     */
    public function activeOrders($storeUuid = null)
    {
        if($storeUuid) {
            $this->andWhere (['order.restaurant_uuid' => $storeUuid]);
        }

        return $this->andWhere ([
                'NOT IN',
                'order.order_status',
                [
                    Order::STATUS_DRAFT,
                    Order::STATUS_ABANDONED_CHECKOUT,
                    Order::STATUS_REFUNDED,
                    Order::STATUS_PARTIALLY_REFUNDED,
                    Order::STATUS_CANCELED
                ]
            ]);
    }
}