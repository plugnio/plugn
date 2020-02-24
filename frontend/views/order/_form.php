<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Area;
use common\models\PaymentMethod;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="order-form">

    <?php
    $areaQuery = Area::find()->asArray()->all();
    $areaList = ArrayHelper::map($areaQuery, 'area_id', 'area_name');

    $paymentQuery = PaymentMethod::find()->asArray()->all();
    $paymentList = ArrayHelper::map($paymentQuery, 'payment_method_id', 'payment_method_name');

    $form = ActiveForm::begin();
    ?>

    <?= $form->field($model, 'area_id')->dropDownList($areaList)->label('Area'); ?>

    <?= $form->field($model, 'unit_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'block')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'avenue')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'house_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'special_directions')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'customer_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'customer_phone_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'customer_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_method_id')->dropDownList($paymentList)->label('Payment Method'); ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
