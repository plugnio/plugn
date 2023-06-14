<?php

namespace common\models\query;

use common\models\Plan;
use common\models\Subscription;
use yii\db\Expression;

class SubscriptionQuery extends \yii\db\ActiveQuery
{
    public function filterPremium()
    {
        return $this
            ->andWhere(['IN', 'plan_id', Plan::find()->select('plan_id')->andWhere(['>', 'price', 0])])
            ->andWhere([
                'AND',
                ['subscription_status' => Subscription::STATUS_ACTIVE],
                new Expression('subscription_end_at IS NULL || DATE(subscription_end_at) >= DATE(NOW())')
            ]);
    }
}