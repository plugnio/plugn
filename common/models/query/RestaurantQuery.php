<?php

namespace common\models\query;

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
}