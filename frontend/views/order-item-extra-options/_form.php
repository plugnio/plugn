<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OrderItemExtraOptions */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-item-extra-options-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'order_item_id')->textInput() ?>

    <?= $form->field($model, 'extra_option_id')->textInput() ?>

    <?= $form->field($model, 'extra_option_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'extra_option_name_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'extra_option_price')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
