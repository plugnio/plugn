<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\models\Order;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = $model->area_name;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

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
                <strong><?= $model->customer_name ?> </strong><br>
                <b>Phone:</b> <?= $model->customer_phone_number ?> <br>
                <?php if ($model->customer_email) { ?>
                    <b>Email:</b> <?= $model->customer_email ?> <br>
                <?php } ?>
                <b>Expected Delivery:</b>TODO <br>
            </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            <b>Order ID:</b> <?= $model->order_id ?><br>
            <b>Order Placed:</b> <?= \Yii::$app->formatter->asDatetime($model->order_created_at) ?> <br>
            <b>Invoice Date:</b> <?= \Yii::$app->formatter->asDatetime($model->order_created_at, 'MMM dd, yyyy') ?> <br>
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
                    <p style="margin-right: 20px;">Area: <?= $model->area_name ?></p>
                    <p style="margin-right: 20px;">Block: <?= $model->block ?></p>
                    <p style="margin-right: 20px;">Street: <?= $model->street ?> </p>
                    <p>House: <?= $model->house_number ?></p>
                </section>
            </address>
        </div>
    </div>
    <!-- Table row -->
    <div class="row">
    
        <div class="col-12 table-responsive">
            <h5 style="margin-bottom: 30px;">Your Order:</h5>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Qty</th>
                        <th>Product</th>
                        <th>Serial #</th>
                        <th>Description</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Call of Duty</td>
                        <td>455-981-221</td>
                        <td>El snort testosterone trophy driving gloves handsome</td>
                        <td>$64.50</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Need for Speed IV</td>
                        <td>247-925-726</td>
                        <td>Wes Anderson umami biodiesel</td>
                        <td>$50.00</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Monsters DVD</td>
                        <td>735-845-642</td>
                        <td>Terry Richardson helvetica tousled street art master</td>
                        <td>$10.70</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Grown Ups Blue Ray</td>
                        <td>422-568-642</td>
                        <td>Tousled lomo letterpress</td>
                        <td>$25.99</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
        <!-- accepted payments column -->
        <div class="col-6">
            <p class="lead">Payment Methods:</p>
            <img src="../../dist/img/credit/visa.png" alt="Visa">
            <img src="../../dist/img/credit/mastercard.png" alt="Mastercard">
            <img src="../../dist/img/credit/american-express.png" alt="American Express">
            <img src="../../dist/img/credit/paypal2.png" alt="Paypal">

            <p class="text-muted well well-sm shadow-none" style="margin-top: 10px;">
                Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles, weebly ning heekya handango imeem
                plugg
                dopplr jibjab, movity jajah plickers sifteo edmodo ifttt zimbra.
            </p>
        </div>
        <!-- /.col -->
        <div class="col-6">
            <p class="lead">Amount Due 2/22/2014</p>

            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th style="width:50%">Subtotal:</th>
                        <td>$250.30</td>
                    </tr>
                    <tr>
                        <th>Tax (9.3%)</th>
                        <td>$10.34</td>
                    </tr>
                    <tr>
                        <th>Shipping:</th>
                        <td>$5.80</td>
                    </tr>
                    <tr>
                        <th>Total:</th>
                        <td>$265.24</td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- this row will not appear when printing -->
    <div class="row no-print">
        <div class="col-12">
            <a href="invoice-print.html" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
            <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Submit
                Payment
            </button>
            <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;">
                <i class="fas fa-download"></i> Generate PDF
            </button>
        </div>
    </div>
</div>
<!-- /.invoice -->


<div class="order-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->order_id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->order_id], [
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
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
//            'order_id',
//            'area_id',
                    'area_name',
                    'area_name_ar',
                    'unit_type',
                    'block',
                    'street',
                    'avenue',
                    'house_number',
                    'special_directions',
                    'customer_name',
                    'customer_phone_number',
                    'customer_email:email',
//            'payment_method_id',
                    'payment_method_name',
                    'order_status',
                ],
                'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
            ])
            ?>
        </div>
    </div>

    <h2>Items</h2>


    <?=
    GridView::widget([
        'dataProvider' => $itemsExtraOpitons,
        'columns' => [
            'orderItem.item.item_name',
            'orderItem.qty',
            'orderItem.item_price:currency',
            'orderItem.instructions',
            'extra_option_name',
            ['class' => 'yii\grid\ActionColumn', 'controller' => 'order-item-extra-options'],
        ],
    ]);
    ?>

</div>
