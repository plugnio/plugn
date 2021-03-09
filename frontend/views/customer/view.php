<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\models\Order;
use common\models\AgentAssignment;
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
                        [
                            'attribute' => 'customer_phone_number',
                            "format" => "raw",
                            "value" => function($model) {
                              return '<a href="tel:'. $model->customer_phone_number .'"> '. str_replace(' ', '', $model->customer_phone_number) .' </a>';
                            }
                        ],
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
                              else if ($model->order_status == Order::STATUS_ACCEPTED)
                                  return '<span class="badge" style="background-color:#2898C8;" >' . $model->orderStatusInEnglish . '</span>';
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
                      [
                          'attribute' => 'delivery_fee',
                          "value" => function($data) {
                                  return Yii::$app->formatter->asCurrency($data->delivery_fee, $data->currency->code);
                          },
                      ],
                      [
                          'attribute' => 'total_price',
                          "value" => function($data) {
                                  return Yii::$app->formatter->asCurrency($data->total_price, $data->currency->code);
                          },
                      ],
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
