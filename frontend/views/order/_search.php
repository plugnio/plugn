<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\daterange\DateRangePicker;
use common\models\Order;

/* @var $this yii\web\View */
/* @var $model frontend\models\OrderSearch */
/* @var $form yii\widgets\ActiveForm */

$js = "
    $(function () {

    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    })
    $('select').select2({
    placeholder: 'Select a state',
    allowClear: true
});
    $('select').select2({
        minimumResultsForSearch: -1
    });
  })
";


$this->registerJs($js);
?>

<div class="order-search">



    <?php
        $form = ActiveForm::begin([
                    'layout' => 'horizontal',
                    'fieldConfig' => [
                        'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                        'horizontalCssClasses' => [
                            'label' => 'col-sm-4',
                            'offset' => 'col-sm-offset-4',
                            'wrapper' => 'col-sm-8',
                            'error' => '',
                            'hint' => '',
                        ],
                    ],
                    'action' => ['order/index', 'restaurantUuid' => $restaurant_uuid],
                    'method' => 'get',
                ]);
    ?>


    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'order_uuid') ?>
            <?=
                $form->field($model, 'date_range', [
                ])->widget(DateRangePicker::classname(), [
                    'presetDropdown' => false,
                    'convertFormat' => true,
                    'pluginOptions' => ['locale' => ['format' => 'Y-m-d H:m:s']],
                ]);
            ?>
            <?=
            $form->field($model, 'order_status')->dropDownList([
                Order::STATUS_PENDING => 'Pending',
                Order::STATUS_BEING_PREPARED => 'Being prepared',
                Order::STATUS_OUT_FOR_DELIVERY => 'Out for delivery',
                Order::STATUS_COMPLETE => 'Complete',
                Order::STATUS_CANCELED => 'Canceled',
                Order::STATUS_REFUNDED => 'Refunded'
                    ], ['class' => 'select2', 'prompt' => 'Select order status']);
            ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'customer_name') ?>
            <?= $form->field($model, 'customer_phone_number') ?>

        </div>
    </div>


    <div class="form-group">
    <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Reset', ['order/index', 'restaurantUuid' => $restaurant_uuid], ['class' => 'btn btn-outline-secondary']) ?>
    </div>

<?php ActiveForm::end(); ?>

</div>
