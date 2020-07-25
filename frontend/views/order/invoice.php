<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\models\Order;
use common\models\Voucher;

$this->title = 'Invoice #' . $model->order_uuid;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index', 'restaurantUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = ['label' => 'Order #' . $model->order_uuid, 'url' => ['view', 'id' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = $this->title;

$this->params['restaurant_uuid'] = $model->restaurant_uuid;
?>
<!-- invoice functionality start -->
<section class="invoice-print mb-1">
    <div>
        <button class="btn btn-primary btn-print mb-1 mb-md-0"> <i class="feather icon-file-text"></i> Print</button>
    </div>
</section>
<!-- invoice functionality end -->
<!-- invoice page -->
<section class="card invoice-page">
    <div id="invoice-template" class="card-body">
        <!-- Invoice Company Details -->
        <div id="invoice-company-details" class="row">
            <div class="col-12  ">
                <div class="media " style="margin-bttom: 20px">
                    <img src="<?= $model->restaurant->getRestaurantLogoUrl() ?>" style="margin-left: auto; margin-right: auto; display:block" />

                </div>
            </div>
            <div class="col-sm-6 col-12 text-right">

            </div>
        </div>
        <!--/ Invoice Company Details -->

        <!-- Invoice Recipient Details -->
        <div id="invoice-customer-details" class="row pt-2">
            <div class="col-sm-6 col-12 text-left">

                <div class="invoice-details my-2">
                    <h6 class="mt-2">INVOICE NO.</h6>
                    <p> <?= '#' . $model->order_uuid ?></p>
                    <h6 class="mt-2">Payment Method</h6>
                    <p>
                        <?= $model->payment_method_name ?>
                    </p>
                    <h6 class="mt-2">When</h6>
                    <p>  <?= $model->is_order_scheduled ? 'Scheduled' : 'As soon as possible'; ?> </p>



                </div>

                <div class="recipient-contact pb-2">
                    <div class="table-responsive">

                        <table class="table table-bordered table-hover" style="margin-top:29px !important;">
                            <tbody>
                                <tr>
                                    <th>Customer name</th>
                                    <td><?= $model->customer_name ?></td>
                                </tr>
                                <tr>
                                    <th>Customer phone number</th>
                                    <td><?= $model->customer_phone_number ?></td>
                                </tr>
                                <?php if ($model->customer_email) { ?>
                                    <tr>
                                        <th>Customer email address</th>
                                        <td><?= $model->customer_email ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-12 text-left">


                <div class="invoice-details my-2">

                    <h6 class="mt-2">INVOICE DATE</h6>
                    <p>
                        <?= \Yii::$app->formatter->asDatetime($model->order_created_at, 'MMM dd, yyyy h:mm a') ?>
                    </p>




                    <?php if ($model->order_mode == Order::ORDER_MODE_PICK_UP) { ?>

                        <h6 class="mt-2">Type</h6>
                        <p>  Pick up </p>

                    <?php } else { ?>
                        <h6 class="mt-2">Type</h6>
                        <p>  Delivery </p>
                    <?php } ?>

                    <h6 class="mt-2">Expected at</h6>
                    <p>
                        <?= \Yii::$app->formatter->asDatetime($model->estimated_time_of_arrival, 'MMM dd, yyyy h:mm a') ?>
                    </p>


                </div>
                <div class="recipient-info my-2">

                    <?php if ($model->order_mode == Order::ORDER_MODE_DELIVERY) { ?>


                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" style="margin-top: 1.5rem !important;">
                                <thead>
                                    <tr>
                                        <th>Area</th>
                                        <th>Block</th>
                                        <th>Street</th>
                                        <?= $model->avenue != null ? '<th>Avenue</th>' : '' ?>
                                        <th>House</th>
                                        <th>Special Directions</th>
                                    </tr>
                                </thead>

                                <tbody>
                                <td><?= $model->area_name ?></td>
                                <td><?= $model->block ?></td>
                                <td><?= $model->street ?></td>
                                <?= $model->avenue != null ? '<td>' . $model->avenue . '</td>' : '' ?></td>
                                <td> <?= $model->house_number ?></td>
                                <td> <?= $model->special_directions ?></td>
                                </tbody>
                            </table>
                        <?php } ?>


                    </div>
                </div>

            </div>

        </div>
        <!--/ Invoice Recipient Details -->

        <!-- Invoice Items Details -->
        <div id="invoice-items-details" class="pt-1 invoice-items-table">
            <div class="row">
                <div class="table-responsive col-12">

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
                        'layout' => '{items}',
                        'tableOptions' => ['class' => 'table table-bordered table-hover'],
                    ]);
                    ?>
                </div>
            </div>
        </div>
        <div id="invoice-total-details" class="invoice-total-table">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <tbody>
                                <tr>
                                    <th>SUBTOTAL</th>
                                    <td><?= \Yii::$app->formatter->asCurrency($model->subtotal) ?></td>
                                </tr>
                                <?php
                                if ($model->voucher_id) {
                                    $voucherDiscount = $model->voucher->discount_type == Voucher::DISCOUNT_TYPE_PERCENTAGE ? ($model->subtotal * ($model->voucher->discount_amount / 100)) : $model->voucher->discount_amount;
                                    $subtotalAfterDiscount = $model->subtotal - $voucherDiscount;
                                    ?>
                                    <tr>
                                        <th>Voucher Discount (<?= $model->voucher->code ?>)</th>
                                        <td>-<?= Yii::$app->formatter->asCurrency($voucherDiscount, '', [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5]) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Subtotal After Voucher</th>
                                        <td><?= Yii::$app->formatter->asCurrency($subtotalAfterDiscount, '', [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5]) ?></td>
                                    </tr>
                                <?php } ?>

                                <tr>
                                    <th>Delivery fee</th>
                                    <td><?= \Yii::$app->formatter->asCurrency($model->delivery_fee) ?></td>
                                </tr>
                                <tr>
                                    <th>TOTAL</th>

                                    <?php if ($model->voucher_id) { ?>
                                        <td><?= Yii::$app->formatter->asCurrency($subtotalAfterDiscount + $model->delivery_fee, '', [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5]) ?></td>
                                    <?php } else { ?>
                                        <td><?= Yii::$app->formatter->asCurrency($model->total_price, '', [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5]) ?></td>
                                    <?php } ?>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<!-- invoice page end -->
