<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\ExtraOptionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="extra-option-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'extra_option_id') ?>

    <?= $form->field($model, 'option_id') ?>

    <?= $form->field($model, 'extra_option_name') ?>

    <?= $form->field($model, 'extra_option_name_ar') ?>

    <?= $form->field($model, 'price') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
