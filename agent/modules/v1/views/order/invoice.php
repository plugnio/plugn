<!-- todo: update language support -->

<div class="ion-content">

    <div class="ion-card ion-no-padding card-invoice" id="invoice">

        <table>
            <tr>
                <td>
                    <div class="ion-card-header">
                        <div class="media">

                            <!--
                            <img *ngIf="order.armada_qr_code_link" [src]="order.armada_qr_code_link" width="100" height="100"></img>
                              -->

                            <?php if(!$order->restaurant->logo) { ?>
                                <img src="<?= $defaultLogo ?>"></img>
                            <?php } else { ?>
                                <img class="ion-float-start" width="75" height="75"
                                     src="https://res.cloudinary.com/plugn/image/upload/c_scale,h_105,w_105/restaurants/<?= $order->restaurant_uuid . '/logo/'
                                     . $order->restaurant->logo ?>"></img>
                            <?php } ?>

                            <?php if($order->armada_qr_code_link) { ?>
                                <img class="ion-float-end" src="<?= $order->armada_qr_code_link ?>" width="70" height="70"></img>
                            <?php } ?>
                        </div>
                    </div>
                </td>
                <td align="right">
                    <h2 class="txt-invoice-heading">
                        <b>Invoice</b> <br/>#<?= $order->order_uuid ?>

                        <?php if($order->payment && $order->payment->received_callback && $order->payment->payment_current_status == 'CAPTURED') { ?>
                            <div class="ion-badge status-paid">
                                Paid
                            </div>
                        <?php } else { ?>
                            <div class="ion-badge status-unpaid">
                                Unpaid
                            </div>
                        <?php } ?>
                    </h2>
                </td>
            </tr>
        </table>


        <div class="ion-padding ion-margin">
            <!-- Invoice Recipient Details -->

            <table style="width: 100%">
                <tr style="font-family: Nunito" id="invoice-company-details" class="row">
                    <td style="width:50%" class="text-left">

                    <h3 class="invoice-logo"><?= $order->restaurant->name ?></h3>
                    <!-- <p class="card-text mb-25">Office 149, 450 South Brand Brooklyn</p>
                    <p class="card-text mb-25">San Diego County, CA 91905, USA</p>
                    <p class="card-text mb-0">+1 (123) 456 7891, +44 (876) 543 2198</p> -->

                    <?php if($order->order_mode == 1) { ?>

                      <?php if(!$order->address_1 && !$order->address_2) { ?>

                          <?php if($order->area_id && $order->block) { ?>
                          <p style="font-family: Nunito;">
                              Block <?= $order->block ?>
                          </p>
                          <?php } ?>

                          <?php if($order->street) { ?>
                          <p style="font-family: Nunito" >
                              Street <?= $order->street ?>
                          </p>
                          <?php } ?>

                          <?php if((strtolower($order->unit_type) == 'apartment' || strtolower($order->unit_type) == 'office')) { ?>

                              <?php if($order->avenue) { ?>
                                  <p style="font-family: Nunito"  class="txt-avenue">
                                      Avenue <?= $order->avenue ?>
                                  </p>
                              <?php } ?>

                              <?php if($order->floor) { ?>
                                  <p style="font-family: Nunito"  class="txt-building">
                                      Floor <?= $order->floor ?>
                                  </p>
                              <?php } ?>

                              <?php if(strtolower($order->unit_type) == 'apartment' && $order->apartment) { ?>
                                  <p style="font-family: Nunito"  class="txt-building">
                                      Apartment No. <?= $order->apartment ?>
                                  </p>
                              <?php } ?>

                              <?php if(strtolower($order->unit_type) == 'office' && $order->office) { ?>
                                  <p style="font-family: Nunito"  class="txt-building">
                                      Office No. <?= $order->office ?>
                                  </p>
                              <?php } ?>

                              <?php if(strtolower($order->unit_type) != 'house' && $order->house_number) { ?>
                                  <p style="font-family: Nunito"  class="txt-building">
                                      Building <?= $order->house_number ?>
                                  </p>
                              <?php } ?>

                          <?php } ?>

                          <?php if(strtolower($order->unit_type) != 'apartment' && strtolower($order->unit_type) != 'office') { ?>

                              <?php if($order->avenue) { ?>
                              <p class="txt-avenue">
                                  Avenue <?= $order->avenue ?>
                              </p>
                              <?php } ?>

                              <?php if(strtolower($order->unit_type) == 'house' &&  $order->house_number ) { ?>
                              <p class="txt-house-number">
                                  House No. <?= $order->house_number ?>
                              </p>
                              <?php } ?>

                              <?php if(strtolower($order->unit_type) != 'house'  &&  $order->house_number ) { ?>
                              <p class="txt-building">
                                  Building <?= $order->house_number ?>
                              </p>
                              <?php } ?>

                          <?php } ?>

                          <?php if($order->area_id) { ?>
                              <p style="font-family: Nunito" >
                                  <?= $order->area_name ?>
                              </p>
                          <?php } ?>

                        <?php } else { ?>

                        <?php if($order->address_1) { ?>
                            <p class="txt-address-1">
                                <?= $order->address_1 ?>
                            </p>
                        <?php

                          }

                          if($order->address_2) { ?>
                            <p class="txt-address-2">
                                <?= $order->address_2 ?>
                            </p>
                          <?php }
                        }

                         if(($order->area && $order->area->city) || $order->city) { ?>
                        <p style="font-family: Nunito" >
                            <?= $order->area_id && $order->area->city ? $order->area->city->city_name : $order->city ?> <?= $order->postalcode ?>
                        </p>
                        <?php } ?>

                        <?php if($order->country_name) { ?>
                        <p style="font-family: Nunito" >
                            <?= $order->country_name ?>
                        </p>
                        <?php } ?>

                        <p style="font-family: Nunito" class="ltr">
                            <?= $order->customer_phone_number ?>
                        </p>
                    <?php } else { ?>

                        <p style="font-family: Nunito" class=" ltr">
                            <?= $order->customer_phone_number ?>
                        </p>
                    <?php } ?>

                    </td>

                    <td style="width:50%" class="text-left">

                    </td>
                </tr>

                <tr>
                    <td>
                        <table>
                            <tr>
                                <td><strong>Customer</strong> : <?= $order->customer_name ?></td>
                            </tr>
                            <tr>
                                <td><strong>Payment Method</strong> : <?= $order->payment_method_name ?></td>
                            </tr>

                                <tr>
                                    <td>
                                      <?php if($order->special_directions) { ?>
                                        <strong>Special Direction</strong>:<?=($order->special_directions) ? $order->special_directions : '';?>
                                      <?php } ?>
                                    </td>
                                </tr>

                        </table>
                    </td>
                    <td>
                            <table>
                                <tr>
                                    <td><strong>Invoice Date</strong> : <?= \Yii::$app->formatter->asDatetime($order->order_created_at, 'MMM dd, yyyy h:mm a') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Estimated Delivery</strong> : <?= \Yii::$app->formatter->asDatetime($order->estimated_time_of_arrival, 'MMM dd, yyyy h:mm a') ?></td>
                                </tr>
                                <tr>
                                    <td><strong>When</strong> : <?= $order->is_order_scheduled ? 'Scheduled' : 'As soon as possible' ?></td>
                                </tr>
                            </table>
                    </td>
                </tr>
            </table>

            <!-- END recipient detail -->

            <!-- Invoice Items Details -->

            <div id="invoice-items-details" class="pt-1 invoice-items-table">
                <div class="ion-row row">
                    <div class="ion-col col-12">
                        <div class="table-responsive">
                            <table class="table-hover">
                                <thead>
                                <tr>
                                    <th align="start" style="background:#000;color: #fff;">Item name</th>
                                    <th align="center" style="background:#000;color: #fff;">SKU</th>
                                    <th align="center" style="background:#000;color: #fff;">Barcode</th>
                                    <th align="center" style="background:#000;color: #fff;">Instruction</th>
                                    <th align="center" style="background:#000;color: #fff;">QTY</th>
                                    <th align="end" style="background:#000;color: #fff;">Extra Options</th>
                                    <th align="end" style="background:#000;color: #fff;">Total</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach($order->orderItems as $orderItem) { ?>
                                <tr>
                                    <td align="start">
                                        <b>
                                            <?= $orderItem->item_name ?>
                                            <!-- todo: extraOptionsToText(orderItem.orderItemExtraOptions)  -->
                                        </b>
                                    </td>
                                    <td align="center"><?= $orderItem->item? $orderItem->item->sku: '' ?></td>
                                    <td align="center"><?= $orderItem->item? $orderItem->item->barcode: '' ?></td>
                                    <td align="center"><?= $orderItem->customer_instruction ?></td>
                                    <td align="center"><?= $orderItem->qty ?></td>
                                    <td align="start"><?= $orderItem->getOrderExtraOptionsText() ?></td>
                                    <td align="end">
                                        <!-- currency rate calculation? -->
                                        <?= $orderItem->currency? $orderItem->currency->code: '' ?> <?= $orderItem->item_price ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                </tbody>
                            </table>

                            <hr/>
                            <table style="width: 100%">
                                <tr>
                                    <td style="width:50%"></td>
                                    <td style="width:50%">

                                        <table style="position: relative; right: 0" class="invoice-total-table">
                                            <tbody>
                                            <tr>
                                                <td align="start" colspan="3">Subtotal</td>
                                                <TD align="end">

                                                    <?= Yii::$app->formatter->asCurrency(
                                                        $order->subtotal,
                                                        $order->currency->code,
                                                        [
                                                            \NumberFormatter::MIN_FRACTION_DIGITS => $order->currency->decimal_place,
                                                            \NumberFormatter::MAX_FRACTION_DIGITS => $order->currency->decimal_place
                                                        ]
                                                    )
                                                    ?>

                                                </TD>
                                            </tr>

                                            <?php if($voucherDiscount) { ?>
                                                <tr>
                                                    <td align="start" colspan="3">Voucher Discount (<?= $order->voucher->code ?>)</td>
                                                    <td align="end">

                                                        <?= Yii::$app->formatter->asCurrency(
                                                            $voucherDiscount,
                                                            $order->currency->code,
                                                            [
                                                                \NumberFormatter::MIN_FRACTION_DIGITS => $order->currency->decimal_place,
                                                                \NumberFormatter::MAX_FRACTION_DIGITS => $order->currency->decimal_place
                                                            ]
                                                        )
                                                        ?>

                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td align="start" colspan="3">Subtotal After Voucher</td>
                                                    <td align="end">

                                                        <?= Yii::$app->formatter->asCurrency(
                                                            $order->subtotal - $voucherDiscount,
                                                            $order->currency->code,
                                                            [
                                                                \NumberFormatter::MIN_FRACTION_DIGITS => $order->currency->decimal_place,
                                                                \NumberFormatter::MAX_FRACTION_DIGITS => $order->currency->decimal_place
                                                            ]
                                                        )
                                                        ?>

                                                    </td>
                                                </tr>

                                            <?php } ?>

                                            <?php if($bankDiscount > 0) { ?>

                                                <tr>
                                                    <td align="start" colspan="3">Bank Discount</td>
                                                    <td align="end">

                                                        <?= Yii::$app->formatter->asCurrency(
                                                            $bankDiscount,
                                                            $order->currency->code,
                                                            [
                                                                \NumberFormatter::MIN_FRACTION_DIGITS => $order->currency->decimal_place,
                                                                \NumberFormatter::MAX_FRACTION_DIGITS => $order->currency->decimal_place
                                                            ]
                                                        )
                                                        ?>

                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td align="start" colspan="3">Subtotal After Bank Discount</td>
                                                    <td align="end">

                                                        <?= Yii::$app->formatter->asCurrency(
                                                            $order->subtotal - $bankDiscount,
                                                            $order->currency->code,
                                                            [
                                                                \NumberFormatter::MIN_FRACTION_DIGITS => $order->currency->decimal_place,
                                                                \NumberFormatter::MAX_FRACTION_DIGITS => $order->currency->decimal_place
                                                            ]
                                                        )
                                                        ?>

                                                    </td>
                                                </tr>

                                            <?php } ?>

                                            <tr>
                                                <td align="start" colspan="3">Delivery fee</td>
                                                <td align="end">

                                                    <?= Yii::$app->formatter->asCurrency(
                                                        $order->delivery_fee,
                                                        $order->currency->code,
                                                        [
                                                            \NumberFormatter::MIN_FRACTION_DIGITS => $order->currency->decimal_place,
                                                            \NumberFormatter::MAX_FRACTION_DIGITS => $order->currency->decimal_place
                                                        ]
                                                    )
                                                    ?>

                                                </td>
                                            </tr>
                                            <?php if($order->discount_type == 3) { ?>

                                                <tr>
                                                    <td align="start" colspan="3">Voucher Discount (<?= $order->voucher->code ?>)</td>
                                                    <td align="end">

                                                        <?= Yii::$app->formatter->asCurrency(
                                                            $order->delivery_fee,
                                                            $order->currency->code,
                                                            [
                                                                \NumberFormatter::MIN_FRACTION_DIGITS => $order->currency->decimal_place,
                                                                \NumberFormatter::MAX_FRACTION_DIGITS => $order->currency->decimal_place
                                                            ]
                                                        )
                                                        ?>

                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td align="start" colspan="3">Delivery fee After Voucher</td>
                                                    <td align="end"><?= $order->currency->code ?> 0.000</td>
                                                </tr>

                                            <?php }
                                            if($order->tax > 0) { ?>

                                            <tr>
                                                <td align="start" colspan="3">Tax</td>
                                                <td align="end">

                                                    <?= Yii::$app->formatter->asCurrency(
                                                        $order->tax,
                                                        $order->currency->code,
                                                        [
                                                            \NumberFormatter::MIN_FRACTION_DIGITS => $order->currency->decimal_place,
                                                            \NumberFormatter::MAX_FRACTION_DIGITS => $order->currency->decimal_place
                                                        ]
                                                    )
                                                    ?>

                                                </td>
                                            </tr>
                                            <?php } ?>

                                            <tr style="background: #f4f4f4;">
                                                <td align="start" colspan="3">Amount</td>
                                                <td style="text-align: right;align-content: end;" align="end">

                                                    <?= Yii::$app->formatter->asCurrency(
                                                        $order->total_price,
                                                        $order->currency->code,
                                                        [
                                                            \NumberFormatter::MIN_FRACTION_DIGITS => $order->currency->decimal_place,
                                                            \NumberFormatter::MAX_FRACTION_DIGITS => $order->currency->decimal_place
                                                        ]
                                                    )
                                                    ?>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>

                                    </td>
                                </tr>
                            </table>

                        </div>

                    </div>
                </div>
            </div>

            <!-- END -->

        </div>

    </div>
</div>
