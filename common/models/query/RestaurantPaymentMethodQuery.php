<?php

namespace common\models\query;

use common\models\RestaurantPaymentMethod;

class RestaurantPaymentMethodQuery  extends \yii\db\ActiveQuery
{
    public function filterByCountry($country_id) {
        if(!$country_id) {
            return $this;
        }

        return $this->joinWith(['restaurant'])
            ->andWhere (['restaurant.country_id'=> $country_id]);
    }

    public function filterActive() {
        return $this->andWhere(['restaurant_payment_method.status' => RestaurantPaymentMethod::STATUS_ACTIVE]);
    }
}