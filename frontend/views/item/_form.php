<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Category;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use common\models\CategoryItem;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model common\models\Item */
/* @var $form yii\widgets\ActiveForm */

$js = "$(function () {
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

  });
  

  $(document).ready(function () {
     bsCustomFileInput.init();
  });

";




$this->registerJs($js);
?>

<div class="item-form">

    <?php
    $categoryQuery = Category::find()->asArray()->all();
    $categoryArray = ArrayHelper::map($categoryQuery, 'category_id', 'category_name');

    $itemCategoryValues = [];

    if ($model->item_uuid != null) {

        $itemCategoryValues = CategoryItem::find()
                ->select('category_id')
                ->asArray()
                ->where(['item_uuid' => $model->item_uuid])
                ->all();

        $itemCategoryValues = ArrayHelper::getColumn($itemCategoryValues, 'category_id');
    }

    $form = ActiveForm::begin([
                'enableClientScript' => false,
    ]);
    ?>


    <?= $form->field($model, 'items_category[]')->dropDownList($categoryArray, ['class' => 'select2']); ?>


    <?= $form->field($model, 'item_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_name_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_description_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort_number')->textInput() ?>

    <?= $form->field($model, 'stock_qty')->textInput() ?>

    <?=
    $form->field($model, 'item_image', [
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


    <?= $form->field($model, 'price')->textInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
