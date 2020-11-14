<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessLocation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="business-location-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'restaurant_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'business_location_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'business_location_name_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'support_delivery')->textInput() ?>

    <?= $form->field($model, 'support_pick_up')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
