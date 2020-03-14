<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\WorkingHoursSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="working-hours-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'working_day_id') ?>

    <?= $form->field($model, 'restaurant_uuid') ?>

    <?= $form->field($model, 'operating_from') ?>

    <?= $form->field($model, 'operating_to') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
