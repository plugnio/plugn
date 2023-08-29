<?php

namespace common\models\query;

use yii\db\Expression;

class RestaurantDomainRequestQuery extends \yii\db\ActiveQuery
{
    public function filterByDateRange($date_start, $date_end) {
        if(!$date_start || !$date_end) {
            return $this;
        }

        $start = date('Y-m-d', strtotime($date_start));
        $end = date('Y-m-d', strtotime($date_end));

        if($start == $end) {
            return $this->andWhere(new Expression("DATE(created_at) = DATE('".$start."')"));
        }

        return $this->andWhere(new Expression("DATE(created_at) >= DATE('".$start."')
            AND DATE(created_at) <= DATE('".$end."')"));

        //return $this->andWhere(['between', 'created_at', $start, $end]);
    }
}