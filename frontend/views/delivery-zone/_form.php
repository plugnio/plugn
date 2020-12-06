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

    <?php
      if($model->delivery_zone_id){
          echo         Html::a('Delete', ['delete', 'id' => $model->delivery_zone_id, 'restaurantUuid' => $restaurantUuid], [
                      'class' => 'btn btn-danger',
                      'style' => 'margin-bottom:15px',
                      'data' => [
                          'confirm' => 'Are you sure you want to delete this zone?',
                          'method' => 'post',
                      ],
                  ]);
      }

    ?>

<div class="card">
    <div class="card-body delivery-zone-form">

        <?php
            $areaQuery = $model->getAreas()->all();
            $areaArray[] = ArrayHelper::map($areaQuery, 'area_id', 'area_name');

            $form = ActiveForm::begin();
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
