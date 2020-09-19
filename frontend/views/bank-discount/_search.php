<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\BankDiscountSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bank-discount-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'bank_discount_id') ?>

    <?= $form->field($model, 'bank_id') ?>

    <?= $form->field($model, 'restaurant_uuid') ?>

    <?= $form->field($model, 'discount_type') ?>

    <?= $form->field($model, 'discount_amount') ?>

    <?php // echo $form->field($model, 'bank_discount_status') ?>

    <?php // echo $form->field($model, 'valid_from') ?>

    <?php // echo $form->field($model, 'valid_until') ?>

    <?php // echo $form->field($model, 'max_redemption') ?>

    <?php // echo $form->field($model, 'limit_per_customer') ?>

    <?php // echo $form->field($model, 'minimum_order_amount') ?>

    <?php // echo $form->field($model, 'bank_discount_created_at') ?>

    <?php // echo $form->field($model, 'bank_discount_updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
