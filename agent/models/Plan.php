<?php


namespace agent\models;


class Plan extends \common\models\Plan
{
    /**
     * Gets query for [[Subscriptions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptions($modelClass = "\agent\models\Subscription")
    {
        return parent::getSubscriptions($modelClass);
    }
}