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
                  <!-- <tr>


                        <td style="padding: 5px  15px">
                    <?php
                    // $form->field($daily_hours, "open_24_hrs", [
                    //   'template' => "<span style='margin-right: 10px;padding: 0px; display: block;' class='switch-label'>Open 24 hours</span><div class='custom-control custom-switch custom-control-inline'>{input}<label class='custom-control-label' for='open24Hrs'> </label></div>\n<div class=\"col-lg-8\">{error}</div>",
                    // ])->checkbox([
                    //     'id' => 'open24Hrs',
                    //     'class' => 'custom-control-input'
                    //         ], false)->label(false)
                    ?>
                        </td>
                        <td style="padding: 5px  15px">
                            Set Daily
                        </td>
                        <td style="padding: 5px  15px">
                    <?php
                    // $form->field($daily_hours, "open_at" )->textInput(['class' => 'form-control pickatime-open-at','id'=>'dailyOpenTime', 'style'=>'position: initial;','value'=>'00:00'])->label('Opens at');
                    ?>
                        </td>
                        <td style="padding: 5px  15px" >
                    <?php
                    // $form->field($daily_hours, "close_at")->textInput(['class' => 'form-control pickatime-close-at', 'id'=>'dailyCloseTime','style'=>'position: initial;','value'=>'00:00'])->label('Closes at');
                    ?>
                        </td>

                      </tr> -->


                    <?php for ($dayOfWeek = 0; $dayOfWeek < 7; $dayOfWeek++) { ?>
                        <tr>

                            <td style="padding: 5px  15px">
                                <?php
                                switch ($dayOfWeek) {
                                    case OpeningHour::DAY_OF_WEEK_SATURDAY:
                                        echo '<h4><b>Saturday</b></h4>';
                                        break;
                                    case OpeningHour::DAY_OF_WEEK_SUNDAY:
                                        echo '<h4><b>Sunday</b></h4>';
                                        break;
                                    case OpeningHour::DAY_OF_WEEK_MONDAY:
                                        echo '<h4><b>Monday</b></h4>';
                                        break;
                                    case OpeningHour::DAY_OF_WEEK_TUESDAY:
                                        echo '<h4><b>Tuesday</b></h4>';
                                        break;
                                    case OpeningHour::DAY_OF_WEEK_WEDNESDAY:
                                        echo '<h4><b>Wednesday</b></h4>';
                                        break;
                                    case OpeningHour::DAY_OF_WEEK_THURSDAY:
                                        echo '<h4><b>Thursday</b></h4>';
                                        break;
                                    case OpeningHour::DAY_OF_WEEK_FRIDAY:
                                        echo '<h4><b>Friday</b></h4>';
                                        break;
                                }
                                ?>

                                                                        <?php
                                                                          echo Html::a('<i class="fa fa-plus"></i>', ['/opening-hour/create', 'storeUuid' => $storeUuid, 'dayOfWeek' => $dayOfWeek], ['id' => 'open_modal', 'class' => 'btn btn-xs btn-success']);
                                                                        ?>

                            </td>

                    <table class="table">
                        <tbody>



                            <?php
                            foreach ($models as $index => $model) {
                                if ($model->day_of_week == $dayOfWeek) {
                                    ?>
                                    <tr>

                                        <td style="padding: 5px  15px">
                                            <?= $form->field($model, "[$index]open_at")->textInput(['class' => 'form-control pickatime-open-at', 'style' => 'position: initial;', 'id' => 'OpenTime' . $index])->label('Opens at'); ?>
                                        </td>
                                        <td style="padding: 5px  15px" >
                                          <?= $form->field($model, "[$index]close_at")->textInput(['class' => 'form-control pickatime-close-at', 'style' => 'position: initial;', 'id' => 'CloseTime' . $dayOfWeek])->label('Closes at'); ?>
                                        </td>

                                        <td class="text-center">


                                      <?php
                                          echo Html::a('<i class="fa fa-minus"></i>', ['/opening-hour/delete', 'storeUuid' => $storeUuid, 'opening_hour_id' => $model->opening_hour_id, 'dayOfWeek' => $model->day_of_week], ['id' => 'open_modal', 'class' => 'btn btn-xs btn-danger', 'data' => [
                                                  'method' => 'post',
                                              ]
                                          ]);
                                      ?>


                                        </td>
                                        <td class="text-right" colspan="2"></td>
                                    </tr>

                            <?php
                          } ?>

                                <?php

                            }

                            $isClosed = array_search($dayOfWeek, array_column($models, 'day_of_week'));

                            if(  !$isClosed && gettype( $isClosed) == 'boolean'){
                            ?>

                            <td class="text-center">
                              <h5>
                                Closed
                              </h5>
                            </td>

                            <?php

                        }


                        ?>




                        </tbody>
                    </table>


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
