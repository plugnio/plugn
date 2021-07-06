<?php

namespace common\models\query;

use yii\db\Expression;
use common\models\Order;

/**
 * OrderQuery extends ActiveQuery, allowing easier filtering of orders
 */
class OrderQuery extends \yii\db\ActiveQuery
{

    /**
     * @inheritdoc
     * @return Agent[]|array
     */
    public function all($db = null)
    {
        return parent::all ($db);
    }

    /**
     * @inheritdoc
     * @return Agent|array|null
     */
    public function one($db = null)
    {
        return parent::one ($db);
    }

    /**
     * Orders successfully placed
     */
    public function checkoutCompleted()
    {
        return $this->filterWhere (['NOT IN', 'order_status', [Order::STATUS_ABANDONED_CHECKOUT, Order::STATUS_DRAFT]]);
    }

    /**
     * Active records only
     */
    public function activeOrders($storeUuid)
    {
        return $this->where (['order.restaurant_uuid' => $storeUuid])
            ->andWhere (['!=', 'order_status', Order::STATUS_DRAFT])
            ->andWhere (['!=', 'order_status', Order::STATUS_ABANDONED_CHECKOUT])
            ->andWhere (['!=', 'order_status', Order::STATUS_REFUNDED])
            ->andWhere (['!=', 'order_status', Order::STATUS_PARTIALLY_REFUNDED])
            ->andWhere (['!=', 'order_status', Order::STATUS_CANCELED]);
    }

    /**
     * Get revenueGenerated for all active orders
     */
    public function revenueGenerated($storeUuid, $start_date, $end_date)
    {
        return $this->activeOrders ($storeUuid)
            ->andWhere (['between', 'order_created_at', $start_date, $end_date])
            ->sum ('total_price');
    }

    /**
     * Active records only
     */
    public function ordersReceived($storeUuid, $start_date, $end_date)
    {
        return $this->activeOrders ($storeUuid)
            ->andWhere (['between', 'order_created_at', $start_date, $end_date])
            ->count ();
    }

    public function filterByCreatedDate($date_range){
        // do we have values? if so, add a filter to our query
        if (!empty($date_range) && strpos($date_range, '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $date_range);
            return $this->andFilterWhere(['between', 'order_created_at', $start_date, $end_date]);
        }
    }

}
