<?php

namespace common\models\query;

class PaymentQuery extends \yii\db\ActiveQuery
{
    public function filterByDateRange($date_start, $date_end) {

        if(!$date_start || !$date_end) {
            return $this;
        }

        $start = date('Y-m-d', strtotime($date_start));
        $end = date('Y-m-d', strtotime($date_end));

        return $this->andWhere(['between', 'payment_created_at', $start, $end]);
    }

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

    public function filterByCountry($country_id) {
        if(!$country_id) {
            return $this;
        }

        return $this->joinWith(['restaurant'])
            ->andWhere (['restaurant.country_id' => $country_id]);
    }
}
