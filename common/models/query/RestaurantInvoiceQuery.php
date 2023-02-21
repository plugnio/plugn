<?php

namespace common\models\query;

use common\models\RestaurantInvoice;

class RestaurantInvoiceQuery extends \yii\db\ActiveQuery
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

    public function notPaid()
    {
        return $this->andWhere(['!=', 'invoice_status', RestaurantInvoice::STATUS_PAID]);
    }

    public function unpaid()
    {
        return $this->andWhere(['invoice_status' => RestaurantInvoice::STATUS_UNPAID]);
    }

    public function locked()
    {
        return $this->andWhere(['invoice_status' => RestaurantInvoice::STATUS_LOCKED]);
    }
}