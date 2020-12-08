<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use common\models\Country;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessLocation */
/* @var $form yii\widgets\ActiveForm */


$js = "

$(document).on('wheel', 'input[type=number]', function (e) {
    $(this).blur();
});

";

$this->registerJs($js);
?>


  <div class="card">
<div class="business-location-form card-body">

    <?php

          $countryQuery = Country::find()->asArray()->all();
          $countryArray = ArrayHelper::map($countryQuery, 'country_id', 'country_name');


          $form = ActiveForm::begin();
      ?>



    <?= $form->field($model, 'business_location_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'business_location_name_ar')->textInput(['maxlength' => true]) ?>




    <?=
      $form->field($model, 'country_id')->dropDownList($countryArray, [
          'class' => 'form-control select2 select2',
          'multiple' => false,
          'value' => $model->restaurant->country_id
      ]);
    ?>

    <?= $form->field($model, 'business_location_tax', [
        'template' => "{label}"

        . "<div  class='input-group'>
            <div class='input-group-prepend'>
              <span class='input-group-text'> % </span>
            </div>
              {input}
          </div>
        "
        . "{error}{hint}"
    ])->textInput([
      'type' => 'number',
      'step' => '.01',
      'value' => 0,'style' => '    border-top-left-radius: 0px !important;   border-bottom-left-radius: 0px !important;']) ?>



    <?=
      $form->field($model, 'support_pick_up', [
          'template' => '
          <div class="vs-checkbox-con vs-checkbox-primary">
              {input}
              <span class="vs-checkbox">
                  <span class="vs-checkbox--check">
                      <i class="vs-icon feather icon-check"></i>
                  </span>
              </span>
              <span class="">{label}</span>
          </div>
          <div class=\"col-lg-8\">{error}</div>
          ',
      ])->checkbox([
          'checked' => $model->support_pick_up ? true : false,
          'id' => 'trackQuantityInput',
              ], false)
    ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
