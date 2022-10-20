<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use kartik\daterange\DateRangePicker;
use common\models\Order;
use common\models\Restaurant;

/* @var $this yii\web\View */
/* @var $model frontend\models\OrderSearch */
/* @var $form yii\widgets\ActiveForm */

$js = "
$(function () {

  $('#ordersearch-date_range').attr('autocomplete','off');

  });
";
$this->registerJs($js);
?>

                    <div class="order-search row">

                        <?php
                        $form = ActiveForm::begin([
                                    'action' => ['order/index'],
                                    'method' => 'get',
                        ]);
                        ?>

                        <div class="col-md-2">
                            <?= $form->field($model, 'order_uuid') ?>
                        </div>

                        <div class="col-md-2">
                            <?= $form->field($model, 'restaurant_uuid') ?>
                        </div>

                        <div class="col-md-2">
                            <?= $form->field($model, 'customer_id') ?>
                        </div>

                        <div class="col-md-2">
                            <?= $form->field($model, 'customer_phone_number') ?>
                        </div>

                        <div class="col-md-2">
                            <?=
                            $form->field($model, 'order_status')->dropDownList([

                                Order::STATUS_DRAFT => 'Draft',
                                Order::STATUS_PARTIALLY_REFUNDED => 'Partially Refunded',
                                Order::STATUS_REFUNDED => 'Refunded',
                                Order::STATUS_ABANDONED_CHECKOUT => 'Abandoned',
                                Order::STATUS_PENDING => 'Pending',
                                Order::STATUS_BEING_PREPARED => 'Being prepared',
                                Order::STATUS_OUT_FOR_DELIVERY => 'Out for delivery',
                                Order::STATUS_COMPLETE => 'Complete',
                                Order::STATUS_ACCEPTED => 'Accepted',
                                Order::STATUS_CANCELED => 'Canceled',
                                    ], ['class' => 'form-control', 'prompt' => 'Select order status']);
                            ?>
                        </div>


                        <div class="col-md-2">
                            <div class="form-group" style="margin-top: 22px;">
                                <label class="control-label" for="ordersearch-order_status">&nbsp;</label>
                                <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                                <?= Html::a('Reset', ['order/index'], ['class' => 'btn btn-outline-secondary', 'style' => 'margin-left: 10px;']) ?>
                            </div>
                        </div>

                        <?php ActiveForm::end(); ?>

            </div>
<hr/>
