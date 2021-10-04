<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\PartnerPayoutSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="partner-payout-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'partner_payout_uuid') ?>

    <?= $form->field($model, 'partner_uuid') ?>

    <?= $form->field($model, 'amount') ?>

    <?= $form->field($model, 'created_at') ?>

    <?= $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'payout_status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
