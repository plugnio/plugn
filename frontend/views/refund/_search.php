<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RefundSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="refund-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'refund_id') ?>

    <?= $form->field($model, 'restaurant_uuid') ?>

    <?= $form->field($model, 'order_uuid') ?>

    <?= $form->field($model, 'refund_amount') ?>

    <?= $form->field($model, 'reason') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
