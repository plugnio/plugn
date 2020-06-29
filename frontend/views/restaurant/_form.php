<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\time\TimePicker;
use frontend\models\Agent;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\Area;
use common\models\RestaurantDelivery;
use common\models\RestaurantPaymentMethod;
use common\models\PaymentMethod;
use kartik\file\FileInput;
use common\models\Restaurant;

$js = "
let phoneNumberInput = $('#phoneNumberInput');

phoneNumberInput.change(function(){

    let selection = $(this).val();
    if(selection.length == 8){
      $('#phoneNumberDisplay').show();
    }else{
        $('#phoneNumberDisplay').hide();
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
      operatingFromTimepicker: true,
      operatingToTimepicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY H:mm '
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
    $('#operatingFromTimepicker').datetimepicker({
        format:'H:mm'
    })
    $('#operatingToTimepicker').datetimepicker({
       format:'H:mm'
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

    $(document).ready(function () {
      bsCustomFileInput.init();
    });

";




$this->registerJs($js);


/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="restaurant-form">

    <?php
    $paymentMethodQuery = PaymentMethod::find()->asArray()->all();
    $paymentMethodArray = ArrayHelper::map($paymentMethodQuery, 'payment_method_id', 'payment_method_name');

    $sotredRestaurantPaymentMethod = [];

    if ($model->restaurant_uuid != null) {


        $sotredRestaurantPaymentMethod = RestaurantPaymentMethod::find()
                ->select('payment_method_id')
                ->asArray()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->all();

        $sotredRestaurantPaymentMethod = ArrayHelper::getColumn($sotredRestaurantPaymentMethod, 'payment_method_id');
    }


    $form = ActiveForm::begin();
    ?>

    <?= $form->errorSummary($model); ?>


    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tagline')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tagline_ar')->textInput(['maxlength' => true]) ?>


    <?=
    $form->field($model, 'restaurant_thumbnail_image', [
        'template' => "{label}"
        . "            <div class='input-group'>"
        . "             <div class='custom-file'>"
        . "                 {input}"
        . "                 <label class='custom-file-label' for='exampleInputFile'>Choose file</label>"
        . "             </div>"
        . "            </div>"
    ])->fileInput([
        'multiple' => false,
        'accept' => 'image/*',
        'class' => 'custom-file-input',
    ])
    ?>


    <?=
    $form->field($model, 'restaurant_logo', [
        'template' => "{label}"
        . "            <div class='input-group'>"
        . "             <div class='custom-file'>"
        . "                 {input}"
        . "                 <label class='custom-file-label' for='exampleInputFile'>Choose file</label>"
        . "             </div>"
        . "            </div>"
    ])->fileInput([
        'multiple' => false,
        'accept' => 'image/*',
        'class' => 'custom-file-input',
    ])
    ?>

    <?=
    $form->field($model, 'support_delivery')->dropDownList(
            [
        1 => 'Yes',
        0 => 'No',
            ]
            , ['prompt' => 'Choose...', 'id' => 'supportDeliveryInput', 'class' => 'select2']
    );
    ?>

    <?=
    $form->field($model, 'support_pick_up')->dropDownList(
            [
        1 => 'Yes',
        0 => 'No',
            ]
            , ['prompt' => 'Choose...', 'id' => 'supportPickupInput', 'class' => 'select2']
    );
    ?>



    <?= $form->field($model, 'phone_number')->input('number',['id' => 'phoneNumberInput']) ?>

    <div id="phoneNumberDisplay" <?= $model->phone_number ? "style = display:block " : "style = display:none "?> >
            <?=
             $form->field($model, 'phone_number_display')->radioList([2=>'📞',3=>'+965 12345678',1=>'Dont show phone number button'],['style'=>'display:grid']);
            ?>
    </div>

           <?=
            $form->field($model, 'store_layout')->radioList([Restaurant::STORE_LAYOUT_LIST =>'List', Restaurant::STORE_LAYOUT_GRID=>'Grid'],['style'=>'display:grid']);
            ?>

    <?= $form->field($model, 'restaurant_email')->input('email') ?>

    <?=
        $form->field($model, 'restaurant_email_notification', [
                    'template' => "<label style='display:block;' class='control-label' for='restaurant-restaurant_email'>Email Notification</label>\n{input}\n{hint}\n{error}"
        ])->checkbox([
            'label' => '',
                    'checked' => $model->restaurant_email_notification == 0 ? false : true,
            'data-bootstrap-switch' => '',
            'data-off-color' => 'danger',
            'data-on-color' => 'success'
        ])
    ?>


    <?= $form->field($model, 'armada_api_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'google_analytics_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'facebook_pixil_id')->textInput(['maxlength' => true]) ?>


    <div class="form-group" style="background: #f4f6f9; padding-bottom: 10px; margin-bottom: 0px; padding-bottom: 15px; background:#f4f6f9 ">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
