<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OrderItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'order_item_id') ?>

    <?= $form->field($model, 'order_uuid') ?>

    <?= $form->field($model, 'item_uuid') ?>

    <?= $form->field($model, 'item_name') ?>

    <?= $form->field($model, 'item_price') ?>

    <?php // echo $form->field($model, 'qty') ?>

    <?php // echo $form->field($model, 'customer_instruction') ?>

    <?php // echo $form->field($model, 'order_status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
