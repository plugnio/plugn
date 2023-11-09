<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessItemType */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="business-item-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'business_item_type_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'business_item_type_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'business_item_type_subtitle_en')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'business_item_type_subtitle_ar')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
