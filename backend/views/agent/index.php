<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Agents';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agent-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Agent', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'agent_name',
            'agent_email:email',
            // 'agent_auth_key',
            // 'agent_password_hash',
            //'agent_password_reset_token',
            // 'agent_status',
            'agent_created_at',
            'agent_updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
