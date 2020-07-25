
<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\models\Order;
use common\models\Voucher;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Order #' . $model->order_uuid;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index', 'restaurantUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);


$js = "
$(function () {
  $('.summary').insertAfter('.top');
  $('.top').css('display', 'none');
});

";
$this->registerJs($js);
?>


<?php if ($errorMessage && $successMessage == null) { ?>

    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fa fa-ban"></i> Warning!</h5>
        <?= ($errorMessage) ?>
    </div>
<?php } elseif ($successMessage && $errorMessage == null) { ?>
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h5><i class="icon fa fa-check"></i> Success!</h5>
        <?= ($successMessage) ?>
    </div>
<?php } ?>
<div class="page-title">

    <p>
        <?php
        if ($model->order_status != Order::STATUS_ABANDONED_CHECKOUT && $model->order_status != Order::STATUS_DRAFT) {
            echo Html::a('<i class="feather icon-file-text"></i> View Invoice', ['view-invoice', 'order_uuid' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid], ['class' => 'btn btn-outline-primary mr-1 mb-1', 'style' => 'margin-right: 7px']);
        }
        ?>

        <?php
        if ($model->order_status != Order::STATUS_ABANDONED_CHECKOUT) {
            echo Html::a('Update', ['update', 'id' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid,], ['class' => 'btn btn-primary mr-1 mb-1', 'style' => 'margin-right: 7px;']);
        }
        ?>

        <?php
        if ($model->latitude && $model->longitude) {
            echo Html::a('Get directions', 'https://www.google.com/maps/search/?api=1&query=' . $model->latitude . ',' . $model->longitude, ['class' => 'btn btn-warning mr-1 mb-1', 'style' => 'margin-right: 7px;']);
        }
        ?>

        <?php
        // if ($model->order_status != Order::STATUS_ABANDONED_CHECKOUT && $model->order_status != Order::STATUS_DRAFT ) {
        //     echo Html::a('Refund', ['refund-order', 'order_uuid' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid,], ['class' => 'btn btn-warning', 'style'=>'margin-left: 5px;']) ;
        // }
        ?>

        <?php
        $currentTime = strtotime('now');
        $deliveryTime = strtotime($model->estimated_time_of_arrival);
        $difference = round(abs($deliveryTime - $currentTime) / 3600, 2);


        if ($difference <= 1 && $model->order_mode == Order::ORDER_MODE_DELIVERY && $model->restaurant->armada_api_key != null && $model->tracking_link == null) {
            echo Html::a('Request a driver', ['request-driver-from-armada', 'restaurantUuid' => $model->restaurant_uuid, 'order_uuid' => $model->order_uuid], ['class' => 'btn btn-primary mr-1 mb-1', 'style' => 'margin-right: 7px;']);
        }
        ?>


        <?=
        Html::a('Delete', ['delete', 'id' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid], [
            'class' => 'btn btn-danger mr-1 mb-1',
            'data' => [
                'confirm' => 'Are you sure you want to delete this order?',
                'method' => 'post',
            ],
            'style' => 'margin-right: 7px;'
        ]);
        ?>

    </p>



</div>

