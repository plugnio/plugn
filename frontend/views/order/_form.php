<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Area;
use yii\helpers\ArrayHelper;
use common\models\Order;
use common\models\City;
use common\models\RestaurantDelivery;
use borales\extensions\phoneInput\PhoneInput;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $model common\models\Order */
/* @var $form yii\widgets\ActiveForm */

$url = yii\helpers\Url::to(['get-areas-belongs-to-this-location', 'storeUuid' => $model->restaurant_uuid, 'business_location_id' => 2]);

$js = "

    $('#order-estimated_time_of_arrival').attr('autocomplete','off');
    $('#order-estimated_time_of_arrival').attr('style', '  padding-right: 2rem !important; padding-left: 3rem !important; ');



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


    $(document).on('wheel', 'input[type=number]', function (e) {
        $(this).blur();
    });

";
$this->registerJs($js);
?>

<div class="card order-form">

    <div class="card-body">
        <?php
        $areaQuery = $restaurant->getAreas()->asArray()->all();
        $areaList = ArrayHelper::map($areaQuery, 'area_id', 'area_name');


          $form = ActiveForm::begin([
                      'errorSummaryCssClass' => 'alert alert-danger'
          ]);
        ?>

        <?= $form->errorSummary($model, ['header' => '<h4 class="alert-heading">Please fix the following errors:</h4>']); ?>

        <?php

        $paymentMethods = $restaurant->getRestaurantPaymentMethods()
          ->with('paymentMethod')
          ->asArray()
          ->all();

        $paymentMethodsArray = ArrayHelper::map($paymentMethods, 'payment_method_id', 'paymentMethod.payment_method_name');

        echo $form->field($model, 'payment_method_id')->dropDownList($paymentMethodsArray, [
          'prompt' => 'Choose...', 'class' => 'form-control select2',
        ]);

        echo $form->field($model, 'order_mode')->dropDownList([
          Order::ORDER_MODE_DELIVERY => 'Delivery',
          Order::ORDER_MODE_PICK_UP => 'Pick up'
        ], ['prompt' => 'Choose...', 'class' => 'form-control select2', 'id' => 'orderModeInput']);



        $businessLocationQuery = common\models\BusinessLocation::find()->where(['restaurant_uuid' => $model->restaurant_uuid])->asArray()->all();
        $businessLocationArray = ArrayHelper::map($businessLocationQuery, 'business_location_id', 'business_location_name');
        ?>


        <div id='customer-address' style='display:none; <?= $model->order_mode != null && $model->order_mode == Order::ORDER_MODE_DELIVERY ? "display:block" : "" ?>'>

            <div class="row">
                <div class="col-12 col-sm-4  col-md-4 col-lg-4">

                    <?= $form->field($model, 'area_id')->dropDownList($areaList, ['prompt' => 'Choose area name...', 'class' => 'form-control select2','value'=>$model->area_id])->label('Area'); ?>
                </div>
                <div class="col-12 col-sm-4  col-md-4 col-lg-4">
                    <?= $form->field($model, 'unit_type')->dropDownlist(['House' => 'House', 'Office' => 'Office', 'Apartment' => 'Apartment']) ?>
                </div>

                <div class="col-12 col-sm-4  col-md-4 col-lg-4">

                    <?= $form->field($model, 'block')->textInput(['maxlength' => true]) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-4  col-md-4 col-lg-4">
                    <?= $form->field($model, 'street')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-12 col-sm-4  col-md-4 col-lg-4">
                    <?= $form->field($model, 'avenue')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-12 col-sm-4  col-md-4 col-lg-4">

                    <?= $form->field($model, 'house_number')->textInput(['maxlength' => true])->label('Building No. ') ?>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-sm-4  col-md-4 col-lg-4">
                    <?= $form->field($model, 'floor')->textInput(['maxlength' => true]) ?>
                </div>
                <div class="col-12 col-sm-4  col-md-4 col-lg-4">
                  <?= $form->field($model, 'apartment')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-12 col-sm-4  col-md-4 col-lg-4">
                  <?= $form->field($model, 'office')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

        </div>

        <div id='pickup-branch' style='<?= $model->order_mode != null && $model->order_mode == Order::ORDER_MODE_DELIVERY ? "display:none" : "" ?>'>
            <?= $form->field($model, 'pickup_location_id')->dropDownList($businessLocationArray, ['prompt' => 'Choose pickup location...', 'class' => 'select2'])->label('Pickup from'); ?>
        </div>

        <?= $form->field($model, 'customer_name')->textInput(['maxlength' => true]) ?>

        <?=
           $form->field($model, 'customer_phone_number')->widget(PhoneInput::className(), [
              'jsOptions' => [
                  'preferredCountries' => ['kw', 'sa', 'aed','qa','bh','om'],
                  'initialCountry' => $model->restaurant->country->iso
              ]
          ]);
        ?>

        <?= $form->field($model, 'customer_email')->textInput(['maxlength' => true, 'type' => 'email']) ?>

        <?= $form->field($model, 'special_directions')->textInput(['maxlength' => true]) ?>


        <?=
          $form->field($model, 'estimated_time_of_arrival', [
              'labelOptions' => ['class' => 'control-label'],
              'template' => '
              {label}
           <div class="position-relative has-icon-left">

                {input}

             <div class="form-control-position">
              <i class="feather icon-calendar"></i>
            </div>
          </div>'
          ])->widget(DateRangePicker::classname(), [
              'presetDropdown' => false,
              'convertFormat' => true,
              'pluginOptions' => [
                'locale'=>['format' => 'Y-m-d H:i'],
                'timePicker'=>true,
                'singleDatePicker'=>true,
              ],
          ]);
        ?>



        <div class="form-group" style="background: #f4f6f9;  margin-bottom: 0px; padding-bottom: 0px; background:#f4f6f9 ">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
        </div>
        <?php ActiveForm::end(); ?>

    </div>
</div>
