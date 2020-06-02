
<?php

use yii\helpers\Html;
use kartik\range\RangeInput;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;


$this->title = 'Refund';
$this->params['breadcrumbs'][] = ['label' => 'Order #' . $model->order_uuid, 'url' => ['index', 'restaurantUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = $this->title;
?>

<script>

    var itemCounter = 0.000;
    var itemsSubtotal = 0.000;
    var refundedQty = 0;

    function incrementRefundedAmount(event, maxQty, refunded_qty, itemPrice) {

        inputValue = document.getElementById(refunded_qty).value;


        if (inputValue < maxQty) {
            refundedQty = ++document.getElementById(refunded_qty).value;

            itemCounter++;
            itemsSubtotal += parseFloat(itemPrice);

            document.getElementById("items_subtotal").innerHTML = parseFloat(itemsSubtotal).toFixed(3) + ' KWD';
            document.getElementById("refund_total").innerHTML = parseFloat(itemsSubtotal).toFixed(3) + ' KWD';

            if (itemCounter > 1)
                $('#refunded_items_qty').text(itemCounter + ' items');
            else
                $('#refunded_items_qty').text(itemCounter + ' item');
        }



        if (itemCounter == 0) {
            $(".no-items").show();
            $(".refund-summary").hide();

        } else {
            $(".no-items").hide();
            $(".refund-summary").show();
        }

    }

    function decrementRefundedAmount(maxQty, refunded_qty, itemPrice) {



        inputValue = document.getElementById(refunded_qty).value;

        if (inputValue > 0) {
            document.getElementById(refunded_qty).value--;
            itemCounter--;
            itemsSubtotal -= parseFloat(itemPrice);

            document.getElementById("items_subtotal").innerHTML = parseFloat(itemsSubtotal).toFixed(3) + ' KWD';
            document.getElementById("refund_total").innerHTML = parseFloat(itemsSubtotal).toFixed(3) + ' KWD';

            if (itemCounter > 1)
                $('#refunded_items_qty').text(itemCounter + ' items');
            else
                $('#refunded_items_qty').text(itemCounter + ' item');
        }

        if (itemCounter == 0) {
            $(".no-items").show();
            $(".refund-summary").hide();

        } else {
            $(".no-items").hide();
            $(".refund-summary").show();
        }



    }

</script>


<div class="refund-order">



    <div class="card-body">
        <div class="row">
            <div class=" col-12 col-lg-8 col-xl-8">
                <div class="card">

                    <!-- Insert loop here -->
                    <?php
                    foreach ($model->getOrderItems()->all() as $itemKey => $orderItem) {
                        ?>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div  class="summary-container">
                                        <div>
                                            <section class="item-img-section">
                                                <img  class="item-img" src="<?= $orderItem->item->getItemImage() ?>" alt="Smart Short Sleeve Kimono Romper + Bib - Blue 0-3 M / Blue Whale" class="_3R2Os">
                                            </section>
                                        </div>
                                        <div class="item-data">
                                            <!-- Product name -->
                                            <div>
                                                <span>
                                                    <?= $orderItem->item_name ?>
                                                </span>
                                            </div>
                                            <!-- Product description -->
                                            <div>
                                                <?php
                                                if (!empty($orderItem->getOrderItemExtraOptions()->all())) {
                                                    $extraOptions = '';

                                                    foreach ($orderItem->getOrderItemExtraOptions()->all() as $key => $extraOption) {
                                                        if ($key == 0) {
                                                            $extraOptions .= '<span>' . $extraOption->extra_option_name . '</span>';
                                                        } else {
                                                            $extraOptions .= '<span> / ' . $extraOption->extra_option_name . '</span>';
                                                        }
                                                    }

                                                    echo $extraOptions;
                                                }
                                                ?>
                                                <span>

                                                </span>
                                            </div>
                                            <!-- Product price -->
                                            <div>
                                                <?= Yii::$app->formatter->asCurrency($orderItem->item_price, '', [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5,]) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 text-center">
                                    <div class="test1">



                                        <div class="card-body">
                                            <h5 style="margin-bottom: 20px;">
                                                Price
                                            </h5>
                                            <div class="form-group field-item-item_price required">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <?php
                                                        echo Html::button(
                                                                '-', [
                                                            'class' => 'btn btn-danger bootstrap-touchspin-up',
                                                            'type' => 'button',
                                                            'onclick' => "decrementRefundedAmount('$orderItem->qty', 'refunded_qty'+$itemKey,'$orderItem->item_price')"
                                                                ]
                                                        );
                                                        ?>
                                                    </div>
                                                    <input type="number" readonly id=<?= "refunded_qty" . $itemKey ?> class="form-control" style="background:white;" name="Item[item_price]" value="0" step=".01" aria-required="true">


                                                    <div class="input-group-prepend">
                                                        <span class='input-group-text' style="background: white;">/ <?= $orderItem->qty ?></span>
                                                        <?php
                                                        $test = 'test';


                                                        echo Html::button(
                                                                '+', [
                                                            'class' => 'btn btn-success bootstrap-touchspin-up',
                                                            'type' => 'button',
                                                            'style' => 'border-top-right-radius: 0.25rem; border-bottom-right-radius: 0.25rem;',
                                                            'onclick' => "incrementRefundedAmount(event, '$orderItem->qty', 'refunded_qty'+$itemKey,'$orderItem->item_price')"
                                                                ]
                                                        );
                                                        ?>

                                                    </div>

                                                </div>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                    </div>



                                </div>
                            </div>
                        </div>
                    <?php }
                    ?>
                </div>
            </div>

            <div class="col-12  col-lg-4 col-xl-4">
                <div class="card">
                    <div  class="summary-section">
                        <h2 class="summary-txt">
                            Summary
                        </h2>
                    </div>

                    <div  class="no-items">No items selected.</div>

                    <div class="refund-summary"  style="display:none">

                        <div class="row" style="margin-top:16px">
                            <div class="col-12  col-sm-10 col-md-10 col-lg-8 col-xl-8">
                                <div>
                                    <div><span>Items subtotal</span></div>
                                    <div><span id="refunded_items_qty" style="color: var(--p-text-subdued,#637381);"></span></div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-2 col-md-2 col-lg-4 col-xl-4">
                                <p id="items_subtotal"></p>
                            </div>
                        </div>


                        <div class="row" style="margin-top:16px">
                            <div class="col-12  col-sm-10 col-md-10 col-lg-8 col-xl-8">
                                <div><span>Refund total</span></div>
                            </div>
                            <div class="col-12 col-sm-2 col-md-2 col-lg-4 col-xl-4">
                                <span id="refund_total"></span>
                            </div>
                        </div>


                    </div>

                    <div  class="refund-section">
                        <h2 class="refund-amount-txt">
                            REFUND AMOUNT
                        </h2>

                        <div style="margin-top: 1.6rem;">
                            <span>
                                <?= $orderItem->order->payment_method_name ?>
                            </span>

                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="text" class="form-control">
                            </div>


                            <span class="avaliable-amount-to-refund">    <?= Yii::$app->formatter->asCurrency($orderItem->order->total_price, '', [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5,]) ?> available for refund</span>

                        </div>
                        <div class="refund-btn">
                            <button type="button" class="btn btn-block bg-gradient-success btn-md">
                                Refund $0.00 USD
                            </button>
                        </div>
                    </div>


                </div>


            </div>
