<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\AgentAssignment;

/* @var $this yii\web\View */
/* @var $model common\models\AgentAssignment */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = $model->agent->agent_name;
$this->params['breadcrumbs'][] = ['label' => 'Assigned Agents', 'url' => ['index', 'storeUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="agent-assignment-view">

    <p>

        <?php
        if ($model->agent_id != Yii::$app->user->identity->agent_id) {
            echo Html::a('Update', ['update', 'assignment_id' => $model->assignment_id, 'agent_id' => $model->agent_id, 'storeUuid' => $model->restaurant_uuid], ['class' => 'btn btn-primary', 'style' => 'margin-right: 5px;']);


            echo Html::a('Delete', ['delete', 'assignment_id' => $model->assignment_id, 'agent_id' => $model->agent_id, 'storeUuid' => $model->restaurant_uuid], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]);
        }
        ?>

    </p>
    <div class="card">
        <div class="card-body">
            <div class="box-body table-responsive no-padding">
                <?=
                DetailView::widget([
                    'model' => $model,
                    'attributes' => [
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
                                return $data->businessLocation->business_location_name;
                            },
                            'visible' => $model->business_location_id != null
                        ],
                        'assignment_agent_email:email',
                        'assignment_created_at',
                        'assignment_updated_at',
                    ],
                    'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
                ])
                ?>
            </div>
        </div>
    </div>




</div>
