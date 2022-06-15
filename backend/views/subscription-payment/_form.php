<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\SubscriptionPayment */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="subscription-payment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'payment_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'restaurant_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'subscription_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_gateway_order_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_gateway_transaction_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_mode')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_current_status')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'payment_amount_charged')->textInput() ?>

    <?= $form->field($model, 'payment_net_amount')->textInput() ?>

    <?= $form->field($model, 'payment_gateway_fee')->textInput() ?>

    <?= $form->field($model, 'payment_udf1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_udf2')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_udf3')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_udf4')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_udf5')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'received_callback')->textInput() ?>

    <?= $form->field($model, 'response_message')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_token')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_fee')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payout_status')->textInput() ?>

    <?= $form->field($model, 'partner_payout_uuid')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
