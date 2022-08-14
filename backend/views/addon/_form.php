<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Addon */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="addon-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'description_ar')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'special_price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'expected_delivery')->textInput()->hint('in days') ?>

    <?= $form->field($model, 'sort_number')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList(
        [
            10 => 'Active',
            0 => 'Inactive',
        ]
    ); ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
