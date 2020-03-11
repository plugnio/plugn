<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ItemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'item_uuid') ?>

    <?= $form->field($model, 'restaurant_uuid') ?>

    <?= $form->field($model, 'item_name') ?>

    <?= $form->field($model, 'item_name_ar') ?>

    <?= $form->field($model, 'item_description') ?>

    <?php // echo $form->field($model, 'item_description_ar') ?>

    <?php // echo $form->field($model, 'sort_number') ?>

    <?php // echo $form->field($model, 'stock_qty') ?>

    <?php // echo $form->field($model, 'item_image') ?>

    <?php // echo $form->field($model, 'extra_option_price') ?>

    <?php // echo $form->field($model, 'item_created_at') ?>

    <?php // echo $form->field($model, 'item_updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
