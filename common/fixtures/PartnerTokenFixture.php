<?php

namespace common\fixtures;

use yii\test\ActiveFixture;

class PartnerTokenFixture extends ActiveFixture
{
    public $modelClass = 'common\models\PartnerToken';

    public $depends = [
        'common\fixtures\PartnerFixture'
    ];
}