<div class="order-view">

    <div class="card">

        <div class="card-body">
            <h3>Order details</h3>

            <p>
                <?php
                if ($model->order_status == Order::STATUS_DRAFT && $model->getOrderItems()->count()) {
                    echo Html::a('Mark as pending', ['change-order-status', 'order_uuid' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid, 'status' => Order::STATUS_PENDING], ['style' => 'margin-right: 10px;', 'class' => 'btn btn-success']);
                }


                if (($model->order_status != Order::STATUS_PARTIALLY_REFUNDED && $model->order_status != Order::STATUS_REFUNDED && $model->order_status != Order::STATUS_ABANDONED_CHECKOUT && $model->order_status != Order::STATUS_DRAFT)) {
                    if ($model->order_status != Order::STATUS_BEING_PREPARED) {
                        echo Html::a('Being Prepared', ['change-order-status', 'order_uuid' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid, 'status' => Order::STATUS_BEING_PREPARED], ['style' => 'margin-right: 10px;', 'class' => 'btn btn-primary mr-1 mb-1']);
                    }

                    if ($model->order_status != Order::STATUS_OUT_FOR_DELIVERY) {
                        echo Html::a('Out for Delivery', ['change-order-status', 'order_uuid' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid, 'status' => Order::STATUS_OUT_FOR_DELIVERY], ['style' => 'margin-right: 10px;', 'class' => 'btn btn-info mr-1 mb-1']);
                    }

                    if ($model->order_status != Order::STATUS_COMPLETE) {
                        echo Html::a('Mark as Complete', ['change-order-status', 'order_uuid' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid, 'status' => Order::STATUS_COMPLETE], ['style' => 'margin-right: 10px;', 'class' => 'btn btn-success mr-1 mb-1']);
                    }

                    if ($model->order_status != Order::STATUS_CANCELED) {
                        echo Html::a('Mark as cancelled', ['change-order-status', 'order_uuid' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid, 'status' => Order::STATUS_CANCELED], ['style' => 'margin-right: 10px;', 'class' => 'btn btn-danger mr-1 mb-1']);
                    }
                }
                ?>
            </p>
            <div class="box-body table-responsive no-padding">

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
                        // 'total_price:currency',
                        // 'subtotal:currency',
                        // 'delivery_fee:currency',
                        [
                            'attribute' => 'order_created_at',
                            "format" => "raw",
                            "value" => function($model) {
                                return date('l d M, Y - h:i A', strtotime($model->order_created_at));
                            }
                        ],
                        [
                            'label' => 'When',
                            'format' => 'raw',
                            'value' => function ($data) {
                                return $data->is_order_scheduled ? 'Scheduled' : 'As soon as possible';
                            },
                        ],
                        [
                            'attribute' => 'estimated_time_of_arrival',
                            "format" => "raw",
                            "value" => function($model) {
                                return date('l d M, Y - h:i A', strtotime($model->estimated_time_of_arrival));
                            }
                        ],
                        [
                            'attribute' => 'special_directions',
                            'format' => 'html',
                            'value' => function ($data) {
                                return $data->special_directions;
                            },
                            'visible' => $model->special_directions,
                        ],
                        [
                            'attribute' => 'tracking_link',
                            'format' => 'html',
                            'value' => function ($data) {
                                return '<a target="_blank" href=' . $data->tracking_link . '>' . $data->tracking_link . '</a>';
                            },
                            'visible' => $model->tracking_link != null,
                        ],
                    ],
                    'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
                ])
                ?>

            </div>
        </div>
    </div>
    <?php if ($orderItems->totalCount > 0) { ?>
        <section id="data-list-view" class="data-list-view-header">

            <div class="card">
                <div class="card-body">
                    <div class="box-body table-responsive no-padding">

                        <?=
                        GridView::widget([
                            'dataProvider' => $orderItems,
                            'sorter' => false,
                            'columns' => [
                                [
                                    'label' => 'Item image',
                                    'format' => 'html',
                                    'value' => function ($data) {
                                        $itemItmage = $data->getItemImages()->one();
                                        if ($itemItmage) {
                                            return Html::img("https://res.cloudinary.com/plugn/image/upload/c_scale,h_105,w_105/restaurants/" . $data->restaurant->restaurant_uuid . "/items/" . $itemItmage->product_file_name);
                                        }
                                    },
                                    'contentOptions' => ['style' => 'width: 100px;'],
                                ],
                                'item_name',
                                'customer_instruction',
                                'qty',
                                [
                                    'label' => 'Extra Options',
                                    'value' => function ($data) {
                                        $extraOptions = '';

                                        foreach ($data->orderItemExtraOptions as $key => $extraOption) {
                                            if ($key == 0) {
                                                $extraOptions .= $extraOption['extra_option_name'];
                                            } else {
                                                $extraOptions .= ', ' . $extraOption['extra_option_name'];
                                            }
                                        }

                                        return $extraOptions;
                                    },
                                    'format' => 'raw'
                                ],
                                [
                                    'label' => 'Subtotal',
                                    'value' => function ($orderItem) {
                                        return $orderItem->item_price;
                                    },
                                    'format' => 'currency'
                                ],
                            ],
                            'layout' => '{items}{pager}',
                            'tableOptions' => ['class' => 'table table-bordered table-hover'],
                        ]);
                        ?>

                    </div>
                </div>
            </div>
        </section>
    <?php } ?>

    <?php
    $totalNumberOfItems = $model->getOrderItems()->count();
    $refunds = $model->getRefunds();

    if ($totalNumberOfItems > 0 || $refunds->count() > 0) {
        ?>
        <div class="card">
            <div class="card-body">
                <h3>
                    <?php
                    if ($model->order_status == Order::STATUS_PARTIALLY_REFUNDED) {
                        echo 'Partially refunded';
                    } elseif ($model->order_status == Order::STATUS_REFUNDED) {
                        echo 'Refunded';
                    } else if ($model->order_status != Order::STATUS_REFUNDED && $model->order_status != Order::STATUS_PARTIALLY_REFUNDED && $model->payment_method_id != 3 && $model->payment->payment_current_status == 'CAPTURED') {
                        echo 'Paid';
                    } else {
                        echo 'Payment pending';
                    }
                    ?>
                </h3>


                <table class="order-details-summary-table"  style="width: 100%; border-collapse: separate; border-spacing: 0; padding: 0.4rem 0.8rem; border: 0; vertical-align: top;">
                    <tbody>
                        <tr>
                            <td colspan="2">Subtotal</td>
                            <td style="float: right;"><?= Yii::$app->formatter->asCurrency($model->subtotal, '', [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5]) ?></td>
                        </tr>
                    </tbody>
                    <?php
                    if ($model->voucher_id) {
                        $voucherDiscount = $model->voucher->discount_type == Voucher::DISCOUNT_TYPE_PERCENTAGE ? ($model->subtotal * ($model->voucher->discount_amount / 100)) : $model->voucher->discount_amount;
                        $subtotalAfterDiscount = $model->subtotal - $voucherDiscount;
                        ?>
                        <tbody>
                            <tr>
                                <td colspan="2">Voucher Discount (<?= $model->voucher->code ?>)</td>
                                <td style="float: right;">-<?= Yii::$app->formatter->asCurrency($voucherDiscount, '', [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5]) ?></td>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <td colspan="2">Subtotal After Voucher</td>
                                <td style="float: right;"><?= Yii::$app->formatter->asCurrency($subtotalAfterDiscount, '', [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5]) ?></td>
                            </tr>
                        </tbody>
                    <?php } ?>
                    <tbody>
                        <tr>
                            <td colspan="2">Delivery fee</td>
                            <td style="float: right;"><?= Yii::$app->formatter->asCurrency($model->delivery_fee, '', [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5]) ?></td>
                        </tr>
                    </tbody>

                    <tbody>
                        <tr>
                            <td colspan="2">Total</td>
                            <td style="float: right;"><?= Yii::$app->formatter->asCurrency($model->total_price, '', [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5]) ?></td>
                        </tr>
                    </tbody>

                    <tbody>
                        <tr>
                            <td colspan="3" class="order-details-summary-table__separator"><hr /></td>
                        </tr>
                    </tbody>
                    <?php
                    if ($model->order_status == Order::STATUS_REFUNDED || $model->order_status == Order::STATUS_PARTIALLY_REFUNDED && ($refunds->count() > 0)) {
                        foreach ($refunds->all() as $refund) {
                            ?>
                            <tbody class="order-details__summary__refund-lines">
                                <tr class="order-details__summary__detail-line-row">
                                    <td>Refunded</td>
                                    <td class="type--subdued">
                                        Reason:  <?= $refund->reason ? $refund->reason : ' –' ?>
                                    </td>
                                    <td style="float: right;">-<?= Yii::$app->formatter->asCurrency($refund->refund_amount, '', [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5]) ?></td>
                                </tr>
                            </tbody>

                            <?php
                        }
                    }
                    ?>
                    <tbody class="order-details__summary__net-payment">
                        <tr>
                            <td class="type--bold" colspan="2">Net payment</td>
                            <td style="float: right;"><?= Yii::$app->formatter->asCurrency($model->total_price, '', [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5]) ?></td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    <?php } ?>

    <?php
    // order's Item
    $refundDataProvider = new \yii\data\ActiveDataProvider([
        'query' => $model->getRefunds()
    ]);


    if ($refundDataProvider->totalCount > 0 && $model->payment) {
        ?>
        <div class="card">
            <div class="card-body">

                <h3 style="margin-bottom: 20px;"> Refunds  </h3>


                <?=
                GridView::widget([
                    'dataProvider' => $refundDataProvider,
                    'sorter' => false,
                    'columns' => [
                        'refund_id',
                        'refund_amount:currency',
                        'refund_status',
                    ],
                    'layout' => '{items}{pager} ',
                    'tableOptions' => ['class' => 'table table-bordered table-hover'],
                ]);
                ?>
            </div>
        </div>

<?php } ?>

    <div class="card">
        <div class="card-body">
            <h3>Payment details</h3>
            <p>
                <?php
