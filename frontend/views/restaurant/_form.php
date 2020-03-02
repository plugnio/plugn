<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\time\TimePicker;
use common\models\Vendor;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\Area;
use common\models\RestaurantDelivery;
use common\models\RestaurantPaymentMethod;
use common\models\PaymentMethod;
use kartik\file\FileInput;


$js = "
let supportDeliveryInput = $('#supportDeliveryInput');
let supportPickupInput = $('#supportPickupInput');

// On Change of project type input
supportDeliveryInput.change(function(){
    let selection = $(this).val();
    if(selection == 0){ // Dont support delivery
        $('#minDeliveryTime').hide();
        $('#deliveryFeeInput').hide();
    }else{ // Support delivery
        $('#minDeliveryTime').show();
        $('#deliveryFeeInput').show();
    }
});

supportPickupInput.change(function(){
    let selection = $(this).val();
    if(selection == 0){ // Dont support pickup
        $('#minPickupTime').hide();
    }else{ // Reward based
        $('#minPickupTime').show();
    }
});


";


$this->registerJs($js);


/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="restaurant-form">

    <?php

    $areaQuery = Area::find()->asArray()->all();
    $restaurantDeliveryArray = ArrayHelper::map($areaQuery, 'area_id', 'area_name');

    $paymentMethodQuery = PaymentMethod::find()->asArray()->all();
    $paymentMethodArray = ArrayHelper::map($paymentMethodQuery, 'payment_method_id', 'payment_method_name');

    $sotredRestaurantDeliveryAreas = [];
    $sotredRestaurantPaymentMethod = [];

    if ($model->restaurant_uuid != null) {

        $sotredRestaurantDeliveryAreas = RestaurantDelivery::find()
                ->select('area_id')
                ->asArray()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->all();

        $sotredRestaurantDeliveryAreas = ArrayHelper::getColumn($sotredRestaurantDeliveryAreas, 'area_id');


        $sotredRestaurantPaymentMethod = RestaurantPaymentMethod::find()
                ->select('payment_method_id')
                ->asArray()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->all();

        $sotredRestaurantPaymentMethod = ArrayHelper::getColumn($sotredRestaurantPaymentMethod, 'payment_method_id');

    }


    $form = ActiveForm::begin();
    ?>


    <?php
//    $form->field($model, 'restaurant_delivery_area')->widget(Select2::classname(), [
//        'data' => $restaurantDeliveryArray,
//        'options' => [
//            'placeholder' => 'Select delivery area ...',
//            'multiple' => true,
//            'value' => $sotredRestaurantDeliveryAreas
//        ],
//        'pluginOptions' => [
//            'tags' => true,
//            'tokenSeparators' => [',', ' '],
//        ],
//    ]);
    ?>

    <?php
//    $form->field($model, 'restaurant_payments_method')->widget(Select2::classname(), [
//        'data' => $paymentMethodArray,
//        'options' => [
//            'placeholder' => 'Select payment method ...',
//            'multiple' => true,
//            'value' => $sotredRestaurantPaymentMethod
//        ],
//        'pluginOptions' => [
//            'tags' => true,
//            'tokenSeparators' => [',', ' '],
//        ],
//    ]);
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tagline')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tagline_ar')->textInput(['maxlength' => true]) ?>

    <?php
//    $form->field($model, 'thumbnail_image')->widget(FileInput::classname(), [
//        'options' => ['accept' => 'image/*', 'multiple' => false
//        ],
//        'pluginOptions' => [
//            'showUpload' => false,
//             'initialPreview' => $model->getThumbnailImage(),
//             'initialPreviewAsData' => true,
//             'overwriteInitial' => true,
//            'maxFileSize' => 2800
//        ]
//    ]);
    ?>
    <?php
//    $form->field($model, 'logo')->widget(FileInput::classname(), [
//        'options' => ['accept' => 'image/*', 'multiple' => false
//        ],
//        'pluginOptions' => [
//            'showUpload' => false,
//             'initialPreview' => $model->getLogo(),
//             'initialPreviewAsData' => true,
//             'overwriteInitial' => true,
//            'maxFileSize' => 2800
//        ]
//    ]);
    ?>


    <?=
    $form->field($model, 'support_delivery')->dropDownList(
            [
        1 => 'Yes',
        0 => 'No',
            ]
            , ['prompt' => 'Choose...', 'id' => 'supportDeliveryInput']
    );
    ?>

    <?=
    $form->field($model, 'support_pick_up')->dropDownList(
            [
        1 => 'Yes',
        0 => 'No',
            ]
            , ['prompt' => 'Choose...', 'id' => 'supportPickupInput']
    );
    ?>

    <div id='minDeliveryTime' style='<?= $model->isNewRecord || ($model->support_delivery == 0) ? "display:none" : "" ?>'>
        <?php
//        $form->field($model, 'min_delivery_time')->widget(TimePicker::classname(), [
//            'options' => ['placeholder' => 'Enter event time ...'],
//            'pluginOptions' => [
//                'autoclose' => true,
//                'defaultTime' => false,
//                'showSeconds' => true,
//                'showMeridian' => false,
//            ]
//        ]);
        ?>
    </div>

    <div id='minPickupTime' style='<?= $model->isNewRecord || ($model->support_pick_up == 0) ? "display:none" : "" ?>'>
        <?php
//        $form->field($model, 'min_pickup_time')->widget(TimePicker::classname(), [
//            'options' => ['placeholder' => 'Enter event time ...'],
//            'pluginOptions' => [
//                'autoclose' => true,
//                'defaultTime' => false,
//                'showSeconds' => true,
//                'showMeridian' => false,
//            ]
//        ]);
        ?>
    </div>


    <?php
//    $form->field($model, 'operating_from')->widget(TimePicker::classname(), [
//        'options' => ['placeholder' => 'Enter event time ...'],
//        'pluginOptions' => [
//            'autoclose' => true,
//            'defaultTime' => false,
//            'showSeconds' => true,
//            'showMeridian' => false
//        ]
//    ]);
    ?>

    <?php
//    $form->field($model, 'operating_to')->widget(TimePicker::classname(), [
//        'options' => ['placeholder' => 'Enter event time ...'],
//        'pluginOptions' => [
//            'autoclose' => true,
//            'defaultTime' => false,
//            'showSeconds' => true,
//            'showMeridian' => false
//        ]
//    ]);
    ?>
    
    <div id='deliveryFeeInput' style='<?= $model->isNewRecord || ($model->support_delivery == 0) ? "display:none" : "" ?>'>
        <?= $form->field($model, 'delivery_fee')->input('number', ['maxlength' => true, 'placeholder' => '0.500']) ?>
    </div>

    <?= $form->field($model, 'min_charge')->input('number', ['maxlength' => true, 'placeholder' => '5']) ?>

    <?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'location_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'location_latitude')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'location_longitude')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
