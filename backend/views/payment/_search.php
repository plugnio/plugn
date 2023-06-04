<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PaymentSearch */
/* @var $form yii\widgets\ActiveForm */

$js = "

    $(function() {
        $('.btn-download-excel').click(function() {
            $('.payment-search form').attr('action', '". \yii\helpers\Url::to(['payment/export-to-excel'])."');
            $('.payment-search form input[name=\"r\"]').val('payment/export-to-excel');
            $('.payment-search form').submit();
        });
    });

";

$this->registerJs($js);

?>

<div class="payment-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get'
    ]); ?>

    <div class="row">
    <div class="col-md-2">
    <?= $form->field($model, 'payment_uuid') ?>
    </div>

    <div class="col-md-2">
    <?= $form->field($model, 'restaurant_uuid') ?>
    </div>

    <div class="col-md-2">
    <?= $form->field($model, 'customer_id') ?>
    </div>

    <div class="col-md-2">
        <?= $form->field($model, 'payment_gateway_order_id') ?>
    </div>

    <div class="col-md-2">
        <?= $form->field($model, 'date_from')->textInput(["type"=>"date"]) ?>
    </div>

    <div class="col-md-2">
    <?= $form->field($model, 'date_to')->textInput(["type"=>"date"]) ?>
    </div>
    </div>

    <?php // echo $form->field($model, 'payment_gateway_transaction_id') ?>

    <?php // echo $form->field($model, 'payment_gateway_payment_id') ?>

    <?php // echo $form->field($model, 'payment_gateway_invoice_id') ?>

    <?php // echo $form->field($model, 'payment_mode') ?>

    <?php // echo $form->field($model, 'payment_current_status') ?>

    <?php // echo $form->field($model, 'payment_amount_charged') ?>

    <?php // echo $form->field($model, 'payment_net_amount') ?>

    <?php // echo $form->field($model, 'payment_gateway_fee') ?>

    <?php // echo $form->field($model, 'plugn_fee') ?>

    <?php // echo $form->field($model, 'payment_udf1') ?>

    <?php // echo $form->field($model, 'payment_udf2') ?>

    <?php // echo $form->field($model, 'payment_udf3') ?>

    <?php // echo $form->field($model, 'payment_udf4') ?>

    <?php // echo $form->field($model, 'payment_udf5') ?>

    <?php // echo $form->field($model, 'payment_created_at') ?>

    <?php // echo $form->field($model, 'payment_updated_at') ?>

    <?php // echo $form->field($model, 'received_callback') ?>

    <?php // echo $form->field($model, 'response_message') ?>

    <?php // echo $form->field($model, 'payment_token') ?>

    <?php // echo $form->field($model, 'payment_gateway_name') ?>

    <div class="row">
        <div class="col-md-12">
        <div class="form-group">
            <label class="control-label" for="ordersearch-order_status">&nbsp;</label>

            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>

            <?= Html::button('Download as Excel', ['class' => 'btn btn-warning btn-download-excel']) ?>

            <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>

        </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
