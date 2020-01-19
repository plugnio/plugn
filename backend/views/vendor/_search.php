<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\VendorSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vendor-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'vendor_id') ?>

    <?= $form->field($model, 'restaurant_uuid') ?>

    <?= $form->field($model, 'vendor_name') ?>

    <?= $form->field($model, 'vendor_email') ?>

    <?= $form->field($model, 'vendor_auth_key') ?>

    <?php // echo $form->field($model, 'vendor_password_hash') ?>

    <?php // echo $form->field($model, 'vendor_password_reset_token') ?>

    <?php // echo $form->field($model, 'vendor_status') ?>

    <?php // echo $form->field($model, 'vendor_created_at') ?>

    <?php // echo $form->field($model, 'vendor_updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
