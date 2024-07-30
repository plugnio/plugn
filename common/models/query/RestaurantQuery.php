<?php

namespace common\models\query;

use common\models\PaymentMethod;
use common\models\Plan;
use common\models\Subscription;
use yii\db\Expression;

class RestaurantQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return Agent[]|array
     */
    public function all($db = null)
    {
        //$this->andWhere(['!=', 'restaurant.is_deleted', 1]);
        return parent::all ($db);
    }

    /**
     * @inheritdoc
     * @return Agent|array|null
     */
    public function one($db = null)
    {
        //$this->andWhere(['!=', 'restaurant.is_deleted', 1]);
        return parent::one ($db);
    }

    public function filterByNoOrderIn30Days()
    {
        return $this->andWhere (new Expression("restaurant.last_order_at IS NULL OR 
            DATE(restaurant.last_order_at) < DATE(NOW() - INTERVAL 30 DAY)"));
    }

    public function filterByNoOrderInDays($days)
    {
        return $this->andWhere (new Expression("restaurant.last_order_at IS NULL OR 
            DATE(restaurant.last_order_at) < DATE(NOW() - INTERVAL ".$days." DAY)"));
    }

    public function filterByOrderInDays($days)
    {
        return $this->andWhere (new Expression("DATE(restaurant.last_order_at) > DATE(NOW() - INTERVAL ".($days - 1)." DAY)"));
    }

    /**
     * @return RestaurantQuery
     */
    public function filterNotPublished() {
        return $this
            //->andWhere(['not like', 'restaurant_domain', "%.site%"])
            ->andWhere(['has_deployed' => 0, 'restaurant.is_deleted' => 0]);
        //->andWhere(['<', 'version', 5])
    }

    /**
     * no order & no item
     * @param null $db
     * @return RestaurantQuery
     */
    public function inActive($db = null)
    {
        return $this
            ->joinWith(['items'])
            ->andWhere( new Expression("item_uuid IS NULL AND last_order_at IS NULL"));
    }

    /**
     * @param $db
     * @return RestaurantQuery
     */
    public function active($db = null)
    {
        return $this
            ->joinWith(['items'])
            ->andWhere( new Expression("item_uuid IS NOT NULL OR last_order_at IS NOT NULL"));
    }

    public function filterByDateRange($date_start, $date_end) {
        if(!$date_start || !$date_end) {
            return $this;
        }

        $start = date('Y-m-d', strtotime($date_start));
        $end = date('Y-m-d', strtotime($date_end));

        if($start == $end) {
            return $this->andWhere(new Expression("DATE(restaurant_created_at) = DATE('".$start."')"));
        }
        
        return $this->andWhere(new Expression("DATE(restaurant_created_at) >= DATE('".$start."')
            AND DATE(restaurant_created_at) <= DATE('".$end."')"));

        //return $this->andWhere(['between', 'restaurant_created_at', $start, $end]);
    }

    public function filterPremium()
    {
        return $this
            ->joinWith(['subscriptions'])
            ->andWhere(['IN', 'plan_id', Plan::find()->select('plan_id')->andWhere(['>', 'price', 0])])
            ->andWhere([
                'AND',
                ['subscription_status' => Subscription::STATUS_ACTIVE],
                new Expression('subscription_end_at IS NULL || DATE(subscription_end_at) >= DATE(NOW())')
            ]);
    }

    public function filterStoresWithPaymentGateway()
    {
        $subQuery = PaymentMethod::find()
            ->select(['payment_method_id'])
            ->andWhere(['IN', 'payment_method_code', [
                PaymentMethod::CODE_CASH,
                PaymentMethod::CODE_FREE_CHECKOUT
            ]])
            ->all();

        return $this->joinWith(['restaurantPaymentMethods'])
            //->andWhere(new Expression("restaurant_payment_method.payment_method_id IS NOT NULL"))
            ->andWhere(['restaurant_payment_method.status' => 1])
            ->andWhere(['NOT IN', 'restaurant_payment_method.payment_method_id', $subQuery]);
            //->andWhere(["is_tap_enable" => true]);
    }

    public function filterByCountry($country_id) {
        if(!$country_id) {
            return $this;
        }

        return $this->andWhere (['restaurant.country_id' => $country_id]);
    }

    public function filterPlugnDomain($db = null)
    {
        return $this
            ->andWhere( new Expression("restaurant_domain like '%.plugn.store%' OR restaurant_domain like '%.plugn.site%'"));
    }

    public function filterCustomDomain($db = null)
    {
        return $this
            ->andWhere( new Expression("restaurant_domain not like '%.plugn.store%' AND restaurant_domain not like '%.plugn.site%'"));
    }
}