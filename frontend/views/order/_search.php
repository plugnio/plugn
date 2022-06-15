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


<!-- users filter start -->
<div class="card">



    <div class="card-header" style="padding-bottom:21px !important">
        <h4 class="card-title">Filters</h4>
        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
        <div class="heading-elements">
            <ul class="list-inline mb-0">
                <li><a data-action="collapse"><i class="feather icon-chevron-down"></i></a></li>
                <li><a data-action="close"><i class="feather icon-x"></i></a></li>
            </ul>
        </div>
    </div>
    <div class="card-content collapse ">


        <div class="card-body" style="padding-top:0px !important">
            <div class="users-list-filter">
                <form>
                    <div class="row">

                        <?php
                        $form = ActiveForm::begin([
                                    'action' => ['order/index', 'storeUuid' => $restaurant_uuid],
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
                          <?=
                             $form->field($model, 'date_range', [
                             ])->widget(DateRangePicker::classname(), [
                                 'presetDropdown' => false,
                                 'convertFormat' => true,
                                 'pluginOptions' => ['locale' => ['format' => 'Y-m-d H:m:s']],
                             ]);
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
                      </div>


                        <div class="form-group">
                            <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('Reset', ['order/index', 'storeUuid' => $restaurant_uuid], ['class' => 'btn btn-outline-secondary', 'style' => 'margin-left: 10px;']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>

                </form>
            </div>
        </div>

    </div>
</div>
