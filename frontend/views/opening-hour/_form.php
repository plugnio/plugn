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
        format: 'H:i A',
        formatLabel: 'H:i',
        formatSubmit: 'H:i',
        hiddenPrefix: 'prefix__',
        hiddenSuffix: '__suffix'
    });
    $( '.pickatime-close-at').pickatime({
        min: [00,30],
        max: [23,30],
        format: 'H:i A',
        formatLabel: 'H:i A',
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

  .VfPpkd-Bz112c-LgbsSe {
    --mdc-ripple-fg-size: 0;
    --mdc-ripple-left: 0;
    --mdc-ripple-top: 0;
    --mdc-ripple-fg-scale: 1;
    --mdc-ripple-fg-translate-end: 0;
    --mdc-ripple-fg-translate-start: 0;
    -webkit-tap-highlight-color: rgba(0,0,0,0);
    will-change: transform,opacity;
}

.VHnWVc.gEG0eb {
    margin-left: 0;
    margin-right: 12px;
}

.VHnWVc {
    flex: 3;
    margin: 0 20px;
    position: relative;
}

.VfPpkd-Bz112c-LgbsSe {
    display: inline-block;
    position: relative;
    box-sizing: border-box;
    border: none;
    outline: none;
    background-color: transparent;
    fill: currentColor;
    color: inherit;
    font-size: 24px;
    text-decoration: none;
    cursor: pointer;
    -webkit-user-select: none;
    width: 48px;
    height: 48px;
    padding: 12px;
}

.KwjGFb.gEG0eb {
    -webkit-box-direction: normal;
    box-direction: normal;
    -webkit-box-orient: vertical;
    box-orient: vertical;
    flex-direction: column;
    flex: none;
}

.KwjGFb {
    justify-content: center;
    display: flex;
    flex: 1;
}
  ");


?>

<div class="opening-hour-form">


      <?php $form = ActiveForm::begin([
          'enableClientValidation' => false
      ]); ?>

      <div class="card">
        <div class="card-body">

          <div class="input-wrapper">
      <?php foreach ($modelDetails as $i => $modelDetail) { ?>

          <div class="receipt-detail receipt-detail-<?= $i ?>">
              <div style="display: flex;">
                  <?= Html::activeHiddenInput($modelDetail, "[$i]opening_hour_id") ?>
                  <?= Html::activeHiddenInput($modelDetail, "[$i]updateType", ['class' => 'update-type']) ?>
                  <div class="VHnWVc gEG0eb">
                    <?= $form->field($modelDetail, "[$i]open_at" )->textInput(['type' => 'time','id'=>'dailyOpenTime', 'style'=>'position: initial;'])->label('Open time'); ?>
                  </div>
                  <div class="VHnWVc gEG0eb">
                    <?= $form->field($modelDetail, "[$i]close_at" )->textInput(['type' => 'time','id'=>'dailyOpenTime', 'style'=>'position: initial;'])->label('Close time'); ?>
                  </div>
                  <span  class="KwjGFb gEG0eb">
                    <?= Html::button('x', ['class' => 'delete-button VfPpkd-Bz112c-LgbsSe yHy1rc eT1oJ ', 'data-target' => "receipt-detail-$i"]) ?>
                  </span>

              </div>
            </div>
        <?php }

        if(sizeof($modelDetails) == 0 && !$modelDetails){
          echo '<span class="closeStore"> You are closed on this day </span>';
        }
        ?>

        <span class="closeStore"> You are closed on this day </span>





          </div>
        <?= Html::submitButton('Add hours', ['name' => 'addRow', 'value' => 'true', 'class' => 'btn addRow','style'=>'    text-align: inherit;']) ?>
        <div style="    margin-top: 20px;">
             <div class="form-group" style="margin-top: 100px !; background: #f4f6f9;  margin-bottom: 0px; background:#f4f6f9 ">
                 <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>

             </div>
        </div>
    </div>



   <?php ActiveForm::end(); ?>

   </section>
   <!-- Data list view end -->
