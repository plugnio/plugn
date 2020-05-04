<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\AgentAssignment;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Agent Assignments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agent-assignment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Agent Assignment', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'assignment_id',
            'restaurant.name',
            'agent.agent_name',
            [
                'attribute' => 'role',
                'format' => 'html',
                'value' => function ($data) {
                    return $data->role == AgentAssignment::AGENT_ROLE_OWNER ? 'Owner' : 'Staff';
                },
            ],
            'assignment_agent_email:email',
            'assignment_created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
