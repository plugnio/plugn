<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\AgentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agent-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'agent_id') ?>

    <?= $form->field($model, 'agent_name') ?>

    <?= $form->field($model, 'agent_email') ?>

    <?= $form->field($model, 'agent_auth_key') ?>

    <?php // echo $form->field($model, 'agent_password_hash') ?>

    <?php // echo $form->field($model, 'agent_password_reset_token') ?>

    <?php // echo $form->field($model, 'agent_status') ?>

    <?php // echo $form->field($model, 'agent_created_at') ?>

    <?php // echo $form->field($model, 'agent_updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
