<?php

namespace api\models;

class Customer extends \common\models\Customer
{
    public function fields()
    {
        $fields = parent::fields();

        unset($fields['customer_password_hash'],
            $fields['customer_password_reset_token'],
            $fields['restaurant_uuid'],
            $fields['deleted'],
        );

        return $fields;
    }
}