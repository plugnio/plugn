<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Country */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="country-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'country_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'country_name_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'iso')->textInput(['maxlength' => 2]) ?>

    <?= $form->field($model, 'emoji')->textInput(['maxlength' => 3]) ?>

    <?= $form->field($model, 'country_code')->textInput(['maxlength' => 3, 'type' => 'number']) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
