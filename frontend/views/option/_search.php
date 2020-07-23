<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\OptionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="option-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'option_id') ?>

    <?= $form->field($model, 'item_uuid') ?>

    <?= $form->field($model, 'min_qty') ?>

    <?= $form->field($model, 'max_qty') ?>

    <?= $form->field($model, 'option_name') ?>

    <?php // echo $form->field($model, 'option_name_ar') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
