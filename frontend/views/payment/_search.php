<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\PaymentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="payment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'payment_uuid') ?>

    <?= $form->field($model, 'customer_id') ?>

    <?= $form->field($model, 'order_uuid') ?>

    <?= $form->field($model, 'payment_gateway_order_id') ?>

    <?= $form->field($model, 'payment_gateway_transaction_id') ?>

    <?php // echo $form->field($model, 'payment_mode') ?>

    <?php // echo $form->field($model, 'payment_current_status') ?>

    <?php // echo $form->field($model, 'payment_amount_charged') ?>

    <?php // echo $form->field($model, 'payment_net_amount') ?>

    <?php // echo $form->field($model, 'payment_gateway_fee') ?>

    <?php // echo $form->field($model, 'payment_udf1') ?>

    <?php // echo $form->field($model, 'payment_udf2') ?>

    <?php // echo $form->field($model, 'payment_udf3') ?>

    <?php // echo $form->field($model, 'payment_udf4') ?>

    <?php // echo $form->field($model, 'payment_udf5') ?>

    <?php // echo $form->field($model, 'payment_created_at') ?>

    <?php // echo $form->field($model, 'payment_updated_at') ?>

    <?php // echo $form->field($model, 'received_callback') ?>

    <?php // echo $form->field($model, 'response_message') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
