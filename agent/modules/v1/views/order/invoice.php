<!-- todo: update language support -->

<ion-content>

    <ion-card class="ion-no-padding card-invoice" id="invoice">

        <ion-card-header>
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
        </ion-card-header>


        <ion-card-content class="ion-padding ion-margin">

            <h2 class="txt-invoice-heading">
                Invoice #<?= $order->order_uuid ?>

                <?php if($order->payment && $order->payment->received_callback && $order->payment->payment_current_status == 'CAPTURED') { ?>
                    <ion-badge class="status-paid">
                        Paid
                    </ion-badge>
                <?php } else { ?>
                    <ion-badge class="status-unpaid">
                        Unpaid
                    </ion-badge>
                <?php } ?>
            </h2>

            <div class="invoice-details">
                <p>
                    <b>Invoice Date</b>
                    <?= \Yii::$app->formatter->asDatetime($order->order_created_at, 'MMM dd, yyyy h:mm a') ?>
                </p>

                <p>
                    <b>Estimated Delivery</b>
                    <?= \Yii::$app->formatter->asDatetime($order->estimated_time_of_arrival, 'MMM dd, yyyy h:mm a') ?>
                </p>

                <p>
                    <b>When </b> <?= $order->is_order_scheduled ? 'Scheduled' : 'As soon as possible' ?>
                </p>

                <?php if($order->special_directions) { ?>
                <p style="margin-top: 12px;">
                    <b>Special Direction</b>
                    <br/>
                    <?= $order->special_directions ?>
                </p>
                <?php } ?>
            </div>

            <!-- Invoice Recipient Details -->

            <ion-row style="font-family: Nunito" id="invoice-company-details" class="row">

                <ion-col size-xs="12" size-sm="12" size-md="6" size="6" class="text-left" >

                    <h3 class="invoice-logo"><?= $order->restaurant->name ?></h3>
                    <!-- <p class="card-text mb-25">Office 149, 450 South Brand Brooklyn</p>
                    <p class="card-text mb-25">San Diego County, CA 91905, USA</p>
                    <p class="card-text mb-0">+1 (123) 456 7891, +44 (876) 543 2198</p> -->

                    <?php if($order->order_mode == 1) { ?>

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

                        <?php if(($order->unit_type == 'Apartment' || $order->unit_type == 'Office') || $order->avenue) { ?>

                            <?php if($order->avenue) { ?>
                                <p style="font-family: Nunito"  class="txt-avenue">
                                    Avenue <?= $order->avenue ?>
                                </p>
                            <?php } ?>

                            <?php if($order->floor) { ?>
                                <p style="font-family: Nunito"  class="txt-building">
                                    Floor <?= $order->apartment ?>
                                </p>
                            <?php } ?>

                            <?php if($order->unit_type == 'Apartment' && $order->apartment) { ?>
                                <p style="font-family: Nunito"  class="txt-building">
                                    Apartment No. <?= $order->apartment ?>
                                </p>
                            <?php } ?>

                            <?php if($order->unit_type == 'Office' && $order->office) { ?>
                                <p style="font-family: Nunito"  class="txt-building">
                                    Office No. <?= $order->office ?>
                                </p>
                            <?php } ?>

                            <?php if($order->unit_type != 'House' && $order->house_number) { ?>
                                <p style="font-family: Nunito"  class="txt-building">
                                    Building <?= $order->house_number ?>
                                </p>
                            <?php } ?>

                        <?php } ?>

                        <?php if($order->unit_type != 'Apartment' && $order->unit_type != 'Office') { ?>

                            <?php if($order->avenue) { ?>
                            <p class="txt-avenue">
                                Avenue <?= $order->avenue ?>
                            </p>
                            <?php } ?>

                            <?php if($order->unit_type == 'House') { ?>
                            <p class="txt-house-number">
                                House No. <?= $order->house_number ?>
                            </p>
                            <?php } ?>

                            <?php if($order->unit_type != 'House') { ?>
                            <p class="txt-building">
                                Building <?= $order->house_number ?>
                            </p>
                            <?php } ?>

                            <?php if($order->address_1) { ?>
                                <p class="txt-address-1">
                                    <?= $order->address_1 ?>
                                </p>
                            <?php } ?>

                            <?php if($order->address_2) { ?>
                            <p class="txt-address-2">
                                <?= $order->address_2 ?>
                            </p>
                            <?php } ?>
                        <?php } ?>

                        <?php if($order->area_id) { ?>
                            <p style="font-family: Nunito" >
                                <?= $order->area_name ?>
                            </p>
                        <?php } ?>

                        <?php if(($order->area && $order->area->city) || $order->city) { ?>
                        <p style="font-family: Nunito" >
                            <?= $order->area->city ? $order->area->city->city_name : $order->city ?> <?= $order->postalcode ?>
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

                </ion-col>

                <ion-col size-xs="12" size-sm="12" size-md="6" size="6" class="text-left">

                    <h5 class="txt-customer">Customer</h5>

                    <h4 class="txt-customer-name"><?= $order->customer_name ?></h4>

                    <p style="font-family: Nunito" class="txt-payment-method">
                        <?= $order->payment_method_name ?>
                    </p>

                    <!--
                    <div class="invoice-details my-2" *ngIf="order.special_directions">
                      <ion-row class="row">
                        <ion-col class="col-12 text-left">
                          <span>
                            <b>Special Directions</b>
                            <span class="pl-10">
                              <?= $order->special_directions ?>
                            </span>
                          </span>
                        </ion-col>
                      </ion-row>
                    </div>-->

                </ion-col>
            </ion-row>

            <!-- END recipient detail -->

            <!-- Invoice Items Details -->

            <div id="invoice-items-details" class="pt-1 invoice-items-table">
                <ion-row class="row">
                    <ion-col class="col-12">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th align="start">Items</th>
                                    <th align="center">SKU</th>
                                    <th align="center">QTY</th>
                                    <th align="end">Total</th>
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

                                        <?= $orderItem->customer_instruction ?>
                                    </td>
                                    <td align="center"><?= $orderItem->item? $orderItem->item->sku: '' ?></td>
                                    <td align="center"><?= $orderItem->qty ?></td>
                                    <td align="end">
                                        <!-- currency rate calculation? -->
                                        <?= $orderItem->currency? $orderItem->currency->code: '' ?> <?= $orderItem->item_price ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                            <table style="position: relative; right: 0" class="table table-hover invoice-total-table">
                                <tr>
                                    <td align="start" colspan="3">Subtotal</td>
                                    <TD align="end">
                                        <?= Yii::$app->formatter->asCurrency(
                                                $order->subtotal,
                                                $order->currency->code,
                                                [
                                                    NumberFormatter::MIN_FRACTION_DIGITS => 3,
                                                    NumberFormatter::MAX_FRACTION_DIGITS => 3
                                                ]
                                        )
                                        ?>
                                    </TD>
                                </tr>

                                <?php if($order->voucher && $order->voucher->discount_type !== 3) { ?>
                                <tr>
                                    <td align="start" colspan="3">Voucher Discount (<?= $order->voucher->code ?>)</td>
                                    <td align="end">
                                         <?= Yii::$app->formatter->asCurrency(
                                                $voucherDiscount,
                                                $order->currency->code,
                                                [
                                                    NumberFormatter::MIN_FRACTION_DIGITS => 3,
                                                    NumberFormatter::MAX_FRACTION_DIGITS => 3
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
                                                NumberFormatter::MIN_FRACTION_DIGITS => 3,
                                                NumberFormatter::MAX_FRACTION_DIGITS => 3
                                            ]
                                        )
                                        ?>
                                    </td>
                                </tr>

                                <?php } ?>

                                <?php if($order->bankDiscount) { ?>

                                    <tr>
                                        <td align="start" colspan="3">Bank Discount</td>
                                        <td align="end">
                                            <?= Yii::$app->formatter->asCurrency(
                                                $bankDiscount,
                                                $order->currency->code,
                                                [
                                                    NumberFormatter::MIN_FRACTION_DIGITS => 3,
                                                    NumberFormatter::MAX_FRACTION_DIGITS => 3
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
                                                    NumberFormatter::MIN_FRACTION_DIGITS => 3,
                                                    NumberFormatter::MAX_FRACTION_DIGITS => 3
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
                                                    NumberFormatter::MIN_FRACTION_DIGITS => 3,
                                                    NumberFormatter::MAX_FRACTION_DIGITS => 3
                                                ]
                                            )
                                            ?>
                                    </td>
                                </tr>
                                <?php if($order->voucher && $order->voucher->discount_type == 3) { ?>

                                    <tr>
                                        <td align="start" colspan="3">Voucher Discount (<?= $order->voucher->code ?>)</td>
                                        <td align="end">
                                            <?= Yii::$app->formatter->asCurrency(
                                                $order->delivery_fee,
                                                $order->currency->code,
                                                [
                                                    NumberFormatter::MIN_FRACTION_DIGITS => 3,
                                                    NumberFormatter::MAX_FRACTION_DIGITS => 3
                                                ]
                                            )
                                            ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td align="start" colspan="3">Delivery fee After Voucher</td>
                                        <td align="end"><?= $order->currency->code ?> 0.000</td>
                                    </tr>

                                <?php } ?>

                                <tr *ngIf="order.tax > 0">
                                    <td align="start" colspan="3">Tax</td>
                                    <td align="end">
                                        <?= Yii::$app->formatter->asCurrency(
                                                $order->tax,
                                                $order->currency->code,
                                                [
                                                    NumberFormatter::MIN_FRACTION_DIGITS => 3,
                                                    NumberFormatter::MAX_FRACTION_DIGITS => 3
                                                ]
                                        )
                                        ?>
                                    </td>
                                </tr>

                                <tr style="background: #f4f4f4;">
                                    <td align="start" colspan="3">Amount</td>
                                    <td style="text-align: right;align-content: end;" align="end">
                                        <?= Yii::$app->formatter->asCurrency(
                                                $order->total_price,
                                                $order->currency->code,
                                                [
                                                        NumberFormatter::MIN_FRACTION_DIGITS => 3,
                                                        NumberFormatter::MAX_FRACTION_DIGITS => 3
                                                ]
                                            )
                                        ?>
                                    </td>
                                </tr>

                            </table>

                        </div>

                    </ion-col>
                </ion-row>
            </div>

            <!-- END -->

        </ion-card-content>

    </ion-card>
</ion-content>

