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



    <?= $form->field($model, 'business_location_name')->textInput(['maxlength' => true, 'placeholder' => "Main branch"])->label('Location name *') ?>

    <?= $form->field($model, 'business_location_name_ar')->textInput(['maxlength' => true, 'placeholder' => "الفرع الرئيسي"])->label('Location name in Arabic *') ?>



    <?=
      $form->field($model, 'country_id')->dropDownList($countryArray, [
          'prompt' => 'Choose country name...',
          'class' => 'form-control select2',
          'multiple' => false
      ])->label('Located in *');
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
      'style' => '    border-top-left-radius: 0px !important;   border-bottom-left-radius: 0px !important;']) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>

        <?php
          if(!$model->isNewRecord){
            echo Html::a('<i class="feather icon-trash"></i> Delete', ['delete' ,'id' => $model->business_location_id, 'storeUuid' => $storeUuid], [
               'class' => 'btn btn-danger mr-1',
               'data' => [
                   'confirm' => 'Are you sure you want to delete this location?',
                   'method' => 'post',
               ],
               'style' => 'margin-left: 7px;'
           ]);
          }
        ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
