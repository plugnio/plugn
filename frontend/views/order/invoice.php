<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\models\Order;
use common\models\BankDiscount;
use common\models\Voucher;

$this->title = 'Invoice #' . $model->order_uuid;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index', 'storeUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = ['label' => 'Order #' . $model->order_uuid, 'url' => ['view', 'id' => $model->order_uuid, 'storeUuid' => $model->restaurant_uuid]];
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
                    <?php if ($model->armada_qr_code_link) { ?>
                        <img src="<?= $model->armada_qr_code_link ?>" width="100" height="100" />
                    <?php } ?>
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

                    <?php
                      if($model->order_mode == Order::ORDER_MODE_DELIVERY){
                    ?>

                    <h6 class="mt-2">Shipping address</h6>
                      <span style="display: block; margin-bottom:3px" >
                        <?=  $model->customer_name ?>
                      </span>
                      <span style="display: block" >
                        <?= $model->area_id ? $model->area_name : $model->address_1  . ', ' ?>
                      </span>
                      <span style="display: block" >
                        <?= $model->area_id ? 'Block: ' . $model->block : $model->address_2  . ', ' ?>
                      </span>
                      <span style="display: block" >
                        <?= $model->area_id ? 'St: ' . $model->street : $model->postalcode . ' ' . $model->city  . ', ' ?>
                      </span>
                      <span style="display: block" >
                        <?= $model->area_id && $model->avenue ? 'Avenue: ' . $model->avenue : ''; ?>
                      </span>
                      <span style="display: block" >
                        <?= $model->area_id ? 'House: ' . $model->house_number :  $model->country_name  . ', ' ?>
                      </span>
                      <span style="display: block" >
                        <?= $model->area_id ?  $model->area->city->city_name . ', ' :  $model->customer_phone_number  ?>
                      </span>
                      <span style="display: block" >
                        <?= $model->area_id ?  $model->country_name . ', ' :  '' ?>
                      </span>
                      <span style="display: block" >
                        <?= $model->area_id ?  $model->customer_phone_number :  '' ?>
                      </span>
                      <?php
                    } else {
                      ?>
                      <h6 class="mt-2">Customer</h6>
                        <span style="display: block; margin-bottom:3px" >
                          <?=  $model->customer_name ?>
                        </span>
                        <span style="display: block" >
                          <?=  $model->customer_phone_number ?>
                        </span>
                    <?php } ?>
                </div>

            </div>

            <div class="col-sm-6 col-12 text-left">


                <div class="invoice-details my-2">

                    <h6 class="mt-2">INVOICE DATE</h6>
                    <p>
                        <?= \Yii::$app->formatter->asDatetime($model->order_created_at, 'MMM dd, yyyy h:mm a') ?>
                    </p>

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
                            [
                                'label' => 'SKU',
                                'format' => 'raw',
                                'value' => 'item.sku',
                            ],
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
                                'value' => function ($orderItem) {
                                    return Yii::$app->formatter->asCurrency($orderItem->item_price, $orderItem->currency->code);
                                }
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
                                    <th>Subtotal</th>
                                    <td><?= \Yii::$app->formatter->asCurrency($model->subtotal, $model->currency->code) ?></td>
                                </tr>
                                <?php
                                if ($model->voucher_id != null && $model->voucher_id && $model->voucher->discount_type !== Voucher::DISCOUNT_TYPE_FREE_DELIVERY) {
                                    $voucherDiscount = $model->voucher->discount_type == Voucher::DISCOUNT_TYPE_PERCENTAGE ? ($model->subtotal * ($model->voucher->discount_amount / 100)) : $model->voucher->discount_amount;
                                    $subtotalAfterDiscount = $model->subtotal - $voucherDiscount;
                                    ?>
                                    <tr>
                                        <th>Voucher Discount</th>
                                        <td><?= Yii::$app->formatter->asCurrency($voucherDiscount, $model->currency->code, [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 3]) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Subtotal After Voucher</th>
                                        <td><?= Yii::$app->formatter->asCurrency($subtotalAfterDiscount, $model->currency->code, [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 3]) ?></td>
                                    </tr>
                                    <?php
                                } else if ($model->bank_discount_id != null && $model->bank_discount_id) {
                                    $bankDiscount = $model->bankDiscount->discount_type == BankDiscount::DISCOUNT_TYPE_PERCENTAGE ? ($model->subtotal * ($model->bankDiscount->discount_amount / 100)) : $model->bankDiscount->discount_amount;
                                    $subtotalAfterDiscount = $model->subtotal - $bankDiscount;
                                    ?>
                                    <tr>
                                        <th>Bank Discount</th>
                                        <td>-<?= Yii::$app->formatter->asCurrency($bankDiscount, $model->currency->code, [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 3]) ?></td>
                                    </tr>
                                <tbody>
                                    <tr>
                                        <th>Subtotal After Bank Discount</th>
                                        <td><?= Yii::$app->formatter->asCurrency($subtotalAfterDiscount, $model->currency->code, [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 3]) ?></td>
                                    </tr>
                                </tbody>
                            <?php } ?>

                            <?php if ($model->order_mode == Order::ORDER_MODE_DELIVERY) { ?>

                                <tr>
                                    <th>Delivery fee</th>
                                    <td><?= \Yii::$app->formatter->asCurrency($model->delivery_fee, $model->currency->code) ?></td>
                                </tr>

                                <?php if ($model->voucher_id != null && $model->voucher_id && $model->voucher->discount_type == Voucher::DISCOUNT_TYPE_FREE_DELIVERY) { ?>
                                    <tr>
                                        <th>Voucher Discount</th>
                                        <td>-<?= Yii::$app->formatter->asCurrency($model->delivery_fee, $model->currency->code, [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 3]) ?></td>

                                    </tr>

                                    <tr>
                                        <th>Delivery fee After Voucher</th>
                                        <td><?= Yii::$app->formatter->asCurrency(0, $model->currency->code, [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 3]) ?></td>
                                    </tr>
                                <?php } ?>

                            <?php } ?>
                            <?php if ($model->tax) { ?>
                            <tr>
                                <th>Tax</th>
                                <td><?= Yii::$app->formatter->asCurrency($model->tax, $model->currency->code, [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 3]) ?></td>
                            </tr>
                          <?php } ?>
                            <tr>
                                <th>Total Price</th>
                                <td><?= Yii::$app->formatter->asCurrency($model->total_price, $model->currency->code, [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 3]) ?></td>
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
