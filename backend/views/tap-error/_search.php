<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\TapErrorSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tap-error-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'tap_error_uuid') ?>

    <?= $form->field($model, 'restaurant_uuid') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'message') ?>

    <?= $form->field($model, 'text') ?>

    <?php // echo $form->field($model, 'issue_logged') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
