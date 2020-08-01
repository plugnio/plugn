<?php

use yii\helpers\Html;
use wbraganca\dynamicform\DynamicFormWidget;

$js = "
$(document).on('wheel', 'input[type=number]', function (e) {
    $(this).blur();
});

";

$this->registerJs($js);
?>

<?php DynamicFormWidget::begin([
    'widgetContainer' => 'dynamicform_inner',
    'widgetBody' => '.container-extra-options',
    'widgetItem' => '.extra-option',
    'min' => 1,
    'insertButton' => '.add-extra-option',
    'deleteButton' => '.remove-extra-option',
    'model' => $modelsExtraOption[0],
    'formId' => 'dynamic-form',
    'formFields' => [
        'extra_option_name',
        'extra_option_name_ar',
        'extra_option_price',
    ],
]); ?>
<div class="container-extra-options" style="margin-top: 10px;">

    <?php foreach ($modelsExtraOption as $indexExtraOption => $modelExtraOption): ?>

              <div class="extra-option">
                  <?php
                  // necessary for update action.
                  if (!$modelExtraOption->isNewRecord) {
                      echo Html::activeHiddenInput($modelExtraOption, "[{$indexOption}][{$indexExtraOption}]extra_option_id");
                  }
                  ?>

                  <div class="row" style="margin-top: 20px;   margin-bottom: 20px;">
                      <div class="col-lg-4 col-md-12">
                          <?= $form->field($modelExtraOption, "[{$indexOption}][{$indexExtraOption}]extra_option_name")->textInput(['maxlength' => true, 'placeholder' => 'e.g. Red, Black']) ?>
                      </div>
                      <div class="col-lg-4 col-md-12">
                          <?= $form->field($modelExtraOption, "[{$indexOption}][{$indexExtraOption}]extra_option_name_ar")->textInput(['maxlength' => true, 'placeholder' => 'على سبيل المثال أحمر أسود']) ?>
                      </div>
                      <div class="col-lg-3 col-md-12">
                          <?= $form->field($modelExtraOption, "[{$indexOption}][{$indexExtraOption}]extra_option_price")->textInput(['type' => 'number', 'step' => '.01', 'maxlength' => true, 'placeholder' => 'Price']) ?>
                      </div>
                      <div class="col-lg-1 col-md-12">
                          <button  style=" width: 100%; margin-left: auto;  margin-top: 15px; height: 40px;" type="button" class="remove-extra-option btn btn-danger"><span class="fa fa-trash"></span></button>
                      </div>
                  </div>
              </div>
     <?php endforeach; ?>
   </div>
   <button type="button" class="add-extra-option btn btn-outline-secondary">Add Option Value</button>

<?php DynamicFormWidget::end(); ?>
