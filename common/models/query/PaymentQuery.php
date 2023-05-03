<?php

namespace common\models\query;

class PaymentQuery extends \yii\db\ActiveQuery
{
    public function filterPaid()
    {
        return $this->andWhere (['IN', 'payment_current_status', [
            "CAPTURED",
            "SUCCESS",
            "PAID",
            "Success",
            "Captured",
            "Paid",
        ]]);
    }
}
