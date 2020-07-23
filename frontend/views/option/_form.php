<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Option */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="option-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>


    <?= $form->field($model, 'option_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'option_name_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'min_qty')->textInput(['placeholder' => 'Enter the minimum amount of items a customer should select']) ?>
    
    <?= $form->field($model, 'max_qty')->textInput(['placeholder' => 'Enter the maximum amount of items a customer should select (optional)']) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
