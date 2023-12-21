<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\AgentAssignment;

/* @var $this yii\web\View */
/* @var $model common\models\AgentAssignment */

$this->title = $model->agent->agent_name;
$this->params['breadcrumbs'][] = ['label' => 'Agent Assignments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="agent-assignment-view">

    <h1><?= Html::encode($this->title) ?></h1>


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->assignment_id], ['class' => 'btn btn-primary btn-update']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->assignment_id], [
            'class' => 'btn btn-danger btn-delete',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'restaurant.name',
            'agent.agent_name',
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
            [
                'attribute' => 'business_location_id',
                'value' => function ($data) {
                    if($data->businessLocation)
                        return $data->businessLocation->business_location_name;
                },
                'visible' => $model->businessLocation != null
            ],
            [
                'attribute' => 'email_notification',
                'value' => $model->email_notification ? 'Yes' : 'No',
            ],
            [
                'attribute' => 'receive_weekly_stats',
                'value' => $model->receive_weekly_stats ? 'Yes' : 'No',
            ],
            [
                'attribute' => 'reminder_email',
                'value' => $model->reminder_email ? 'Yes' : 'No',
            ],
            'assignment_agent_email:email',
            'assignment_created_at',
            'assignment_updated_at',


        ],
    ])
    ?>

    <h3>Agent</h3>


    <?= DetailView::widget([
        'model' => $model->agent,
        'attributes' => [
             'agent_id',
            'agent_name',
            'agent_email:email',
            [
                'label' => 'Password',
                'value' => '***',
            ],
            [
                'label' => 'Status',
                'value' => $model->agent->status,
            ],
            'agent_email_verification',
            'agent_number',
            'agent_phone_country_code',
            //'email_notification',
            //'reminder_email',
            'agent_language_pref',
            //'receive_weekly_stats',
            'ip_address',
            'last_active_at:datetime',
            'deleted',
            'agent_created_at:datetime',
            'agent_updated_at:datetime',

        ],
    ]) ?>

</div>
