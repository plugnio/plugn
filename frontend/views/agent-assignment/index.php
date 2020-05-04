<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $restaurant_uuid;

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
            'restaurant_uuid',
            'agent_id',
            'assignment_agent_email:email',
            'assignment_created_at',
            //'assignment_updated_at',
            //'role',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
