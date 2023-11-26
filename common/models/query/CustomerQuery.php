<?php

namespace common\models\query;

use yii\db\Expression;
use common\models\Customer;

/**
 * CustomerQuery extends ActiveQuery, allowing easier filtering of customers
 */
class CustomerQuery extends \yii\db\ActiveQuery {

    /**
     * @inheritdoc
     * @return Agent[]|array
     */
    public function all($db = null)
    {
        $this->andWhere(['!=', 'customer.deleted', 1]);
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Agent|array|null
     */
    public function one($db = null)
    {
        $this->andWhere(['!=', 'customer.deleted', 1]);
        return parent::one($db);
    }

    public function customerGained($storeUuid, $start_date, $end_date )
    {
        return $this
            ->andWhere(['restaurant_uuid' => $storeUuid])
            ->andWhere(['between', 'customer_created_at', $start_date, $end_date])
            ->count();
    }
}
