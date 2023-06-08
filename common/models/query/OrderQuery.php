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
        $this->andWhere(['!=', 'order.is_deleted', 1]);
        return parent::all ($db);
    }

    /**
     * @inheritdoc
     * @return Agent|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(['!=', 'order.is_deleted', 1]);
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
                ->where(['delivery_zone.business_location_id' => $assignment->business_location_id]);

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
     * filter by keyword
     * @param $keyword
     * @return OrderQuery
     */
    public function filterByKeyword($keyword)
    {
        if(!$keyword) {
            return $this;
        }

        return $this->andWhere (
            [
                'or',
                ['like', 'business_location_name', $keyword],
                ['like', 'payment_method_name', $keyword],
                ['like', 'order_uuid', $keyword],
                ['like', 'customer_name', $keyword],
                ['like', 'customer_phone_number', $keyword],
            ]
        );
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
     * @return OrderQuery
     */
    public function placedOrders()
    {
        return $this->andWhere ([
                'NOT IN',
                'order_status',
                [
                    Order::STATUS_DRAFT,
                    Order::STATUS_ABANDONED_CHECKOUT,
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

    public function filterByCreatedDate($date_range) {
        // do we have values? if so, add a filter to our query
        if (!empty($date_range) && strpos($date_range, '-') !== false) {
            list($start_date, $end_date) = explode(' - ', $date_range);
            return $this->andFilterWhere(['between', 'order_created_at', $start_date, $end_date]);
        }
    }

    public function filterByDateRange($date_start, $date_end) {

        if(!$date_start || !$date_end) {
            return $this;
        }

        $start = date('Y-m-d', strtotime($date_start));
        $end = date('Y-m-d', strtotime($date_end));

        //return $this->andWhere(['between', 'payment_created_at', $start, $end]);

        return $this->andWhere(new Expression("DATE(order_created_at) >= DATE('".$start."')
            AND DATE(order_created_at) =< DATE('".$end."')"));

        //return $this->andWhere(['between', 'order_created_at', $start, $end]);
    }

    /**
     * live orders records only
     */
    public function liveOrders()
    {
        return $this->andWhere ([
            'IN',
            'order_status',
            [
                Order::STATUS_PENDING,
                Order::STATUS_ACCEPTED,
                Order::STATUS_BEING_PREPARED
            ]
        ]);
    }

    /**
     * archive orders
     * @return OrderQuery
     */
    public function archiveOrders()
    {
        return $this->andWhere ([
            'IN',
            'order_status',
            [
                Order::STATUS_OUT_FOR_DELIVERY,
                Order::STATUS_COMPLETE
            ]
        ]);
    }

    public function filterByCountry($country_id) {
        if(!$country_id) {
            return $this;
        }

        return $this->joinWith(['restaurant'])
            ->andWhere (['restaurant.country_id'=> $country_id]);
    }

    /**
     * Orders successfully placed
     */
    public function notDeleted()
    {
        return $this->andWhere (['order.is_deleted'=>'0']);
    }

}
