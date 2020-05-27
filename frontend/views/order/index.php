<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Order;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;

$js = "
   $(function () {


    //Date range picker
    $('#reservation').daterangepicker()
    //Date range picker with time picker
    $('#reservationtime').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'YYYY-MM-DD H:mm:ss'
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
        $('#reportrange span').html(start.format('YYYY-MM-DD H:mm:ss') + ' - ' + end.format('YYYY-MM-DD H:mm:ss'))
      }
    )



   $(document).ready(function() { 
            $('.input-field').change(function() { 
                alert('Value: ' + $('#reservation').val());
            }); 
        }); 
  })";


$this->registerJs($js);
?>
<!-- Date and time range -->



<!-- /.input group -->
<div class="card">
    <div class="card-body">
        <?php $form = ActiveForm::begin(); ?>

        <?php
//        $form->field($restaurant_model, 'date_range_picker_with_time', [
//            'template' => "{label}"
//            . "<div class='input-group'> <div class='input-group-prepend'> <span class='input-group-text'><i class='far fa-clock'></i></span> </div>{input}"
//            . "</div>"
//            . "{error}{hint}"
//        ])->textInput([
//            'type' => 'text',
//            'class' => 'form-control float-right',
//            'id' => 'reservationtime'
//        ])
        ?>

        <div class="form-group">
            <?= Html::submitButton('Export to Excel', ['class' => 'btn btn-success']) ?>
        </div>



        <?php ActiveForm::end(); ?>


        <?php echo $this->render('_search', ['model' => $searchModel, 'restaurant_uuid' => $restaurant_model->restaurant_uuid]); ?>



        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => 'order_uuid',
                    "format" => "raw",
                    "value" => function($model) {
                        return '#' . $model->order_uuid;
                    }
                ],
                [
                    'attribute' => 'order_created_at',
                    "format" => "raw",
                    "value" => function($model) {
                        return Yii::$app->formatter->asRelativeTime($model->order_created_at);
                    },
                ],
                [
                    'attribute' => 'customer_name',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return Html::a($data->customer->customer_name, ['customer/view', 'id' => $data->customer_id, 'restaurantUuid' => $data->restaurant_uuid]);
                    },
                ],
                [
                    'attribute' => 'order_status',
                    "format" => "raw",
                    "value" => function($model) {
                        if ($model->order_status == Order::STATUS_PENDING)
                            return '<span class="badge bg-warning" >' . $model->orderStatus . '</span>';
                        else if ($model->order_status == Order::STATUS_OUT_FOR_DELIVERY)
                            return '<span class="badge bg-info" >' . $model->orderStatus . '</span>';
                        else if ($model->order_status == Order::STATUS_BEING_PREPARED)
                            return '<span class="badge bg-primary" >' . $model->orderStatus . '</span>';
                        else if ($model->order_status == Order::STATUS_COMPLETE)
                            return '<span class="badge bg-success" >' . $model->orderStatus . '</span>';
                        else if ($model->order_status == Order::STATUS_CANCELED)
                            return '<span class="badge bg-danger" >' . $model->orderStatus . '</span>';
                        else if ($model->order_status == Order::STATUS_REFUNDED)
                            return '<span class="badge bg-danger" >' . $model->orderStatus . '</span>';
                    }
                ],
                [
                    'label' => 'Payment',
                    "format" => "raw",
                    "value" => function($data) {
                        if ($data->payment_uuid)
                            return $data->payment->payment_current_status;
                        else
                            return $data->paymentMethod->payment_method_name;
                    },
                ],
                'total_price:currency',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => ' {view} {update} {delete}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;" class="nav-icon fas fa-eye"></span>', ['view', 'id' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid], [
                                        'title' => 'View',
                                        'data-pjax' => '0',
                                            ]
                            );
                        },
                        'update' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;" class="nav-icon fas fa-edit"></span>', ['update', 'id' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid], [
                                        'title' => 'Update',
                                        'data-pjax' => '0',
                                            ]
                            );
                        },
                        'delete' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;color: red;" class="nav-icon fas fa-trash"></span>', ['delete', 'id' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid], [
                                        'title' => 'Delete',
                                        'data' => [
                                            'confirm' => 'Are you absolutely sure ? You will lose all the information about this category with this action.',
                                            'method' => 'post',
                                        ],
                            ]);
                        },
                    ],
                ],
            ],
            'layout' => '{summary}<div class="box-body table-responsive no-padding">{items}<div class="card-footer clearfix">{pager}</div>',
            'tableOptions' => ['class' => 'table table-bordered table-hover'],
            'summaryOptions' => ['class' => "card-header"],
        ]);
        ?>


    </div>
</div>
