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
$this->registerCss("
  #DataTables_Table_0_filter{
    display:none !important
  }
  ");
?>

<section id="data-list-view" class="data-list-view-header">


      <?php  echo $this->render('_search', ['model' => $searchModel,'restaurant_uuid' => $restaurant_model->restaurant_uuid]); ?>

    <?php if ($dataProvider->getCount() > 0) { ?>


        <!-- Data list view starts -->
        <div class="action-btns ">
            <div class="btn-dropdown mr-1 mb-1">
                <div class="btn-group dropdown actions-dropodown">
                    <?= Html::a('Create order ', ['create', 'storeUuid' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>

        <!-- DataTable starts -->
        <div class="table-responsive">

            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'rowOptions' => function($model) {
                    $url = Url::to(['order/view', 'id' => $model->order_uuid, 'storeUuid' => $model->restaurant_uuid]);

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
                            return Html::a('#' . $model->order_uuid, ['order/view', 'id' => $model->order_uuid, 'storeUuid' => $model->restaurant_uuid]);
                        }
                    ],
                    [
                        'attribute' => 'order_created_at',
                        "format" => "raw",
                        "value" => function($model) {
                            return date('d M - h:i A', strtotime($model->order_created_at));
                        }
                    ],
                    'country_name',
                    [
                        'attribute' => 'business_location_name',
                        "format" => "raw",
                        "value" => function($model) {
                            return $model->business_location_name ? $model->business_location_name : '';
                        }
                    ],
                    [
                        'attribute' => 'customer_name',
                        'format' => 'raw',
                        'value' => function ($data) {
                            if ($data->customer_id)
                                return Html::a($data->customer_name, ['customer/view', 'id' => $data->customer_id, 'storeUuid' => $data->restaurant_uuid]);
                            else
                                return $data->customer_name;
                        },
                    ],
                    [
                        'attribute' => 'customer_phone_number',
                        "format" => "raw",
                        "value" => function($model) {
                            return '<a href="tel:' . $model->customer_phone_number . '"> ' . str_replace(' ', '', $model->customer_phone_number) . ' </a>';
                        }
                    ],
                    [
                        'attribute' => 'order_status',
                        "format" => "raw",
                        "value" => function($model) {

                            if ($model->order_status == Order::STATUS_PENDING)
                                return '<i class="fa fa-circle font-small-3 text-warning mr-50"></i> <span class="text-warning">' . $model->orderStatusInEnglish . '</span>';
                            else if ($model->order_status == Order::STATUS_ACCEPTED)
                                return '<i  style="color: #2898C8" class="fa fa-circle font-small-3 mr-50"></i> <span style="color: #2898C8">' . $model->orderStatusInEnglish . '</span>';
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
                    [
                        'attribute' => 'total_price',
                        "value" => function($data) {
                            return Yii::$app->formatter->asCurrency($data->total_price, $data->currency->code);
                        },
                    ],
                    [
                        'header' => 'Action',
                        'class' => 'yii\grid\ActionColumn',
                        'template' => ' {update-order-status}',
                        'buttons' => [
                            'update-order-status' => function ($url, $model) {

                                if (($model->order_status == Order::STATUS_DRAFT || $model->order_status == Order::STATUS_ABANDONED_CHECKOUT || $model->order_status == Order::STATUS_CANCELED ) && sizeof($model->selectedItems)) {
                                    return Html::a('Mark as pending', [
                                                'change-order-status',
                                                'order_uuid' => $model->order_uuid,
                                                'storeUuid' => $model->restaurant_uuid,
                                                'status' => Order::STATUS_PENDING,
                                                'redirect' => 'index'
                                                    ], [
                                                'style' => 'margin-right: 10px;',
                                                'class' => ' mb-1  btn btn-warning',
                                                'data' => [
                                                    'confirm' => 'Are you sure you want to mark it as pending?',
                                                    'method' => 'post',
                                                ]
                                    ]);
                                } else if ($model->order_status == Order::STATUS_PENDING) {
                                    return Html::a('Accept order', [
                                                'change-order-status',
                                                'order_uuid' => $model->order_uuid,
                                                'storeUuid' => $model->restaurant_uuid,
                                                'status' => Order::STATUS_ACCEPTED,
                                                'redirect' => 'index'
                                                    ], [
                                                'style' => 'margin-right: 10px; color: white; background-color: #2898C8',
                                                'class' => ' mb-1  btn',
                                    ]);
                                } else if ($model->order_status == Order::STATUS_ACCEPTED) {
                                    return Html::a('Being Prepared', [
                                                'change-order-status',
                                                'order_uuid' => $model->order_uuid,
                                                'storeUuid' => $model->restaurant_uuid,
                                                'status' => Order::STATUS_BEING_PREPARED,
                                                'redirect' => 'index'
                                                    ], [
                                                'style' => 'margin-right: 10px;',
                                                'class' => ' mb-1  btn btn-primary',
                                    ]);
                                } else if ($model->order_status == Order::STATUS_BEING_PREPARED) {
                                    return Html::a('Out for Delivery', [
                                                'change-order-status',
                                                'order_uuid' => $model->order_uuid,
                                                'storeUuid' => $model->restaurant_uuid,
                                                'status' => Order::STATUS_OUT_FOR_DELIVERY,
                                                'redirect' => 'index'
                                                    ], [
                                                'style' => 'margin-right: 10px;',
                                                'class' => ' mb-1  btn btn-info',
                                    ]);
                                } else if ($model->order_status == Order::STATUS_OUT_FOR_DELIVERY) {
                                    return Html::a('Mark as Complete', [
                                                'change-order-status',
                                                'order_uuid' => $model->order_uuid,
                                                'storeUuid' => $model->restaurant_uuid,
                                                'status' => Order::STATUS_COMPLETE,
                                                'redirect' => 'index'
                                                    ], [
                                                'style' => 'margin-right: 10px;',
                                                'class' => ' mb-1  btn btn-success',
                                    ]);
                                }
                            },
                        ],
                    ],
                ],
            ],
            'layout' => '{summary}{items}{pager}',
            'pager' => [
              'maxButtonCount' => 7,
              'prevPageLabel' => 'Previous',
              'nextPageLabel' => 'Next',
              'prevPageCssClass' => 'paginate_button page-item previous',
              'nextPageCssClass' => 'paginate_button page-item next',
          ],
            'tableOptions' => ['class' => 'table data-list-view'],
        ]);
        ?>

        </div>
        <!-- DataTable ends -->

        <?php } else { ?>

        <div class="card">
            <div style="padding: 70px 0; text-align: center;">

                <div>
                    <img src="https://res.cloudinary.com/plugn/image/upload/c_scale,h_120,w_100/v1603286062/a64ef20cde1af82ef69556c7ab33c727_pa1xe7.svg" width="226" alt="" />
                </div>

                <h3>Your orders will show here</h3>

                <p>
                    This is where you’ll fulfill orders, collect payments, and track order progress.
                </p>
    <?= Html::a('Create order', ['create', 'storeUuid' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

            <?php } ?>

</section>
<!-- Data list view end -->
