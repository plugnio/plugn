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
$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;

$js = "
$(function () {
  $('.summary').insertAfter('.top');

  $('table.data-list-view.dataTable tbody td').css('padding', '10px');

  $('#restaurant-date_range_picker_with_time').attr('autocomplete','off');
  $('#restaurant-date_range_picker_with_time').attr('style', '  padding-right: 2rem !important; padding-left: 3rem !important; ');

  $('#restaurant-date_range_picker_with_time').change(function(e){
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

    <!-- Data list view starts -->
    <div class="action-btns ">
        <div class="btn-dropdown mr-1 mb-1">
            <div class="btn-group dropdown actions-dropodown">
                <?= Html::a('<i class="feather icon-plus"></i> Create an order manually', ['create', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-outline-primary']) ?>
            </div>
        </div>
    </div>


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
            $form->field($restaurant_model, 'date_range_picker_with_time', [
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
                Html::submitButton('Export to Excel', ['class' => 'btn btn-success', 'id' => 'export-to-excel-btn', 'disabled' => true])
                ?>
            </div>



            <?php ActiveForm::end(); ?>



        </div>
    </div>

    <?php echo $this->render('_search', ['model' => $searchModel, 'restaurant_uuid' => $restaurant_model->restaurant_uuid]); ?>


    <!-- DataTable starts -->
    <div class="table-responsive">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'rowOptions' => function($model) {
                $url = Url::to(['order/view', 'id' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid]);

                return [
                    'onclick' => "window.location.href='{$url}'"
                ];
            },
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'label' => 'Order ID',
                    "format" => "raw",
                    "value" => function($model) {
                        return Html::a('#' . $model->order_uuid, ['order/view', 'id' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid]);
                    }
                ],
                [
                    'attribute' => 'order_created_at',
                    "format" => "raw",
                    "value" => function($model) {
                        return date('d M - h:i A', strtotime($model->order_created_at));
                    }
                ],
                [
                    'attribute' => 'customer_name',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($data->customer_id)
                            return Html::a($data->customer->customer_name, ['customer/view', 'id' => $data->customer_id, 'restaurantUuid' => $data->restaurant_uuid]);
                    },
                    'visible' => function ($data) {
                        return $data->customer_id ? true : false;
                    },
                ],
                'customer_phone_number',
                [
                    'label' => 'When',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->is_order_scheduled ? 'Scheduled' : 'As soon as possible';
                    },
                ],
                [
                    'attribute' => 'order_status',
                    "format" => "raw",
                    "value" => function($model) {

                        if ($model->order_status == Order::STATUS_PENDING)
                            return '<i class="fa fa-circle font-small-3 text-warning mr-50"></i> <span class="text-warning">' . $model->orderStatusInEnglish . '</span>';
                        else if ($model->order_status == Order::STATUS_ACCEPTED)
                            return '<i class="fa fa-circle font-small-3 text-success mr-50"></i> <span class="text-success">' . $model->orderStatusInEnglish . '</span>';
                        else if ($model->order_status == Order::STATUS_BEING_PREPARED)
                            return '<i class="fa fa-circle font-small-3 text-primary mr-50"></i> <span class="text-primary">' . $model->orderStatusInEnglish . '</span>';
                        else if ($model->order_status == Order::STATUS_OUT_FOR_DELIVERY)
                            return '<i class="fa fa-circle font-small-3 text-info mr-50"></i> <span class="text-info">' . $model->orderStatusInEnglish . '</span>';
                        else if ($model->order_status == Order::STATUS_COMPLETE)
                            return '<i class="fa fa-circle font-small-3 text-success mr-50"></i> <span class="text-success">' . $model->orderStatusInEnglish . '</span>';
                        else if ($model->order_status == Order::STATUS_CANCELED)
                            return '<i class="fa fa-circle font-small-3 text-danger mr-50"></i> <span class="text-danger">' . $model->orderStatusInEnglish . '</span>';
                        else if ($model->order_status == Order::STATUS_PARTIALLY_REFUNDED)
                            return '<i class="fa fa-circle font-small-3 text-danger mr-50"></i> <span class="text-danger">' . $model->orderStatusInEnglish . '</span>';
                        else if ($model->order_status == Order::STATUS_REFUNDED)
                            return '<i class="fa fa-circle font-small-3 text-danger mr-50"></i> <span class="text-danger">' . $model->orderStatusInEnglish . '</span>';
                    }
                ],
                [
                    'label' => 'Payment',
                    "format" => "raw",
                    "value" => function($data) {
                        return $data->paymentMethod->payment_method_name;
                    },
                    "visible" => function($data) {
                        return $data->payment->payment_current_status;
                    },
                ],
                'total_price:currency',
            ],
            'layout' => '{summary}{items}{pager}',
            'tableOptions' => ['class' => 'table data-list-view'],
        ]);
        ?>

    </div>
    <!-- DataTable ends -->

</section>
<!-- Data list view end -->
