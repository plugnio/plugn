<?php
namespace common\fixtures;

use yii\test\ActiveFixture;

class AgentAssignmentFixture extends ActiveFixture
{
    public $modelClass = 'common\models\AgentAssignment';

    public $depends = [
        'common\fixtures\AgentFixture',
        'common\fixtures\RestaurantFixture',
    ];
}
