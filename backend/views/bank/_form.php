<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Bank */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bank-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'bank_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bank_iban_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bank_swift_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bank_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bank_transfer_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bank_created_at')->textInput() ?>

    <?= $form->field($model, 'bank_updated_at')->textInput() ?>

    <?= $form->field($model, 'deleted')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
