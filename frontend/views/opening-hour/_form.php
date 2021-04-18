<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\OpeningHour;

/* @var $this yii\web\View */
/* @var $model common\models\OpeningHour */
/* @var $form yii\widgets\ActiveForm */


$js = "
    $( '.pickatime-open-at').pickatime({
        min: [00,00],
        max: [23,00],
        format: 'H:i',
        formatLabel: 'H:i',
        formatSubmit: 'H:i',
        hiddenPrefix: 'prefix__',
        hiddenSuffix: '__suffix'
    });
    $( '.pickatime-close-at').pickatime({
        min: [00,30],
        max: [23,30],
        format: 'H:i',
        formatLabel: 'H:i',
        formatSubmit: 'H:i',
        hiddenPrefix: 'prefix__',
        hiddenSuffix: '__suffix'
    });
$('.picker').css('position', 'inherit');
    $('thead').hide();
    $('.top').hide();
    $('.bottom').hide();
    $('.form-group').css('margin', '0px');
    $('#open24Hrs').change(function(e){
      $.each([ 1,2,3,4,5,6,7], function( index, value ) {
        document.getElementById('OpenTime'+index).value = '00:00';
        document.getElementById('CloseTime'+index).value = '23:59';
      });
    });
    $('#dailyOpenTime').change(function(e){
      $.each([ 1,2,3,4,5,6,7], function( index, value ) {
        document.getElementById('OpenTime'+index).value = e.target.value ;
      });
    });
    $('#dailyCloseTime').change(function(e){
      $.each([ 1,2,3,4,5,6,7], function( index, value ) {
        document.getElementById('CloseTime'+index).value = e.target.value ;
      });
    });


    $('.delete-button').click(function() {
    var detail = $(this).closest('.receipt-detail');
    var updateType = detail.find('.update-type');
    if (updateType.val() === " . json_encode(OpeningHour::UPDATE_TYPE_UPDATE) . ") {
        //marking the row for deletion
        updateType.val(" . json_encode(OpeningHour::UPDATE_TYPE_DELETE) . ");
        detail.hide();
    } else {
        //if the row is a new row, delete the row
        detail.remove();
    }

});
";
$this->registerJs($js);

$this->registerCss("
.custom-switch.switch-lg .custom-control-label::before , .custom-control-input:checked ~ .custom-control-label::before{
      background-color: #28C76F !important;
  }
  .custom-switch.switch-lg .custom-control-label .switch-text-right, .custom-switch.switch-lg .custom-control-label .switch-icon-right {
    color:white !important;
  }
.custom-switch-success .custom-control-input:checked ~ .custom-control-label::before {
      background-color: #EA5455 !important;
  }
  ");

?>

<div class="opening-hour-form">


      <?php $form = ActiveForm::begin([
          'enableClientValidation' => false
      ]); ?>

      <div class="card">
      <div class="card-body">


      <?php foreach ($modelDetails as $i => $modelDetail) : ?>

          <div class="row receipt-detail receipt-detail-<?= $i ?>">
              <div class="col-5">
                  <?= Html::activeHiddenInput($modelDetail, "[$i]opening_hour_id") ?>
                  <?= Html::activeHiddenInput($modelDetail, "[$i]updateType", ['class' => 'update-type']) ?>
                  <?= $form->field($modelDetail, "[$i]open_at" )->textInput(['class' => 'form-control pickatime-open-at','id'=>'dailyOpenTime', 'style'=>'position: initial;'])->label('Opens at'); ?>

              </div>
              <div class="col-5">
                <?= $form->field($modelDetail, "[$i]close_at" )->textInput(['class' => 'form-control pickatime-open-at','id'=>'dailyOpenTime', 'style'=>'position: initial;'])->label('Opens at'); ?>

              </div>
              <div class="col-2">
                  <?= Html::button('x', ['class' => 'delete-button btn btn-danger', 'data-target' => "receipt-detail-$i"]) ?>
              </div>
          </div>
      <?php endforeach; ?>
    </div>
    </div>
      <div class="form-group">
          <?= Html::submitButton('Update', ['class' => 'btn btn-success']) ?>
          <?= Html::submitButton('Add row', ['name' => 'addRow', 'value' => 'true', 'class' => 'btn btn-info']) ?>
      </div>

      <?php ActiveForm::end(); ?>

</div>
