<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Order;
use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;

$js = "
$(function () {
  $('.summary').insertAfter('.top');

  $('table.data-list-view.dataTable tbody td').css('padding', '10px');

  $('#restaurant-export_orders_data_in_specific_date_range').attr('autocomplete','off');
  $('#restaurant-export_orders_data_in_specific_date_range').attr('style', '  padding-right: 2rem !important; padding-left: 3rem !important; ');

  $('#restaurant-export_orders_data_in_specific_date_range').change(function(e){
    if(e.target.value){
      $('#export-to-excel-btn').attr('disabled',false);
    }else {
      $('#export-to-excel-btn').attr('disabled',true);
    }
});


});
";
$this->registerJs($js);
?>

<section id="data-list-view" class="data-list-view-header">
  <div class="card">
      <div class="card-header">
          <?php
          $form = ActiveForm::begin(
                          [
                              'options' => [
                                  'style' => 'width: 100%;'
                              ]
                          ]
          );
          ?>


          <?=
          $form->field($model, 'export_orders_data_in_specific_date_range', [
              'labelOptions' => ['style' => ' margin-bottom: 10px;   font-size: 1.32rem;'],
              'template' => '
            {label}
         <div class="position-relative has-icon-left">

              {input}

           <div class="form-control-position">
            <i class="feather icon-calendar"></i>
          </div>
        </div>'
          ])->widget(DateRangePicker::classname(), [
              'presetDropdown' => false,
              'convertFormat' => true,
              'pluginOptions' => [
                  'timePicker' => true,
                  'timePickerIncrement' => 15,
                  'locale' => ['format' => 'Y-m-d H:i:s']
              ],
          ]);
          ?>

          <div class="form-group">
              <?=
              Html::submitButton('<i class="fa fa-file-excel-o"></i> Export to Excel', ['class' => 'btn btn-success', 'id' => 'export-to-excel-btn', 'disabled' => true])
              ?>
          </div>


          <?php ActiveForm::end(); ?>


      </div>
  </div>

</section>
<!-- Data list view end -->
