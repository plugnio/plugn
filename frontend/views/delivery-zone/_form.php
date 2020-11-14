<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\DeliveryZone */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="delivery-zone-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'business_location_id')->textInput() ?>

    <?= $form->field($model, 'business_location_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'business_location_name_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'support_delivery')->textInput() ?>

    <?= $form->field($model, 'support_pick_up')->textInput() ?>

    <?= $form->field($model, 'delivery_time')->textInput() ?>

    <?= $form->field($model, 'delivery_fee')->textInput() ?>

    <?= $form->field($model, 'min_charge')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
