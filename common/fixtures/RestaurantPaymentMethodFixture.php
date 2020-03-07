<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class RestaurantPaymentMethodFixture extends ActiveFixture {

    public $modelClass = 'common\models\RestaurantPaymentMethod';
    public $depends = [
        'common\fixtures\PaymentMethodFixture',
        'common\fixtures\RestaurantFixture',
    ];

}
