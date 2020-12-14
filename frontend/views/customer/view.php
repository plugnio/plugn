<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\models\Order;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = $model->customer_name;
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index', 'storeUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="customer-view">

  <p>
    <?= Html::a('Update', ['update', 'id' => $model->customer_id, 'storeUuid' => $model->restaurant_uuid], ['class' => 'btn btn-primary']) ?>
  </p>

  <div class="card">
        <div class="card-body">
            <div class="box-body table-responsive no-padding">

                <?=
                DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'customer_name',
                        'customer_phone_number',
                        'customer_email:email',
                        'customer_created_at',
                        'customer_updated_at',
                    ],
                    'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
                ])
                ?>

            </div>
        </div>
    </div>



        <section id="data-list-view" class="data-list-view-header">
          <h2>Order history</h2>



            <!-- DataTable starts -->
            <div class="table-responsive">

                <?=
                GridView::widget([
                    'dataProvider' => $customersOrdersData,
                    'rowOptions' => function($model) {
                        $url = Url::to(['order/view', 'id' => $model->order_uuid, 'storeUuid' => $model->restaurant_uuid]);

                        return [
                            'onclick' => "window.location.href='{$url}'"
                        ];
                    },
                    'columns' => [
                      ['class' => 'yii\grid\SerialColumn'],
                      [
                          'attribute' => 'order_uuid',
                          "format" => "raw",
                          "value" => function($model) {
                              return '#' . $model->order_uuid;
                          }
                      ],
                      [
                          'label' => 'Order Type',
                          "format" => "raw",
                          "value" => function($model) {
                              if ($model->order_mode == Order::ORDER_MODE_DELIVERY)
                                  return 'Delivery';
                              else
                                  return 'Pickup';
                          }
                      ],
                      [
                          'attribute' => 'order_status',
                          'format' => "raw",
                          'value' => function($model) {
                              if ($model->order_status == Order::STATUS_PENDING)
                                  return '<span class="badge bg-warning" >' . $model->orderStatusInEnglish . '</span>';
                              else if ($model->order_status == Order::STATUS_DRAFT)
                                  return '<span class="badge bg-info" >' . $model->orderStatusInEnglish . '</span>';
                              else if ($model->order_status == Order::STATUS_OUT_FOR_DELIVERY)
                                  return '<span class="badge bg-info" >' . $model->orderStatusInEnglish . '</span>';
                              else if ($model->order_status == Order::STATUS_BEING_PREPARED)
                                  return '<span class="badge bg-primary" >' . $model->orderStatusInEnglish . '</span>';
                              else if ($model->order_status == Order::STATUS_COMPLETE)
                                  return '<span class="badge bg-success" >' . $model->orderStatusInEnglish . '</span>';
                              else if ($model->order_status == Order::STATUS_CANCELED)
                                  return '<span class="badge bg-danger" >' . $model->orderStatusInEnglish . '</span>';
                              else if ($model->order_status == Order::STATUS_PARTIALLY_REFUNDED)
                                  return '<span class="badge bg-warning" >' . $model->orderStatusInEnglish . '</span>';
                              else if ($model->order_status == Order::STATUS_REFUNDED)
                                  return '<span class="badge bg-danger" >' . $model->orderStatusInEnglish . '</span>';
                              else if ($model->order_status == Order::STATUS_ABANDONED_CHECKOUT)
                                  return '<span class="badge bg-danger" >' . $model->orderStatusInEnglish . '</span>';
                          }
                      ],
                      'delivery_fee:currency',
                      'total_price:currency',
                      [
                          'attribute' => 'order_created_at',
                          "format" => "raw",
                          "value" => function($model) {
                              return date('d M - h:i A', strtotime($model->order_created_at));
                          }
                      ],
                    ],
                    'layout' => '{summary}{items}{pager}',
                    'tableOptions' => ['class' => 'table data-list-view'],
                ]);
                ?>

            </div>
            <!-- DataTable ends -->

          </section>
        <!-- Data list view end -->
      </div>
