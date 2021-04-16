<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use common\models\OpeningHour;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Opening Hours';
$this->params['breadcrumbs'][] = $this->title;

$this->params['restaurant_uuid'] = $storeUuid;

$js = "

    $(function(){
    $('#open_modal').click(function (){
      console.log('test');
      $.get($(this).attr('href'), function(data) {
        $('#modal').modal('show').find('#modalContent').html(data)
     });
     return false;
    });


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



<section id="data-list-view" class="data-list-view-header">
  <?php $form = ActiveForm::begin(); ?>


<!-- DataTable starts -->
<div class="card table-responsive">

  <div class="card-body">

    <table class="table ">
        <tbody>
          <tr>


                <td style="padding: 5px  15px">
                  <?=
                  $form->field($daily_hours, "open_24_hrs", [
                    'template' => "<span style='margin-right: 10px;padding: 0px; display: block;' class='switch-label'>Open 24 hours</span><div class='custom-control custom-switch custom-control-inline'>{input}<label class='custom-control-label' for='open24Hrs'> </label></div>\n<div class=\"col-lg-8\">{error}</div>",
                  ])->checkbox([
                      'id' => 'open24Hrs',
                      'class' => 'custom-control-input'
                          ], false)->label(false)
                  ?>
                </td>
                <td style="padding: 5px  15px">
                    Set Daily
                </td>
                <td style="padding: 5px  15px">
                    <?= $form->field($daily_hours, "open_at" )->textInput(['class' => 'form-control pickatime-open-at','id'=>'dailyOpenTime', 'style'=>'position: initial;','value'=>'00:00'])->label('Opens at'); ?>
                </td>
                <td style="padding: 5px  15px" >
                    <?= $form->field($daily_hours, "close_at")->textInput(['class' => 'form-control pickatime-close-at', 'id'=>'dailyCloseTime','style'=>'position: initial;','value'=>'00:00'])->label('Closes at'); ?>
                </td>

              </tr>


              <?php foreach ($models as $index => $model) { ?>
                <tr>

                      <td style="padding: 5px  15px">
                        <?=
                        $form->field($model, "[$index]is_closed", [
                          'template' => "
                          <div class='custom-control custom-switch switch-lg custom-switch-success mr-2 mb-1'>
                            {input}
                            <label class='custom-control-label' for='customSwitch$index'> <span class='switch-text-left'>Closed</span> <span class='switch-text-right'>Open</span> </label>
                            </div>
                            \n
                            <div class=\"col-lg-8\">{error}</div>",
                        ])->checkbox([
                            'checked' => $model->is_closed == 0 ? false : true,
                            'id' => 'customSwitch'.$index,
                            'class' => 'custom-control-input'
                                ], false)->label(false)
                        ?>

                      </td>
                      <td style="padding: 5px  15px">
                        <?= $model->getDayOfWeek() ?>
                      </td>
                      <td style="padding: 5px  15px">
                          <?= $form->field($model, "[$index]open_at" )->textInput(['class' => 'form-control pickatime-open-at', 'style'=>'position: initial;','id' =>'OpenTime'.$index])->label('Opens at'); ?>
                      </td>
                      <td style="padding: 5px  15px" >
                          <?= $form->field($model, "[$index]close_at")->textInput(['class' => 'form-control pickatime-close-at', 'style'=>'position: initial;','id' =>'CloseTime'.$index])->label('Closes at'); ?>
                      </td>

                      <td class="text-center">


                        <?php
                          echo Html::a('<i class="fa fa-plus"></i>', ['/opening-hour/create', 'storeUuid' => $storeUuid, 'dayOfWeek' => $model->day_of_week], ['id' => 'open_modal', 'class' => 'btn btn-xs btn-success']);
                        ?>

                        <?php

                          if(OpeningHour::find()->where(['restaurant_uuid' =>$storeUuid,'day_of_week' => $model->day_of_week ])->count() > 1){
                            echo Html::a('<i class="fa fa-minus"></i>', ['/opening-hour/delete', 'storeUuid' => $storeUuid, 'opening_hour_id' => $model->opening_hour_id, 'dayOfWeek' => $model->day_of_week ], ['id' => 'open_modal', 'class' => 'btn btn-xs btn-danger','data' => [
                                'method' => 'post',
                            ]
                            ]);
                          }

                        ?>


                      </td>
                      <td class="text-right" colspan="2"></td>
                    </tr>

          <?php } ?>

        </tbody>

    </table>



</div>


</div>
<!-- DataTable ends -->
<div class="form-group" style="background: #f4f6f9;  margin-bottom: 0px; background:#f4f6f9 ">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
</div>
<?php ActiveForm::end(); ?>

  </section>
<!-- Data list view end -->
