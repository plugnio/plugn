<?php

namespace common\models\query;

use common\models\PaymentMethod;
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

    public function filterPremium()
    {
        return $this
            ->joinWith(['subscriptions'])
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

    public function filterPlugnDomain($db = null)
    {
        return $this
            ->andWhere( new Expression("restaurant_domain like '%.plugn.store%'"));
    }

    public function filterCustomDomain($db = null)
    {
        return $this
            ->andWhere( new Expression("restaurant_domain not like '%.plugn.store%'"));
    }
}