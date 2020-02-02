<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\OrderItemExtraOptionsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-item-extra-options-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'order_item_extra_options_id') ?>

    <?= $form->field($model, 'order_item_id') ?>

    <?= $form->field($model, 'extra_option_id') ?>

    <?= $form->field($model, 'extra_option_name') ?>

    <?= $form->field($model, 'extra_option_name_ar') ?>

    <?php // echo $form->field($model, 'extra_option_price') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
