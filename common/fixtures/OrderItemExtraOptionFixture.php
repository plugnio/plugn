<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class OrderItemExtraOptionFixture extends ActiveFixture {

    public $modelClass = 'common\models\OrderItemExtraOption';
    public $depends = [
        'common\fixtures\OrderItemFixture',
    ];

}