//              if($model->payment_method_id != 3 && $model->order_status != Order::STATUS_REFUNDED) echo Html::a('Create Refund', ['refund/create', 'restaurantUuid' => $model->restaurant_uuid, 'orderUuid' => $model->order_uuid], ['class' => 'btn btn-success']) ;
                ?>
            <div class="box-body table-responsive no-padding">

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
                                if ($data->payment) {
                                    return $data->payment->payment_current_status == 'CAPTURED' ? '<span class="badge bg-success" style="font-size:20px;" >' . $data->payment->payment_current_status . '</span>' : '<span class="badge bg-danger" style="font-size:20px;" >' . $data->payment->payment_current_status . '</span>';
                                }
                            },
                            'visible' => $model->payment_method_id != 3 && $model->payment_uuid,
                        ],
                        [
                            'label' => 'Gateway ID',
                            'format' => 'html',
                            'value' => function ($data) {
                                if ($data->payment) {
                                    return $data->payment->payment_gateway_order_id;
                                }
                            },
                            'visible' => $model->payment_method_id != 3 && $model->payment_uuid,
                        ],
                        [
                            'label' => 'Received Callback',
                            'format' => 'html',
                            'value' => function ($data) {
                                if ($data->payment) {
                                    return $data->payment->received_callback == true ? 'True' : 'False';
                                }
                            },
                            'visible' => $model->payment_method_id != 3 && $model->payment
                        ],
                        [
                            'label' => 'Transaction ID',
                            'format' => 'html',
                            'value' => function ($data) {
                                if ($data->payment) {
                                    return $data->payment->payment_gateway_transaction_id;
                                }
                            },
                            'visible' => $model->payment_method_id != 3 && $model->payment_uuid,
                        ],
                    ],
                    'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
                ])
                ?>

            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h3>Customer Info</h3>

            <div class="box-body table-responsive no-padding">

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
                                return $data->area_name . ', Block ' . $data->block . ', St ' . $data->street . ', ' . ($data->avenue ? 'Avenue ' . $data->avenue . ', ' : '') . $data->house_number;
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

</div>
