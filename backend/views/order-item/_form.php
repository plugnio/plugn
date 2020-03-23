<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\OrderItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'order_uuid')->textInput() ?>

    <?= $form->field($model, 'item_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_price')->textInput() ?>

    <?= $form->field($model, 'qty')->textInput() ?>

    <?= $form->field($model, 'customer_instruction')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'order_status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
