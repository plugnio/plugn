<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\WorkingHours */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="working-hours-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'working_day_id')->textInput() ?>

    <?= $form->field($model, 'restaurant_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operating_from')->textInput() ?>

    <?= $form->field($model, 'operating_to')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
