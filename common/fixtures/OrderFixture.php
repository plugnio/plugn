<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class OrderFixture extends ActiveFixture {

    public $modelClass = 'common\models\Order';
    public $depends = [
        'common\fixtures\ExtraOptionFixture',
        'common\fixtures\RestaurantDeliveryFixture',
    ];

}
