<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantDelivery */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="restaurant-delivery-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'restaurant_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'area_id')->textInput() ?>

    <?= $form->field($model, 'delivery_time')->textInput() ?>

    <?= $form->field($model, 'delivery_fee')->textInput() ?>

    <?= $form->field($model, 'min_charge')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
