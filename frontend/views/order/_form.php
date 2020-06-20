<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Area;
use yii\helpers\ArrayHelper;
use common\models\Order;
use common\models\City;
use common\models\RestaurantDelivery;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */


$js = "

    let orderModeInput = $('#orderModeInput');
    // On Change of project type input
    orderModeInput.change(function(){
        let selection = $(this).val();
        if(selection == 2){ // Pickup mode
            $('#customer-address').hide();
            $('#pickup-branch').show();
        }else{ //  delivery mode
            $('#customer-address').show();
            $('#pickup-branch').hide();
        }
    });

";

?>

<div class="order-form">

    <?php
    $areaQuery = $restaurant_model->getAreas()->all();
    $areaList = ArrayHelper::map($areaQuery, 'area_id', 'area_name');


    $form = ActiveForm::begin();
    ?>


    <?= $form->errorSummary($model); ?>

    <?php
    $orderModeOptions = [];
    $model->restaurant->support_delivery ? $orderModeOptions[Order::ORDER_MODE_DELIVERY] = 'Delivery' : null;
    $model->restaurant->support_pick_up ? $orderModeOptions[Order::ORDER_MODE_PICK_UP] = 'Pick up' : null;


    if (is_array($orderModeOptions) && sizeof($orderModeOptions) > 0)
        echo $form->field($model, 'order_mode')->dropDownList($orderModeOptions, ['prompt' => 'Choose...', 'class' => 'form-control select2', 'id' => 'orderModeInput']);



    $restaurantBrnachesQuery = common\models\RestaurantBranch::find()->where(['restaurant_uuid' => $model->restaurant_uuid])->asArray()->all();
    $restaurantBrnachesArray = ArrayHelper::map($restaurantBrnachesQuery, 'restaurant_branch_id', 'branch_name_en');
    ?>


    <div id='customer-address' style='display:none; <?= $model->order_mode  != null && $model->order_mode == Order::ORDER_MODE_PICK_UP ? "display:none" : "display:block" ?>'>
        <?= $form->field($model, 'area_id')->dropDownList($areaList, ['prompt' => 'Choose area name...', 'class' => 'form-control select2'])->label('Area'); ?>

        <?= $form->field($model, 'unit_type')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'block')->input('number') ?>

        <?= $form->field($model, 'street')->input('number') ?>

        <?= $form->field($model, 'avenue')->input('number') ?>

        <?= $form->field($model, 'house_number')->input('number') ?>
    </div>


    <div id='pickup-branch' style='<?= $model->order_mode != null && $model->order_mode == Order::ORDER_MODE_DELIVERY ? "display:none" : "" ?>'>
        <?= $form->field($model, 'restaurant_branch_id')->dropDownList($restaurantBrnachesArray, ['prompt' => 'Choose branch...', 'class' => 'select2'])->label('Pickup from'); ?>
    </div>

    <?= $form->field($model, 'customer_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'customer_phone_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'special_directions')->textInput(['maxlength' => true]) ?>


    <div class="form-group" style="background: #f4f6f9; padding-bottom: 10px; margin-bottom: 0px; padding-bottom: 15px; background:#f4f6f9 ">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
