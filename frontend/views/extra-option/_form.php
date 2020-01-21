<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\ExtraOption */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="extra-option-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'extra_option_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'extra_option_name_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
