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

    <div class="grid">
        <div class="row">
            <div class="col-md-3">
                <?= $form->field($model, 'agent_id') ?>
            </div>
            <div class="col-md-3">
                <?= $form->field($model, 'utm_uuid') ?>
            </div>
        </div>
    </div>

    <?php // echo $form->field($model, 'agent_status') ?>

    <?php // echo $form->field($model, 'agent_created_at') ?>

    <?php // echo $form->field($model, 'agent_updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
