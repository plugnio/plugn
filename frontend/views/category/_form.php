<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card category-form">
  <div class="card-body">

    <?php
    $form = ActiveForm::begin([
                'enableClientScript' => false,
                'errorSummaryCssClass' => 'alert alert-danger'
    ]);
    ?>

    <?= $form->errorSummary([$model],['header' => '<h4 class="alert-heading">Please fix the following errors:</h4>']); ?>


    <?= $form->field($model, 'title')->textInput(['maxlength' => true,'placeholder' => 'e.g. Meal Deals or Sushi Sets or Soft Drinks']) ?>

    <?= $form->field($model, 'title_ar')->textInput(['maxlength' => true, 'placeholder' => 'e.g. Meal Deals or Sushi Sets or Soft Drinks']) ?>

    <?= $form->field($model, 'subtitle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'subtitle_ar')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'sort_number')->textInput() ?>

    <div class="form-group" style="background: #f4f6f9; padding-bottom: 10px; margin-bottom: 0px; padding-bottom: 15px; background:#f4f6f9 ">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
</div>
