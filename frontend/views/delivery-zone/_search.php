<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\DeliveryZoneSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="delivery-zone-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'delivery_zone_id') ?>

    <?= $form->field($model, 'business_location_id') ?>

    <?= $form->field($model, 'business_location_name') ?>

    <?= $form->field($model, 'business_location_name_ar') ?>

    <?= $form->field($model, 'support_delivery') ?>

    <?php // echo $form->field($model, 'support_pick_up') ?>

    <?php // echo $form->field($model, 'delivery_time') ?>

    <?php // echo $form->field($model, 'delivery_fee') ?>

    <?php // echo $form->field($model, 'min_charge') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
