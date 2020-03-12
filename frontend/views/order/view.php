<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\models\Order;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = 'Order #' . $model->order_uuid;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="page-title"> <i class="icon-custom-left"></i>
    <p>
        <?php
        
        if ($model->order_status != Order::STATUS_BEING_PREPARED)
            echo Html::a('Being Prepared', ['change-order-status', 'id' => $model->order_uuid, 'status' => Order::STATUS_BEING_PREPARED], ['style' => 'margin-right: 10px;', 'class' => 'btn btn-warning']);

        if ($model->order_status != Order::STATUS_OUT_FOR_DELIVERY)
            echo Html::a('Out for Delivery', ['change-order-status', 'id' => $model->order_uuid, 'status' => Order::STATUS_OUT_FOR_DELIVERY], ['style' => 'margin-right: 10px;', 'class' => 'btn btn-primary']);

        if ($model->order_status != Order::STATUS_COMPLETE)
            echo Html::a('Mark as Complete', ['change-order-status', 'id' => $model->order_uuid, 'status' => Order::STATUS_COMPLETE], ['style' => 'margin-right: 10px;', 'class' => 'btn btn-success']);
       
        ?>
    </p>
</div>

<!-- Main content -->
<div class="invoice p-3 mb-3">
    <!-- title row -->
    <div class="row">
        <div class="col-12">
            <h4>
                <i class="fas fa-globe"></i> <?= $model->restaurant->name ?>
                <small class="float-right">Date: <?= \Yii::$app->formatter->asDatetime($model->order_created_at, 'MMM dd, yyyy') ?></small>
            </h4>
        </div>
        <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
            <address>
                <b>Customer Name:</b> <?= $model->customer_name ?> <br>
                <b>Phone:</b> <?= $model->customer_phone_number ?> <br>
                <?php if ($model->customer_email) { ?>
                    <b>Email:</b> <?= $model->customer_email ?> <br>
                <?php } ?>
                <b>Expected Delivery:</b> <?= Yii::$app->formatter->asDuration($model->restaurantDelivery->min_delivery_time * 60 ) ?> <br>
                <b>Payment Method:</b> <?= $model->paymentMethod->payment_method_name ?> <br>
            </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            <b>Order ID:</b> <?= $model->order_uuid ?><br>
            <b>Order Placed:</b> <?= \Yii::$app->formatter->asDatetime($model->order_created_at) ?> <br>
            <b>Invoice Date:</b> <?= \Yii::$app->formatter->asDatetime($model->order_created_at, 'MMM dd, yyyy') ?> <br>
            <b>Invoice Status:</b> <?= $model->getOrderStatus() ?> <br>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    <div class="row invoice-info"  style="margin-top: 50px;">
        <div class="col-sm-4 invoice-col">
            <address>
                <h3>Hey <?= $model->customer_name ?>, </h3>
                <p>Thanks for ordering</p>
            </address>
        </div>
    </div>
    <!-- /.row -->
    <div class="row invoice-info"  style="margin-top: 50px;     margin-bottom: 30px;">
        <div class="col-sm-4 invoice-col">
            <address>
                <h3>Billing Address: </h3>
                <section style="display: inline-flex;">
                    <p style="margin-right: 20px;">Area: <br><?= $model->area_name ?></p>
                    <p style="margin-right: 20px;">Block: <br><?= $model->block ?></p>
                    <p style="margin-right: 20px;">Street:<br> <?= $model->street ?> </p>
                    <p>House: <br><?= $model->house_number ?></p>
                </section>
            </address>
        </div>
    </div>
    <!-- Table row -->
    <div class="row">

        <div class="col-12 table-responsive">
            <h5 style="margin-bottom: 30px;">Your Order:</h5>
            <?=
            GridView::widget([
                'dataProvider' => $orderItems,
                'sorter' => false,
                'columns' => [
                    'item_name',
                    'instructions',
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
                            return $item->order->total_items_price;
                        },
                        'format' => 'currency'
                    ],
                ],
                'layout' => '{items}{pager} '
            ]);
            ?>

        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <!-- /.col --> <div class="col-6">
            <!-- this row will not appear when printing -->
            <div class="row no-print">
                <div class="col-12">
                    <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                </div>
            </div>
        </div>
        <div class="col-6">
            <p class="lead">Amount Due <?= \Yii::$app->formatter->asDatetime($model->order_created_at, 'MMM dd, yyyy') ?></p>

            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width:50%">Subtotal:</th>
                        <td><?= \Yii::$app->formatter->asCurrency($model->total_items_price) ?> </td>
                    </tr>
                    <tr>
                        <th>Delivery:</th>
                        <td><?= \Yii::$app->formatter->asCurrency($model->delivery_fee) ?> </td>
                    </tr>
                    <tr>
                        <th>Total:</th>
                        <td><?= \Yii::$app->formatter->asCurrency($model->total_price) ?> </td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->


</div>
<!-- /.invoice -->
