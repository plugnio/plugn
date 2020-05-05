<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\AgentAssignment;

/* @var $this yii\web\View */
/* @var $model common\models\AgentAssignment */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = $model->agent->agent_name;
$this->params['breadcrumbs'][] = ['label' => 'Assigned Agents', 'url' => ['index', 'restaurantUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="agent-assignment-view">

    <p>

        <?php
        if ($model->agent_id != Yii::$app->user->identity->agent_id) {
            echo Html::a('Update', ['update', 'assignment_id' => $model->assignment_id, 'agent_id' => $model->agent_id, 'restaurantUuid' => $model->restaurant_uuid], ['class' => 'btn btn-primary', 'style' => 'margin-right: 5px;']);


            echo Html::a('Delete', ['delete', 'assignment_id' => $model->assignment_id, 'agent_id' => $model->agent_id, 'restaurantUuid' => $model->restaurant_uuid], [
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
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'role',
                        'format' => 'html',
                        'value' => function ($data) {
                            return $data->role == AgentAssignment::AGENT_ROLE_OWNER ? 'Owner' : 'Staff';
                        },
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
