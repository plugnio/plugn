<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\BusinessItemTypeSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="business-item-type-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'business_item_type_uuid') ?>

    <?= $form->field($model, 'business_item_type_en') ?>

    <?= $form->field($model, 'business_item_type_ar') ?>

    <?= $form->field($model, 'business_item_type_subtitle_en') ?>

    <?= $form->field($model, 'business_item_type_subtitle_ar') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
