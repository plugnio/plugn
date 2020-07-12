<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Opening Hours';
$this->params['breadcrumbs'][] = $this->title;

$this->params['restaurant_uuid'] = $restaurantUuid;

$js = "
$('.picker').css('position', 'inherit');
    $('thead').hide();
    $('.top').hide();
    $('.bottom').hide();
    $('.form-group').css('margin', '0px');

    $('#open24Hrs').change(function(e){
      $.each([ 1,2,3,4,5,6,7], function( index, value ) {
        document.getElementById('OpenTime'+index).value = '00:00:00';
        document.getElementById('CloseTime'+index).value = '23:59:59';
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
                    <?= $form->field($daily_hours, "open_at" )->textInput(['class' => 'form-control pickatime-format','id'=>'dailyOpenTime', 'style'=>'position: initial;','value'=>'00:00'])->label('Opens at'); ?>
                </td>
                <td style="padding: 5px  15px" >
                    <?= $form->field($daily_hours, "close_at")->textInput(['class' => 'form-control pickatime-format', 'id'=>'dailyCloseTime','style'=>'position: initial;','value'=>'00:00'])->label('Closes at'); ?>
                </td>
              </tr>


              <?php foreach ($models as $index => $model) { ?>
                <tr>

                      <td style="padding: 5px  15px">
                        <?=
                        $form->field($model, "[$index]is_closed", [
                          'template' => "<span style='margin-right: 10px;padding: 0px; display: block;' class='switch-label'>Closed</span><div class='custom-control custom-switch custom-control-inline'>{input}<label class='custom-control-label' for='customSwitch$index'> </label></div>\n<div class=\"col-lg-8\">{error}</div>",
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
                          <?= $form->field($model, "[$index]open_at" )->textInput(['class' => 'form-control pickatime-format', 'style'=>'position: initial;','id' =>'OpenTime'.$index])->label('Opens at'); ?>
                      </td>
                      <td style="padding: 5px  15px" >
                          <?= $form->field($model, "[$index]close_at")->textInput(['class' => 'form-control pickatime-format', 'style'=>'position: initial;','id' =>'CloseTime'.$index])->label('Closes at'); ?>
                      </td>
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
