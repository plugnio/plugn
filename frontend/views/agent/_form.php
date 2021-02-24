<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\AgentAssignment;

/* @var $this yii\web\View */
/* @var $model common\models\Agent */
/* @var $form yii\widgets\ActiveForm */

$js = "  $(function () {
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

    $('input[data-bootstrap-switch]').each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });

  })";

$this->registerJs($js);
?>

<div class="card agent-form">

  <div class="card-body">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->errorSummary($model,['header' => '<h4 class="alert-heading">Please fix the following errors:</h4>']); ?>


    <?= $form->field($model, 'agent_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'agent_email')->input('email') ?>


    <?=
      $form->field($model, 'email_notification', [
          'template' => '
          <div class="vs-checkbox-con vs-checkbox-primary">
              {input}
              <span class="vs-checkbox">
                  <span class="vs-checkbox--check">
                      <i class="vs-icon feather icon-check"></i>
                  </span>
              </span>
              <span class="">{label}</span>
          </div>
          <div class=\"col-lg-8\">{error}</div>
          ',
      ])->checkbox([
          'checked' => $model->email_notification ? true : false,
          'id' => 'trackQuantityInput',
              ], false)
    ?>


    <?php

      if(Yii::$app->user->identity->isOwner($storeUuid)) {
        echo $form->field($model, 'reminder_email', [
            'template' => '
            <div class="vs-checkbox-con vs-checkbox-primary">
                {input}
                <span class="vs-checkbox">
                    <span class="vs-checkbox--check">
                        <i class="vs-icon feather icon-check"></i>
                    </span>
                </span>
                <span class="">Send me reminder email if order not accepted in 5 minutes</span>
            </div>
            <div class=\"col-lg-8\">{error}</div>
            ',
        ])->checkbox([
            'checked' => $model->reminder_email ? true : false,
            'id' => 'trackQuantityInput',
          ], false);
      }

    ?>


    <div class="form-group" style="background: #f4f6f9;; margin-bottom: 0px; background:#f4f6f9 ">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
</div>
