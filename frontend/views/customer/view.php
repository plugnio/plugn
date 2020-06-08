<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\models\Order;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = $model->customer_name;
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index', 'restaurantUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="customer-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->customer_id, 'restaurantUuid' => $model->restaurant_uuid], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->customer_id, 'restaurantUuid' => $model->restaurant_uuid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>
    <div class="card">
        <div class="card-body">
            <div class="box-body table-responsive no-padding">

                <?=
                DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'customer_id',
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


    <h2>Order history</h2>
    <div class="card">

        <?=
        GridView::widget([
            'dataProvider' => $customersOrdersData,
            'columns' => [
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
                            return '<span class="badge bg-warning" >' . $model->orderStatus . '</span>';
                        else if ($model->order_status == Order::STATUS_OUT_FOR_DELIVERY)
                            return '<span class="badge bg-info" >' . $model->orderStatus . '</span>';
                        else if ($model->order_status == Order::STATUS_BEING_PREPARED)
                            return '<span class="badge bg-primary" >' . $model->orderStatus . '</span>';
                        else if ($model->order_status == Order::STATUS_COMPLETE)
                            return '<span class="badge bg-success" >' . $model->orderStatus . '</span>';
                        else if ($model->order_status == Order::STATUS_CANCELED)
                            return '<span class="badge bg-danger" >' . $model->orderStatus . '</span>';
                        else if ($model->order_status == Order::STATUS_PARTIALLY_REFUNDED)
                            return '<span class="badge bg-warning" >' . $model->orderStatus . '</span>';
                        else if ($model->order_status == Order::STATUS_REFUNDED)
                            return '<span class="badge bg-danger" >' . $model->orderStatus . '</span>';
                        else if ($model->order_status == Order::STATUS_ABANDONED_CHECKOUT)
                            return '<span class="badge bg-danger" >' . $model->orderStatus . '</span>';
                    }
                ],
                'delivery_fee:currency',
                'total_price:currency',
                [
                    'attribute' => 'order_created_at',
                    "format" => "raw",
                    "value" => function($model) {
                        return Yii::$app->formatter->asRelativeTime($model->order_created_at);
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => ' {view} {update} {delete}',
                    'controller' => 'order',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;" class="nav-icon fas fa-eye"></span>', ['order/view', 'id' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid], [
                                        'title' => 'View',
                                        'data-pjax' => '0',
                                            ]
                            );
                        },
                    ],
                ],
            ],
            'layout' => '{summary}<div class="card-body"><div class="box-body table-responsive no-padding">{items}<div class="card-footer clearfix">{pager}</div></div>',
            'tableOptions' => ['class' => 'table  table-bordered table-hover'],
            'summaryOptions' => ['class' => "card-header"],
        ]);
        ?>


    </div>
