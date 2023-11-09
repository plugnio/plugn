<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="business-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parent_business_type_uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'business_type_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'business_type_ar')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
