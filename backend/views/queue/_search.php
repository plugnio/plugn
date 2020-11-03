<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\QueueSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="queue-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'queue_id') ?>

    <?= $form->field($model, 'restaurant_uuid') ?>

    <?= $form->field($model, 'queue_status') ?>

    <?= $form->field($model, 'queue_created_at') ?>

    <?= $form->field($model, 'queue_updated_at') ?>

    <?php // echo $form->field($model, 'queue_start_at') ?>

    <?php // echo $form->field($model, 'queue_end_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
