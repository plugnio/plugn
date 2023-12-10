<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use agent\models\AgentAssignment;

/* @var $this yii\web\View */
/* @var $model common\models\Agent */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel yii\data\ActiveDataProvider */

$this->title = $model->agent_name;
$this->params['breadcrumbs'][] = ['label' => 'Agents', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agent-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->agent_id], ['class' => 'btn btn-primary btn-update']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->agent_id], [
            'class' => 'btn btn-danger btn-delete',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>


        <?= Html::a('Remove Store Assignments', ['delete-assignments', 'id' => $model->agent_id], [
            'class' => 'btn btn-danger btn-delete',
            'data' => [
                'confirm' => 'Are you sure you want to remove agent from all stores?',
                'method' => 'post',
            ],
        ]) ?>

    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            // 'agent_id',
            'agent_name',
            'agent_email:email',
            [
                'label' => 'Password',
                'value' => '***',
            ],
            [
                'label' => 'Status',
                'value' => $model->status,
            ],
            'agent_email_verification',
            'email_notification',
            'agent_language_pref',
            'receive_weekly_stats',
            'ip_address',
            'agent_created_at:datetime',
            'agent_updated_at:datetime',
        ],
    ]) ?>

    <h3>Agent's stores</h3>

    <?= \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'store_name',
                'value' =>     'restaurant.name'
            ],
            [
                'attribute' => 'agent_name',
                'value' =>     'agent.agent_name'
            ],

            [
                'attribute' => 'role',
                'format' => 'html',
                'value' => function ($data) {
                    if($data->role == AgentAssignment::AGENT_ROLE_OWNER)
                        $role = 'Owner';
                    else  if($data->role == AgentAssignment::AGENT_ROLE_BRANCH_MANAGER)
                        $role = 'Branch Manager';
                    else
                        $role = 'Staff';

                    return $role;
                },
            ],
            'assignment_agent_email:email',
            'assignment_created_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
