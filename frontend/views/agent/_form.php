<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Agent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="agent-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'agent_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'agent_email')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
