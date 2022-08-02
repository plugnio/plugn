<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantPaymentMethod */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="restaurant-payment-method-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'restaurant_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'payment_method_id')->dropDownList(\yii\helpers\ArrayHelper::map(\common\models\PaymentMethod::find()->all(),'payment_method_id','payment_method_name')) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancel', ['restaurant-payment-method/index'],['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
