<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="category-form">

    <?php
    $form = ActiveForm::begin([
                'enableClientScript' => false,
    ]);
    ?>

    <?= $form->errorSummary($model); ?>


    <?= $form->field($model, 'title')->textInput(['maxlength' => true,'placeholder' => 'e.g. Meal Deals or Sushi Sets or Soft Drinks']) ?>

    <?= $form->field($model, 'title_ar')->textInput(['maxlength' => true, 'placeholder' => 'e.g. Meal Deals or Sushi Sets or Soft Drinks']) ?>
    
    <?= $form->field($model, 'subtitle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'subtitle_ar')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'sort_number')->textInput() ?>

    <div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
