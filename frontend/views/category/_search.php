<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\CategorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'category_id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'title_ar') ?>

    <?= $form->field($model, 'subtitle') ?>

    <?= $form->field($model, 'subtitle_ar') ?>

    <?= $form->field($model, 'sort_number') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
