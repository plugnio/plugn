<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\models\Order;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Order #' . $model->order_uuid;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index', 'restaurantUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="page-title">
    <p>
        <?= Html::a('Print', ['download-invoice', 'restaurantUuid' => $model->restaurant_uuid, 'order_uuid' => $model->order_uuid], ['class' => 'btn btn-success']); ?>
    </p>
</div>

<div class="order-view">

    <div class="card">

        <div class="card-body">
            <h3>Order details</h3>

            <p>
                <?php
                if ($model->order_status != Order::STATUS_BEING_PREPARED)
                    echo Html::a('Being Prepared', ['change-order-status', 'order_uuid' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid, 'status' => Order::STATUS_BEING_PREPARED], ['style' => 'margin-right: 10px;', 'class' => 'btn btn-warning']);

                if ($model->order_status != Order::STATUS_OUT_FOR_DELIVERY)
                    echo Html::a('Out for Delivery', ['change-order-status', 'order_uuid' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid, 'status' => Order::STATUS_OUT_FOR_DELIVERY], ['style' => 'margin-right: 10px;', 'class' => 'btn btn-primary']);

                if ($model->order_status != Order::STATUS_COMPLETE)
                    echo Html::a('Mark as Complete', ['change-order-status', 'order_uuid' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid, 'status' => Order::STATUS_COMPLETE], ['style' => 'margin-right: 10px;', 'class' => 'btn btn-success']);

                if ($model->order_status != Order::STATUS_CANCELED)
                    echo Html::a('Mark order as cancelled', ['change-order-status', 'order_uuid' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid, 'status' => Order::STATUS_CANCELED], ['style' => 'margin-right: 10px;', 'class' => 'btn btn-danger']);
                ?>
            </p>

            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'attribute' => 'order_status',
                        'format' => 'html',
                        'value' => function ($data) {
                            return '<span  style="font-size:25px; font-weight: 700" >' . $data->orderStatus . '</span>';
                        },
                    ],
                    'total_price:currency',
                    'total_items_price:currency',
                    'delivery_fee:currency',
                    'estimated_time_of_arrival',
                    [
                        'attribute' => 'order_created_at',
                        'format' => 'html',
                        'value' => function ($data) {
                            return Yii::$app->formatter->asRelativeTime($data->order_created_at);
                        },
                    ],
                    [
                        'attribute' => 'special_directions',
                        'format' => 'html',
                        'value' => function ($data) {
                            return $data->aspecial_directions;
                        },
                        'visible' => $model->special_directions,
                    ],
                ],
                'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
            ])
            ?>

        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <?=
            GridView::widget([
                'dataProvider' => $orderItems,
                'sorter' => false,
                'columns' => [
                    'item_name',
                    'customer_instruction',
                    'qty',
                    [
                        'label' => 'Extra Options',
                        'value' => function ($data) {
                            $extraOptions = '';

                            foreach ($data->orderItemExtraOptions as $key => $extraOption) {

                                if ($key == 0)
                                    $extraOptions .= $extraOption['extra_option_name'];
                                else
                                    $extraOptions .= ', ' . $extraOption['extra_option_name'];
                            }

                            return $extraOptions;
                        },
                        'format' => 'raw'
                    ],
                    [
                        'label' => 'Subtotal',
                        'value' => function ($item) {
                            return $item->calculateOrderItemPrice();
                        },
                        'format' => 'currency'
                    ],
                ],
                'layout' => '{items}{pager} ',
                'tableOptions' => ['class' => 'table table-bordered table-hover'],
            ]);
            ?>

        </div>
    </div>



    <div class="card">
        <div class="card-body">
            <h3>Payment details</h3>
            <p>
              <?php  if($model->payment_method_id != 3 && $model->order_status != Order::STATUS_REFUNDED) echo Html::a('Create Refund', ['refund/create', 'restaurantUuid' => $model->restaurant_uuid, 'orderUuid' => $model->order_uuid], ['class' => 'btn btn-success']) ; ?>

            </p>
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'label' => 'Payment type',
                        'format' => 'html',
                        'value' => function ($data) {
                            return $data->payment_method_name;
                        },
                    ],
                    [
                        'label' => 'Payment status',
                        'format' => 'html',
                        'value' => function ($data) {
                            if ($data->payment)
                                return $data->payment->payment_current_status == 'CAPTURED' ? '<span class="badge bg-success" style="font-size:20px;" >' . $data->payment->payment_current_status . '</span>' :'<span class="badge bg-danger" style="font-size:20px;" >' . $data->payment->payment_current_status . '</span>' ;
                        },
                        'visible' => $model->payment_method_id != 3 && $model->payment_uuid,
                    ],
                    [
                        'label' => 'Gateway ID',
                        'format' => 'html',
                        'value' => function ($data) {
                            if ($data->payment)
                                return $data->payment->payment_gateway_order_id;
                        },
                        'visible' => $model->payment_method_id != 3 && $model->payment_uuid,
                    ],
                    [
                        'label' => 'Received Callback',
                        'format' => 'html',
                        'value' => function ($data) {
                            if ($data->payment)
                                return $data->payment->received_callback == true ? 'True' : 'False';
                        },
                        'visible' => $model->payment_method_id != 3 &&  $model->payment
                    ],
                    [
                        'label' => 'Transaction ID',
                        'format' => 'html',
                        'value' => function ($data) {
                            if ($data->payment)
                                return $data->payment->payment_gateway_transaction_id;
                        },
                        'visible' => $model->payment_method_id != 3 && $model->payment_uuid,
                    ],
                ],
                'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
            ])
            ?>

        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h3>Customer Info</h3>

<?=
DetailView::widget([
    'model' => $model,
    'attributes' => [
        'customer_name',
        'customer_phone_number',
        'customer_email:email',
        [
            'attribute' => 'order_mode',
            'format' => 'html',
            'value' => function ($data) {
                return $data->order_mode == Order::ORDER_MODE_DELIVERY ? 'Delivery' : 'Pickup';
            },
        ],
        [
            'label' => 'Address',
            'format' => 'html',
            'value' => function ($data) {
                return $data->area_name . ', Block ' . $data->block . ', St ' . $data->street . ', ' . ($data->avenue ? 'Avenue ' . $data->avenue . ', ' : '' ) . $data->house_number;
            },
            'visible' => $model->order_mode == Order::ORDER_MODE_DELIVERY,
        ],
        [
            'label' => 'Pickup from',
            'format' => 'html',
            'value' => function ($data) {
                return $data->restaurantBranch->branch_name_en;
            },
            'visible' => $model->order_mode == Order::ORDER_MODE_PICK_UP,
        ],
    ],
    'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
])
?>

        </div>
    </div>

</div>

</div>
