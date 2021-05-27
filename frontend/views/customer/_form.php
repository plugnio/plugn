<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use borales\extensions\phoneInput\PhoneInput;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model); ?>

    <?= $form->field($model, 'customer_name')->textInput(['maxlength' => true]) ?>

    <?=
       $form->field($model, 'customer_phone_number')->widget(PhoneInput::className(), [
          'jsOptions' => [
              'preferredCountries' => ['kw', 'sa', 'aed','qa','bh','om'],
              'initialCountry' => 'kw'
          ]
      ]);
    ?>
    <?= $form->field($model, 'customer_email')->textInput(['maxlength' => true, 'type' => 'email']) ?>

    <div class="form-group" style="background: #f4f6f9; padding-bottom: 10px; margin-bottom: 0px; background:#f4f6f9 ">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
