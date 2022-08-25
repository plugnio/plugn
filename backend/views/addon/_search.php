<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AddonSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="addon-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'addon_uuid') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'name_ar') ?>

    <?= $form->field($model, 'description') ?>

    <?= $form->field($model, 'description_ar') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'special_price') ?>

    <?php // echo $form->field($model, 'slug') ?>

    <?php // echo $form->field($model, 'expected_delivery') ?>

    <?php // echo $form->field($model, 'sort_number') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'updated_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
