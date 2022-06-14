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

                    <div class="order-search">

                        <?php
                        $form = ActiveForm::begin([
                                    'action' => ['order/index'],
                                    'method' => 'get',
                        ]);
                        ?>

                        <div class="col-6">
                            <?= $form->field($model, 'order_uuid') ?>
                        </div>


                        <div class="col-6">
                            <?= $form->field($model, 'customer_phone_number') ?>
                        </div>

                        <div class="col-6">
                          <?php
                          /*
                             $form->field($model, 'date_range', [
                             ])->widget(DateRangePicker::classname(), [
                                 'presetDropdown' => false,
                                 'convertFormat' => true,
                                 'pluginOptions' => ['locale' => ['format' => 'Y-m-d H:m:s']],
                             ]);*/
                         ?>
                        </div>

                        <div class="col-6">
                            <?=
                            $form->field($model, 'order_status')->dropDownList([
                                Order::STATUS_PENDING => 'Pending',
                                Order::STATUS_BEING_PREPARED => 'Being prepared',
                                Order::STATUS_OUT_FOR_DELIVERY => 'Out for delivery',
                                Order::STATUS_COMPLETE => 'Complete',
                                Order::STATUS_ACCEPTED => 'Accepted',
                                Order::STATUS_CANCELED => 'Canceled',
                                    ], ['class' => 'form-control', 'prompt' => 'Select order status']);
                            ?>
                        </div>


                        <div class="form-group">
                            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('Reset', ['order/index'], ['class' => 'btn btn-outline-secondary', 'style' => 'margin-left: 10px;']) ?>
                        </div>

                        <?php ActiveForm::end(); ?>

            </div>