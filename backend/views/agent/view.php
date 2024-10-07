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

        <?php if($model->agent_status == \common\models\Agent::STATUS_ACTIVE) { ?>
            <?= Html::a('Login <i class="fa fa-sign-in-alt"></i>', ['login', 'id' => $model->agent_id], ['class' => 'btn btn-primary', "target" => "_blank"]) ?>
        <?php } ?>

        <?= Html::a('Update <i class="fa fa-pen"></i>', ['update', 'id' => $model->agent_id], ['class' => 'btn btn-primary btn-update']) ?>

        <?= Html::a('Delete <i class="fa fa-trash"></i>', ['delete', 'id' => $model->agent_id], [
            'class' => 'btn btn-danger btn-delete',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>

        <?= Html::a('Remove Store Assignments <i class="fa fa-user-times"></i>', ['delete-assignments', 'id' => $model->agent_id], [
            'class' => 'btn btn-danger btn-delete',
            'data' => [
                'confirm' => 'Are you sure you want to remove agent from all stores?',
                'method' => 'post',
            ],
        ]) ?>

        <?php if(!$model->agent_email_verification) {
            echo Html::a('Send verification email <i class="fa fa-envelope"></i>', ['send-verification-email', 'id' => $model->agent_id], [
                'class' => 'btn btn-danger'
            ]);

            if($model->agent_auth_key) {
                $verifyLink = Yii::$app->params['newDashboardAppUrl'] . '/verify-email/' . urlencode($model->agent_email) . '/' . $model->agent_auth_key;

                echo "<h5>Verification link</h5>" . Html::a($verifyLink, $verifyLink);
            }
        }?>

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
            'agent_deleted_at:datetime'
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
