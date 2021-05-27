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
<?php

      $countryQuery = Country::find()->asArray()->all();
      $countryArray = ArrayHelper::map($countryQuery, 'country_id', 'country_name');


      $form = ActiveForm::begin();
  ?>


  <div class="card">
      <div class="business-location-form card-body">




          <?= $form->field($model, 'business_location_name')->textInput(['maxlength' => true, 'placeholder' => "Main branch"])->label('Location name *') ?>

          <?= $form->field($model, 'business_location_name_ar')->textInput(['maxlength' => true, 'placeholder' => "الفرع الرئيسي"])->label('Location name in Arabic *') ?>



          <?=
            $form->field($model, 'country_id')->dropDownList($countryArray, [
                'prompt' => 'Choose country name...',
                'class' => 'form-control select2',
                'multiple' => false
            ])->label('Located in *');
          ?>


          <div class="form-group">
              <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>



          </div>

      </div>
</div>
<?php
  // if(!$model->isNewRecord){
   //  echo Html::a('<i class="feather icon-trash"></i> Delete this business location', ['delete' ,'id' => $model->business_location_id, 'storeUuid' => $storeUuid], [
   //     'class' => 'mr-1',
   //     'data' => [
   //         'confirm' => 'Are you sure you want to delete this location?',
   //         'method' => 'post',
   //     ],
   //     'style' => 'margin-left: 7px; color: red'
   // ]);
  // }
?>

<?php ActiveForm::end(); ?>
