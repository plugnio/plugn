<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BankSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bank-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'bank_id') ?>

    <?= $form->field($model, 'bank_name') ?>

    <?= $form->field($model, 'bank_iban_code') ?>

    <?= $form->field($model, 'bank_swift_code') ?>

    <?= $form->field($model, 'bank_address') ?>

    <?php // echo $form->field($model, 'bank_transfer_type') ?>

    <?php // echo $form->field($model, 'bank_created_at') ?>

    <?php // echo $form->field($model, 'bank_updated_at') ?>

    <?php // echo $form->field($model, 'deleted') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
