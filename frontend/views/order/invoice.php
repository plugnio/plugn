<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\models\Order;

$this->title = 'Invoice #' . $model->order_uuid;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index', 'restaurantUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = ['label' => 'Order #' . $model->order_uuid, 'url' => ['view', 'id' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = $this->title;

$this->params['restaurant_uuid'] = $model->restaurant_uuid;
?>
<!-- invoice functionality start -->
<section class="invoice-print mb-1">
    <div class="row">

        <fieldset class="col-12 col-md-5 mb-1 mb-md-0">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Email" aria-describedby="button-addon2">
                <div class="input-group-append" id="button-addon2">
                    <button class="btn btn-outline-primary" type="button">Send Invoice</button>
                </div>
            </div>
        </fieldset>
        <div class="col-12 col-md-7 d-flex flex-column flex-md-row justify-content-end">
            <button class="btn btn-primary btn-print mb-1 mb-md-0"> <i class="feather icon-file-text"></i> Print</button>
        </div>
    </div>
</section>
<!-- invoice functionality end -->
<!-- invoice page -->
<section class="card invoice-page">
    <div id="invoice-template" class="card-body">
        <!-- Invoice Company Details -->
        <div id="invoice-company-details" class="row">
            <div class="col-sm-6 col-12 text-left pt-1">
                <div class="media pt-1">
                    <img src="<?= $model->restaurant->getRestaurantLogoUrl() ?>" />

                </div>
            </div>
            <div class="col-sm-6 col-12 text-right">

            </div>
        </div>
        <!--/ Invoice Company Details -->

        <!-- Invoice Recipient Details -->
        <div id="invoice-customer-details" class="row pt-2">
            <div class="col-sm-6 col-12 text-left">

                <div class="invoice-details mt-2">
                    <h6>INVOICE NO.</h6>
                    <p> <?= '#' . $model->order_uuid ?></p>
                    <h6 class="mt-2">INVOICE DATE</h6>
                    <p>
<?= \Yii::$app->formatter->asDatetime($model->order_created_at, 'MMM dd, yyyy') ?>
                    </p>

                    <?php if ($model->order_mode == Order::ORDER_MODE_PICK_UP) { ?>

                      <h6 class="mt-2">Preparation time</h6>

                    <?php } else { ?>
                      <h6 class="mt-2">Expected Delivery Time</h6>

                    <?php } ?>

                    <p>
<?= \Yii::$app->formatter->asDatetime($model->order_created_at, 'MMM dd, yyyy H:mm') ?>
                    </p>

                    <h6 class="mt-2">Payment Method</h6>
                    <p>
<?= $model->payment_method_name ?>
                    </p>

                </div>

                <div class="recipient-info my-2">


                </div>
                <div class="recipient-contact pb-2">
<?php if ($model->customer_email) { ?>

                        <p>
                            <i class="feather icon-user"></i>
    <?= $model->customer_name ?>
                        </p>


                        <p>
                            <i class="feather icon-mail"></i>
    <?= $model->customer_email ?>
                        </p>

<?php } ?>




                    <p>
                        <i class="feather icon-phone"></i>
<?= $model->customer_phone_number ?>
                    </p>
                </div>
            </div>

            <div class="col-sm-6 col-12 text-left">

                <div class="invoice-details mt-2">


                    <h6 class="mt-2">Expected Delivery Time</h6>
                    <p>
<?= \Yii::$app->formatter->asDatetime($model->order_created_at, 'MMM dd, yyyy H:mm') ?>
                    </p>


                </div>

                <div class="recipient-info my-2">

<?php if ($model->order_mode == Order::ORDER_MODE_PICK_UP) { ?>

                        <h6 class="mt-2">Type</h6>
                        <p>  Pick up </p>
                        <h6 class="mt-2">Pickup from:</h6>
                        <p>  <?= $model->restaurantBranch->branch_name_en ?>   </p>

<?php } ?>

                    <?php if ($model->order_mode == Order::ORDER_MODE_DELIVERY) { ?>

                        <h6 class="mt-2">Type</h6>
                        <p>  Delivery </p>

                        <h6 class="mt-2">Address</h6>
                        <p>
                            <b>Area:</b> <?= $model->area_name ?>,
                            <b>Block:</b> <?= $model->block ?>,
                            <b>Street:</b> <?= $model->street ?>,
    <?= $model->avenue != null ? '<b>Avenue: </b>' . $model->avenue . ', ' : '' ?>
                            <b>House:</b> <?= $model->house_number ?>,
                        </p>

<?php } ?>


                </div>

            </div>

        </div>
        <!--/ Invoice Recipient Details -->

        <!-- Invoice Items Details -->
        <div id="invoice-items-details" class="pt-1 invoice-items-table">
            <div class="row">
                <div class="table-responsive col-12">
                    <!-- <table class="table table-borderless">
                        <thead>
                            <tr>
                                <th>TASK DESCRIPTION</th>
                                <th>HOURS</th>
                                <th>RATE</th>
                                <th>AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Website Redesign</td>
                                <td>60</td>
                                <td>15 USD</td>
                                <td>90000 USD</td>
                            </tr>
                            <tr>
                                <td>Newsletter template design</td>
                                <td>20</td>
                                <td>12 USD</td>
                                <td>24000 USD</td>
                            </tr>
                        </tbody>
                    </table> -->


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
                        'tableOptions' => ['class' => 'table table-borderless'],
                    ]);
                    ?>
                </div>
            </div>
        </div>
        <div id="invoice-total-details" class="invoice-total-table">
            <div class="row">
                <div class="col-7">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <th>SUBTOTAL</th>
                                    <td><?= \Yii::$app->formatter->asCurrency($model->subtotal) ?></td>
                                </tr>
                                <tr>
                                    <th>Delivery fee</th>
                                    <td><?= \Yii::$app->formatter->asCurrency($model->delivery_fee) ?></td>
                                </tr>
                                <tr>
                                    <th>TOTAL</th>
                                    <td><?= \Yii::$app->formatter->asCurrency($model->total_price) ?></td>
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
