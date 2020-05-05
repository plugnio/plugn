<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\AgentAssignment;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $restaurant_uuid;

$this->title = 'Assigned Agents';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agent-assignment-index">


    <p>
        <?php
        if (AgentAssignment::isOwner($restaurant_uuid))
            echo Html::a('Invite Additional Agent', ['create','restaurantUuid' => $restaurant_uuid], ['class' => 'btn btn-success'])
        ?>
    </p>

    <div class="card">
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'agent.agent_name',
                'assignment_agent_email:email',
                [
                    'attribute' => 'role',
                    'format' => 'html',
                    'value' => function ($data) {
                        return $data->role == AgentAssignment::AGENT_ROLE_OWNER ? 'Owner' : 'Staff';
                    },
                ],
                'assignment_created_at',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => ' {view} {update} {delete}',
                    'visible' => AgentAssignment::isOwner($restaurant_uuid) ? true : false,
                    'buttons' => [
                        'view' => function ($url, $model) {
                            if ($model->agent_id != Yii::$app->user->identity->agent_id) {
                                return Html::a(
                                                '<span style="margin-right: 20px;" class="nav-icon fas fa-eye"></span>', ['view', 'assignment_id' => $model->assignment_id, 'agent_id' => $model->agent_id, 'restaurantUuid' => $model->restaurant_uuid], [
                                            'title' => 'View',
                                            'data-pjax' => '0',
                                                ]
                                );
                            }
                        },
                        'update' => function ($url, $model) {
                            if ($model->agent_id != Yii::$app->user->identity->agent_id) {
                                return Html::a(
                                                '<span style="margin-right: 20px;" class="nav-icon fas fa-edit"></span>', ['update', 'assignment_id' => $model->assignment_id, 'agent_id' => $model->agent_id, 'restaurantUuid' => $model->restaurant_uuid], [
                                            'title' => 'Update',
                                            'data-pjax' => '0',
                                                ]
                                );
                            }
                        },
                        'delete' => function ($url, $model) {
                            if ($model->agent_id != Yii::$app->user->identity->agent_id) {
                                return Html::a(
                                                '<span style="margin-right: 20px;color: red;" class="nav-icon fas fa-trash"></span>', ['delete', 'assignment_id' => $model->assignment_id, 'agent_id' => $model->agent_id, 'restaurantUuid' => $model->restaurant_uuid], [
                                            'title' => 'Delete',
                                            'data' => [
                                                'confirm' => 'Are you absolutely sure ? You will lose all the information about this agent with this action.',
                                                'method' => 'post',
                                            ],
                                ]);
                            }
                        },
                    ],
                ],
            ],
            'layout' => '{summary}<div class="card-body">{items}<div class="card-footer clearfix">{pager}</div></div>',
            'tableOptions' => ['class' => 'table table-bordered table-hover'],
            'summaryOptions' => ['class' => "card-header"],
        ]);
        ?>


    </div>
</div>
