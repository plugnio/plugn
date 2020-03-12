<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\OrderSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'order_uuid') ?>

    <?= $form->field($model, 'area_id') ?>

    <?= $form->field($model, 'area_name') ?>

    <?= $form->field($model, 'area_name_ar') ?>

    <?= $form->field($model, 'unit_type') ?>

    <?php // echo $form->field($model, 'block') ?>

    <?php // echo $form->field($model, 'street') ?>

    <?php // echo $form->field($model, 'avenue') ?>

    <?php // echo $form->field($model, 'house_number') ?>

    <?php // echo $form->field($model, 'special_directions') ?>

    <?php // echo $form->field($model, 'customer_name') ?>

    <?php // echo $form->field($model, 'customer_phone_number') ?>

    <?php // echo $form->field($model, 'customer_email') ?>

    <?php // echo $form->field($model, 'payment_method_id') ?>

    <?php // echo $form->field($model, 'payment_method_name') ?>

    <?php // echo $form->field($model, 'order_status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
