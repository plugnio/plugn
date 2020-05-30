<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Area;
use yii\helpers\ArrayHelper;
use common\models\Order;
use common\models\City;
use common\models\RestaurantDelivery;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */


$js = "

    let orderModeInput = $('#orderModeInput');
    // On Change of project type input
    orderModeInput.change(function(){
        let selection = $(this).val();
        if(selection == 2){ // Pickup mode
            $('#customer-address').hide();
            $('#pickup-branch').show();
        }else{ //  delivery mode
            $('#customer-address').show();
            $('#pickup-branch').hide();
        }
    });
    
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

    $('input[data-bootstrap-switch]').each(function(){
      $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });

  })
";


$this->registerJs($js);
?>

<div class="order-form">

    <?php
    $areaQuery = $restaurant_model->getAreas()->all();
    $areaList = ArrayHelper::map($areaQuery, 'area_id', 'area_name');


    $form = ActiveForm::begin();
    ?>


    <?= $form->errorSummary($model); ?>

    <?php
    $orderModeOptions = [];
    $model->restaurant->support_delivery ? $orderModeOptions[Order::ORDER_MODE_DELIVERY] = 'Delivery' : null;
    $model->restaurant->support_pick_up ? $orderModeOptions[Order::ORDER_MODE_PICK_UP] = 'Pick up' : null;


    if (is_array($orderModeOptions) && sizeof($orderModeOptions) > 0)
        echo $form->field($model, 'order_mode')->dropDownList($orderModeOptions, ['prompt' => 'Choose...', 'class' => 'select2', 'id' => 'orderModeInput']);



    $restaurantBrnachesQuery = common\models\RestaurantBranch::find()->where(['restaurant_uuid' => $model->restaurant_uuid])->asArray()->all();
    $restaurantBrnachesArray = ArrayHelper::map($restaurantBrnachesQuery, 'restaurant_branch_id', 'branch_name_en');
    ?>


    <div id='customer-address' style='display:none; <?= $model->order_mode  != null && $model->order_mode == Order::ORDER_MODE_PICK_UP ? "display:none" : "display:block" ?>'>
        <?= $form->field($model, 'area_id')->dropDownList($areaList, ['prompt' => 'Choose area name...', 'class' => 'select2'])->label('Area'); ?>

        <?= $form->field($model, 'unit_type')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'block')->input('number') ?>

        <?= $form->field($model, 'street')->input('number') ?>

        <?= $form->field($model, 'avenue')->input('number') ?>

        <?= $form->field($model, 'house_number')->input('number') ?>
    </div>
    

    <div id='pickup-branch' style='<?= $model->order_mode != null && $model->order_mode == Order::ORDER_MODE_DELIVERY ? "display:none" : "" ?>'>
        <?= $form->field($model, 'restaurant_branch_id')->dropDownList($restaurantBrnachesArray, ['prompt' => 'Choose branch...', 'class' => 'select2'])->label('Pickup from'); ?>
    </div>

    <?= $form->field($model, 'customer_name')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'customer_phone_number')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'customer_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'special_directions')->textInput(['maxlength' => true]) ?>


    <div class="form-group" style="background: #f4f6f9; padding-bottom: 10px; margin-bottom: 0px; padding-bottom: 15px; background:#f4f6f9 ">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
