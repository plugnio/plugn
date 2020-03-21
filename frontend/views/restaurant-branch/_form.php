<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantBranch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="restaurant-branch-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'branch_name_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'branch_name_ar')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'prep_time')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
