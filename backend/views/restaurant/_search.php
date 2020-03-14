<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\RestaurantSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="restaurant-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'restaurant_uuid') ?>

    <?= $form->field($model, 'vendor_id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'name_ar') ?>

    <?= $form->field($model, 'tagline') ?>

    <?php // echo $form->field($model, 'tagline_ar') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'thumbnail_image') ?>

    <?php // echo $form->field($model, 'logo') ?>

    <?php // echo $form->field($model, 'support_delivery') ?>

    <?php // echo $form->field($model, 'support_pick_up') ?>

    <?php // echo $form->field($model, 'min_delivery_time') ?>

    <?php // echo $form->field($model, 'min_pickup_time') ?>

    <?php // echo $form->field($model, 'delivery_fee') ?>

    <?php // echo $form->field($model, 'min_charge') ?>


    <?php // echo $form->field($model, 'phone_number') ?>

    <?php // echo $form->field($model, 'restaurant_created_at') ?>

    <?php // echo $form->field($model, 'restaurant_updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
