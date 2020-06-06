<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\models\Order;
?>

<!-- Main content -->
<div class="invoice p-3 mb-3">
    <!-- title row -->
    <div class="row" style="margin-bottom:30px">
        <div class="col-12">
            <div>
                <img src="<?= $model->restaurant->getRestaurantLogoUrl() ?>" />
            </div>
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
                <?php if ($model->order_mode == Order::ORDER_MODE_DELIVERY) { ?>
                    <b>Expected Delivery Time:</b> <?= $model->estimated_time_of_arrival ?> <br>
                <?php } ?>
                <b>Payment Method:</b> <?= $model->payment_method_name ?> <br>
                <?php if ($model->order_mode == Order::ORDER_MODE_PICK_UP) { ?>
                    <b>Pickup from:</b> <?= $model->restaurantBranch->branch_name_en ?> <br>
                    <b>Preparation time:</b> <?= \Yii::$app->formatter->asDuration($model->restaurantBranch->prep_time * 60) ?> <br>
                <?php } ?>
            </address>
        </div>
        <!-- /.col -->
        <div class="col-sm-4 invoice-col">
            <b>Order ID:</b>
            <?= $model->order_uuid ?>
                <br>
                <b>Order Placed:</b>
                <?= \Yii::$app->formatter->asDatetime($model->order_created_at) ?>
                    <br>
                    <b>Invoice Date:</b>
                    <?= \Yii::$app->formatter->asDatetime($model->order_created_at, 'MMM dd, yyyy') ?>
                        <br>
                        <b>Order Status:</b>
                        <?= $model->getOrderStatus() ?>
                            <br>
        </div>
        <!-- /.col -->
    </div>

    <!-- /.row -->
    <?php if ($model->order_mode == Order::ORDER_MODE_DELIVERY) { ?>
        <div class="row invoice-info" style="margin-top: 50px;   margin-bottom: 30px;">
            <div>
                <address>
                    <h3 style="margin-bottom:0px;">Billing Address: </h3>
                    <section style="display: inline-flex;">
                        <p>
                            Area: <?= $model->area_name ?>,
                            Block: <?= $model->block ?>,
                            Street: <?= $model->street ?>,
    <?= $model->avenue != null ? 'Avenue: ' . $model->avenue . ', ' : '' ?>
                            House: <?= $model->house_number ?>
                        </p>

                    </section>
                </address>
            </div>
        </div>
        <?php } ?>
            <!-- Table row -->
            <div class="row">

                <div class="col-12 table-responsive">
                    <h3 style="margin-bottom: 20px;">Your Order:</h3>
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
                <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row" style="margin-top:30px">

                <div class="col-6">

                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width:50%">Subtotal:</th>
                                <td>
                                    <?= \Yii::$app->formatter->asCurrency($model->subtotal) ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Delivery:</th>
                                <td>
                                    <?= \Yii::$app->formatter->asCurrency($model->delivery_fee) ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Total:</th>
                                <td>
                                    <?= \Yii::$app->formatter->asCurrency($model->total_price) ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->

</div>
<!-- /.invoice -->
