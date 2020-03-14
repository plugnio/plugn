<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\WorkingDay;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model common\models\WorkingHours */
/* @var $form yii\widgets\ActiveForm */

$js = "

  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })

    //Datemask dd/mm/yyyy
    $('#datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })
    //Datemask2 mm/dd/yyyy
    $('#datemask2').inputmask('mm/dd/yyyy', { 'placeholder': 'mm/dd/yyyy' })
    //Money Euro
    $('[data-mask]').inputmask()

    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY hh:mm A'
      }
    })
    //Date range as a button
    $('#daterange-btn').daterangepicker(
      {
        ranges   : {
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(29, 'days'),
        endDate  : moment()
      },
      function (start, end) {
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
      }
    )

    //Timepicker
    $('#timepicker').datetimepicker({
      format: 'LT'
    })
    
    //Bootstrap Duallistbox
    $('.duallistbox').bootstrapDualListbox()

    //Colorpicker
    $('.my-colorpicker1').colorpicker()
    //color picker with addon
    $('.my-colorpicker2').colorpicker()

    $('.my-colorpicker2').on('colorpickerChange', function(event) {
      $('.my-colorpicker2 .fa-square').css('color', event.color.toString());
    });



  })
";

$this->registerJs($js);
?>

<div class="working-hours-form">

    <?php
    $workingDaysQuery = WorkingDay::find()->asArray()->all();
    $workingDayArray = ArrayHelper::map($workingDaysQuery, 'working_day_id', 'name');

    $form = ActiveForm::begin();
    ?>

    <div class="form-group">
        <label>Time picker:</label>
        <div class="input-group date" id="timepicker" data-target-input="nearest">
            <input type="text" class="form-control datetimepicker-input" data-target="#timepicker"/>
            <div class="input-group-append" data-target="#timepicker" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="far fa-clock"></i></div>
            </div>
        </div>
    </div>

    <?= $form->field($model, 'working_day_id')->dropDownList($workingDayArray) ?>

    <?=
    $form->field($model, 'operating_from', [
        'template' => "{label}"
        . "            <div class='input-group date' id='timepicker' data-target-input='nearest'>"
             
        . "                 {input}"
    
        . "            </div>"
    ])->textInput(['class'=>'datetimepicker-input','data'])
    ?>

    <?= $form->field($model, 'operating_to')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
