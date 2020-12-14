
<?php

use yii\helpers\Html;
use kartik\range\RangeInput;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;


$this->title = 'Refund';
$this->params['breadcrumbs'][] = ['label' => 'Order #' . $model->order_uuid, 'url' => ['index', 'storeUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = $this->title;



$js = "
$(document).on('wheel', 'input[type=number]', function (e) {
    $(this).blur();
});

";

$this->registerJs($js);

?>

<script>

    var itemCounter = 0.000;
    var itemsSubtotal = 0.000;
    var refundedQty = 0;

    // $(function(){
    //    $('.refund_total').change(function(){
    //      conole.log('ttst');
    //         getSalutationValue(this.value);
    //           $('.refund_amount_btn').val(this.value);
    //     });
    //
    //
    //   });


    function inputHasBeenUpdated(event) {
        var userInput = document.getElementById("refund_amount").value;
        userInput = parseFloat(document.getElementById("refund_amount").value);



        document.getElementById("refund_amount").value = userInput.toFixed(3) ;
        document.getElementById("refund_amount_btn").innerHTML = userInput.toFixed(3)  + ' KWD';

    }

    function incrementRefundedAmount(event, maxQty, refunded_qty, itemPrice) {

        inputValue = document.getElementById(refunded_qty).value;


        if (inputValue < maxQty) {
            refundedQty = ++document.getElementById(refunded_qty).value;

            itemCounter++;
            itemsSubtotal += parseFloat(itemPrice);

            document.getElementById("items_subtotal").innerHTML = parseFloat(itemsSubtotal).toFixed(3) + ' KWD';
            document.getElementById("refund_total").innerHTML = parseFloat(itemsSubtotal).toFixed(3) + ' KWD';
            document.getElementById("refund_amount_btn").innerHTML = parseFloat(itemsSubtotal).toFixed(3) + ' KWD';
            document.getElementById("refund_amount").value = parseFloat(itemsSubtotal).toFixed(3);

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
            document.getElementById("refund_amount_btn").innerHTML = parseFloat(itemsSubtotal).toFixed(3) + ' KWD';
            document.getElementById("refund_amount").value = parseFloat(itemsSubtotal).toFixed(3);

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


    <?php
    $form = ActiveForm::begin([
                'enableClientValidation' => false,
    ]);
    ?>

    <?= $form->errorSummary($model); ?>

    <div class="card-body">
        <div class="row">
            <div class=" col-12 col-lg-8 col-xl-8">
                <div class="card">

                    <!-- Insert loop here -->
                    <?php
                    foreach ($refunded_items_model as $refundedItemKey => $refundedItem) {
                        ?>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-sm-6">
                                    <div  class="summary-container">
                                        <div>
                                            <section class="item-img-section">
                                                <img  class="item-img" src="<?= "https://res.cloudinary.com/plugn/image/upload/restaurants/". $refundedItem->restaurant->restaurant_uuid ."/items/" .   $itemItmage = $refundedItem->orderItem->getItemImages()->one()->product_file_name ?>" alt="Smart Short Sleeve Kimono Romper + Bib - Blue 0-3 M / Blue Whale" class="_3R2Os">
                                            </section>
                                        </div>
                                        <div class="item-data">
                                            <!-- Product name -->
                                            <div>
                                                <span>
                                                    <?= $refundedItem->orderItem->item_name ?>
                                                </span>
                                            </div>
                                            <!-- Product description -->
                                            <div>
                                                <?php
                                                if (!empty($refundedItem->orderItem->getOrderItemExtraOptions()->all())) {
                                                    $extraOptions = '';

                                                    foreach ($refundedItem->orderItem->getOrderItemExtraOptions()->all() as $key => $extraOption) {
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
                                                <?= Yii::$app->formatter->asCurrency($refundedItem->orderItem->item_price, '', [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5]) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 text-center">
                                    <div class="test1">



                                        <div class="card-body">

                                            <div class="form-group field-item-item_price required">
                                                <div class="input-group">

                                                      <?php
                                                          $order_item_qty = $refundedItem->orderItem->qty;
                                                          $order_item_price = $refundedItem->orderItem->item_price / $order_item_qty;

                                                          // echo $form->field($refundedItem, "[$refundedItemKey]item_uuid")->textInput(['value' => $refundedItem->orderItem->item_uuid,'style'=>'display:none'])->label(false);

                                                          echo $form->field($refundedItem, "[$refundedItemKey]qty", [
                                                              'template' =>
                                                              '  <div class="form-group">
                                                              <div class="input-group">
                                                              <div class="input-group-prepend">' .
                                                          Html::button(
                                                                '-', [
                                                                'class' => 'btn btn-danger bootstrap-touchspin-up',
                                                                'type' => 'button',
                                                                'onclick' => "decrementRefundedAmount('$refundedItem->qty', 'refunded_qty'+$refundedItemKey,'$order_item_price')"
                                                                ])
                                                                . '</div>
                                                                {input}
                                                                <div class="input-group-prepend"> <span class="input-group-text">/ '. $order_item_qty  .'</span> </div>
                                                                <div class="input-group-prepend"> '
                                                              . Html::button(
                                                                '+', [
                                                                'class' => 'btn btn-success bootstrap-touchspin-up',
                                                                'type' => 'button',
                                                                'style' => 'border-top-right-radius: 0.25rem; border-bottom-right-radius: 0.25rem;',
                                                                'onclick' => "incrementRefundedAmount(event, '$order_item_qty', 'refunded_qty'+$refundedItemKey,'$order_item_price')"
                                                              ]) .
                                                              '</div>
                                                              ' .
                                                              '</div> </div>'
                                                          ])->textInput([
                                                              'id' => "refunded_qty" . $refundedItemKey,
                                                              'value' => "0",
                                                              'type' => 'number',
                                                              'min' => 0,
                                                              'max'=> $order_item_qty,
                                                              'step' => "1"
                                                          ])->label(false);
                                                        ?>

                                                </div>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>

                                    </div>



                                </div>

                            <span style="color: var(--p-text-subdued,#637381);">
                              Refunded items will be removed from the order.
                            </span>

                            </div>
                        </div>
                      <?php } ?>
                </div>

                <div class="card">
                  <div class="card-body">

                    <div>
                      <!-- <h2 style="font-family: -apple-system,BlinkMacSystemFont,San Francisco,Segoe UI,Roboto,Helvetica Neue,sans-serif; font-weight: 600; line-height: 2.4rem; margin: 0; font-size: 1.6rem;">
                        Reason for refund
                      </h2> -->

                      <?= $form->field($model, 'reason',
                      [
                        'labelOptions' =>
                              [
                                'style' => 'padding-bottom: 20px;font-family: -apple-system,BlinkMacSystemFont,San Francisco,Segoe UI,Roboto,Helvetica Neue,sans-serif; font-weight: 600; line-height: 2.4rem; margin: 0; font-size: 1.4rem'
                              ],
                        'template' => '
                          {label}
                          {input}
                          {hint}
                          {error}
                          <p style="    color: var(--p-text-subdued,#637381);">
                            Only you and other staff can see this reason.
                          </p>'
                      ])->textInput(); ?>

                    </div>

                  </div>
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
                              <?= $refundedItem->orderItem->order->payment_method_name ?>
                            </span>


                                <?=
                                $form->field($model, 'refund_amount', [
                                    'template' => "{label}"
                                    . "<div class='input-group'> <div class='input-group-prepend'> <span class='input-group-text'>KWD</span> </div>{input}"
                                    . "</div>"
                                    . "{error}{hint}"
                                ])->textInput([
                                    'type' => 'number',
                                    'oninput' => 'inputHasBeenUpdated(event)',
                                    'step' => '0.001',
                                    'id' => 'refund_amount',
                                    'value' => \Yii::$app->formatter->asDecimal(0, 3),
                                    'class' => 'form-control'
                                ])->label(false)
                                ?>

                            <span class="avaliable-amount-to-refund">  <?= Yii::$app->formatter->asCurrency($refundedItem->orderItem->order->total_price, '', [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5,]) ?> available for refund</span>

                        </div>
                        <div class="form-group refund-btn">
                        <?= Html::submitButton('Refund <span id="refund_amount_btn">0.000 KWD</span>', ['class' => 'btn btn-block bg-gradient-success btn-m']) ?>
                                                </div>

                                            </div>

                                        </div>




                                    </div>

                                </div>
                            </div>
                        <?php ActiveForm::end(); ?>

</div>
