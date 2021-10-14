<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PartnerPayout */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="partner-payout-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'partner_payout_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'partner_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'amount')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'payout_status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
