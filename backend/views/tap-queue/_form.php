<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\TapQueue */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tap-queue-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'restaurant_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'queue_status')->textInput() ?>

    <?= $form->field($model, 'queue_start_at')->textInput() ?>

    <?= $form->field($model, 'queue_end_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
