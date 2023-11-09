<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessCategory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="business-category-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'business_category_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'business_category_ar')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
