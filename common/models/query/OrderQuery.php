<?php

namespace common\models\query;

use Yii;
use agent\models\AgentAssignment;
use agent\models\DeliveryZone;
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
     * if branch manager show order only of that branch
     * @param $store_uuid
     * @return $this
     */
    public function filterBusinessLocationIfManager($store_uuid) {

        $assignment = Yii::$app->accountManager->getAssignment ($store_uuid);

        if($assignment->role == AgentAssignment::AGENT_ROLE_BRANCH_MANAGER)
        {
            $deliveryZoneQuery = DeliveryZone::find()
                ->select('delivery_zone_id')
                ->where(['business_location_id' => $assignment->business_location_id]);

            $this->andWhere([
                'OR',
                ['in', 'delivery_zone_id', $deliveryZoneQuery],
                ['pickup_location_id' => $assignment->business_location_id]
            ]);
        }

        return $this;
    }

    /**
     * Orders successfully placed
     */
    public function checkoutCompleted()
    {
        return $this->andWhere (['NOT IN', 'order_status', [Order::STATUS_ABANDONED_CHECKOUT, Order::STATUS_DRAFT]]);
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
                'order_status',
                [
                    Order::STATUS_DRAFT,
                    Order::STATUS_ABANDONED_CHECKOUT,
                    Order::STATUS_REFUNDED,
                    Order::STATUS_PARTIALLY_REFUNDED,
                    Order::STATUS_CANCELED
                ]
            ]);
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
