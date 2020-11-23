<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\View;
use common\models\BusinessLocation;
use common\models\City;
use common\models\Area;
use common\models\Country;

/* @var $this yii\web\View */
/* @var $model common\models\DeliveryZone */
/* @var $form yii\widgets\ActiveForm */

$url = Yii::$app->request->baseUrl . '/delivery-zone/render-cities-checbox-list?restaurantUuid='. $restaurantUuid .'&countryId=';
$selectedAreas = $model->getAreaDeliveryZones()->all();
$selectedAreas = ArrayHelper::map($selectedAreas, 'area_id', 'area_id');
$data = json_encode($selectedAreas);

$js = "
$(document).ready(function() {

      $.ajax({
         // Controller method to call
         url: '$url' + $('#country-id').val(),
         // Parameter data to pass in
         data: {
             selectedAreas : $data
         },
         type: 'POST',
         cache: false,
         success: function(data) {
           console.log(data);
           $('#cities').html(data);
           $(document).trigger('rebindButtons');
         }
      })


      $('#country-id').on('change', function(e){

              $.ajax({
                 // Controller method to call
                 url: '$url' + e.target.value,
                 // Parameter data to pass in
                 data: {
                     selectedAreas : $data
                 },
                 type: 'POST',
                 cache: false,
                 success: function(data) {
                   $('#cities').html(data);
                   $(document).trigger('rebindButtons');
                 }
         })
      });
  });
";

$this->registerJs($js);

?>



<div class="card">
    <div class="card-body delivery-zone-form">

        <?php
            $areaQuery = $model->getAreas()->all();
            $areaArray[] = ArrayHelper::map($areaQuery, 'area_id', 'area_name');


            $businessLocationQuery = BusinessLocation::find()->where(['restaurant_uuid' => $restaurantUuid])->asArray()->all();
            $businessLocationArray = ArrayHelper::map($businessLocationQuery, 'business_location_id', 'business_location_name');


            $countryQuery = Country::find()->asArray()->all();
            $countryArray = ArrayHelper::map($countryQuery, 'country_id', 'country_name');


            $form = ActiveForm::begin();
        ?>

        <?=
        $form->field($model, 'business_location_id')->dropDownList($businessLocationArray, [
            'class' => 'form-control select2',
            'multiple' => false,
            'id' => 'business-location-id',
        ]);
        ?>


        <?=
          $form->field($model, 'country_id')->dropDownList($countryArray, [
              'class' => 'form-control select2',
              'multiple' => false,
              'id' => 'country-id',
          ]);
        ?>


        <?= $form->field($model, 'delivery_time')->textInput() ?>

        <?= $form->field($model, 'delivery_fee')->textInput() ?>

        <?= $form->field($model, 'min_charge')->textInput() ?>


        <div id="cities"></div>


        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
