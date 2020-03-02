<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\models\Order;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = 'Order #' . $model->order_id;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="page-title"> <i class="icon-custom-left"></i>
    <p>
        <?= Html::a('Mark it as paid', ['create'], ['class' => 'btn btn-success']) ?>

    </p>
</div>

<!-- Main content -->
<div class="invoice p-3 mb-3">
    <!-- title row -->
    <div class="row">
        <div class="col-12">
            <h4>
                <i class="fas fa-globe"></i> AdminLTE, Inc.
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
                <b>Expected Delivery:</b> TODO <br>
                <b>Payment Method:</b> <?= $model->paymentMethod->payment_method_name ?> <br>
            </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            <b>Order ID:</b> <?= $model->order_id ?><br>
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
                    'order_id',
                    'instructions',
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
                        <td><?= \Yii::$app->formatter->asCurrency($model->calculateOrderItemsTotalPrice()) ?> </td>
                    </tr>
                    <tr>
                        <th>Delivery:</th>
                        <td>TODO</td>
                    </tr>
                    <tr>
                        <th>Total:</th>
                        <td>TODO</td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->


</div>
<!-- /.invoice -->
