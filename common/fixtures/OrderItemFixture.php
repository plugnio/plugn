<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class OrderItemFixture extends ActiveFixture {

    public $modelClass = 'common\models\OrderItem';
    public $depends = [
        'common\fixtures\OrderFixture',
    ];

}
