<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Queue;

/* @var $this yii\web\View */
/* @var $model common\models\Queue */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="queue-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'restaurant_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'queue_status')->dropDownList([
        Queue::QUEUE_STATUS_FAILED=>'Failed',
        Queue::QUEUE_STATUS_PENDING=>'Pending',
        Queue::QUEUE_STATUS_CREATING=>'Creating',
        Queue::QUEUE_STATUS_COMPLETE=>'Published',
        Queue::QUEUE_STATUS_HOLD=>'Hold'
    ]) ?>

    <?= $form->field($model, 'queue_start_at')->textInput() ?>

    <?= $form->field($model, 'queue_end_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
