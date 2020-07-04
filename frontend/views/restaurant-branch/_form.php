<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantBranch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card restaurant-branch-form">
  <div class="card-body">

    <?php $form = ActiveForm::begin([
        'errorSummaryCssClass' => 'alert alert-danger'
      ]);
      ?>

      <?= $form->errorSummary([$model],['header' => '<h4 class="alert-heading">Please fix the following errors:</h4>']); ?>

    <?= $form->field($model, 'branch_name_en')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'branch_name_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'prep_time')->textInput(['maxlength' => true]) ?>

    <div class="form-group" style="background: #f4f6f9; padding-bottom: 10px; margin-bottom: 0px; padding-bottom: 15px; background:#f4f6f9 ">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
