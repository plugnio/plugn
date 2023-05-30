<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Campaign */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="campaign-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'utm_source')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'utm_medium')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'utm_campaign')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'utm_content')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'utm_term')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'investment')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
