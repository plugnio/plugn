<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PartnerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="partner-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'partner_uuid') ?>

    <?= $form->field($model, 'username') ?>

    <?= $form->field($model, 'partner_auth_key') ?>

    <?= $form->field($model, 'partner_password_hash') ?>

    <?= $form->field($model, 'partner_password_reset_token') ?>

    <?php // echo $form->field($model, 'partner_email') ?>

    <?php // echo $form->field($model, 'partner_status') ?>

    <?php // echo $form->field($model, 'referral_code') ?>

    <?php // echo $form->field($model, 'commission') ?>

    <?php // echo $form->field($model, 'partner_created_at') ?>

    <?php // echo $form->field($model, 'partner_updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
