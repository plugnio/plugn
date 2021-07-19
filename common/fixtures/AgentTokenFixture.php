<?php
namespace common\fixtures;

use yii\test\ActiveFixture;

class AgentTokenFixture extends ActiveFixture
{
    public $modelClass = 'common\models\AgentToken';

    public $depends = [
        'common\fixtures\AgentFixture'
    ];
}
