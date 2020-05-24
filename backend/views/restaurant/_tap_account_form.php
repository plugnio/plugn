<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\time\TimePicker;
use common\models\Agent;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\Area;
use common\models\RestaurantDelivery;
use common\models\RestaurantPaymentMethod;
use common\models\PaymentMethod;
use kartik\file\FileInput;
use kartik\datetime\DateTimePicker;
use kartik\date\DatePicker;

$js = "
let supportDeliveryInput = $('#supportDeliveryInput');
let supportPickupInput = $('#supportPickupInput');

// On Change of project type input
supportDeliveryInput.change(function(){
    let selection = $(this).val();
    if(selection == 0){ // Dont support delivery
        $('#minDeliveryTime').hide();
    }else{ // Support delivery
        $('#minDeliveryTime').show();
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

    <?php  $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
    ?>


    <?= $form->field($model, 'business_type')->textInput(['value' => 'corp']) ?>

    <?= $form->field($model, 'vendor_sector')->textInput() ?>

    <?= $form->field($model, 'license_number')->textInput() ?>

    <!-- Authorized Signature  -->
    <?= $form->field($model, 'authorized_signature_issuing_country')->textInput(['value' => "KW"]) ?>

    <?=
    $form->field($model, 'authorized_signature_issuing_date')->widget(DatePicker::classname(), [
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ])
    ?>
    <?=
    $form->field($model, 'authorized_signature_expiry_date')->widget(DatePicker::classname(), [
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ])
    ?>

    <?=
    $form->field($model, 'restaurant_authorized_signature_file')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
            'multiple' => false
        ]
    ]);
    ?>

    <?= $form->field($model, 'authorized_signature_title')->textInput(['value' => 'Authorized Signature']) ?>

    <?= $form->field($model, 'authorized_signature_file_purpose')->textInput(['value' => 'customer_signature']) ?>

    <!-- Commercial License  -->
    <?= $form->field($model, 'commercial_license_issuing_country')->textInput(['value' => "KW"]) ?>

    <?=
    $form->field($model, 'commercial_license_issuing_date')->widget(DatePicker::classname(), [
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ])
    ?>
    <?=
    $form->field($model, 'commercial_license_expiry_date')->widget(DatePicker::classname(), [
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ])
    ?>

    <?=
    $form->field($model, 'restaurant_commercial_license_file')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
            'multiple' => false
        ]
    ]);
    ?>

    <?= $form->field($model, 'commercial_license_title')->textInput(['value' => 'Commercial License']) ?>

    <?= $form->field($model, 'commercial_license_file_purpose')->textInput(['value' => 'license']) ?>

    <?= $form->field($model, 'iban')->textInput() ?>

    <?= $form->field($model, 'identification_issuing_country')->textInput(['value' => "KW"]) ?>

    <?=
    $form->field($model, 'identification_issuing_date')->widget(DatePicker::classname(), [
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ])
    ?>
    <?=
    $form->field($model, 'identification_expiry_date')->widget(DatePicker::classname(), [
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ])
    ?>

    <?=
    $form->field($model, 'owner_identification_file')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
            'multiple' => false
        ]
    ]);
    ?>

    <?= $form->field($model, 'identification_file_purpose')->textInput(['value' => 'identity_document']) ?>

    <?= $form->field($model, 'identification_title')->textInput(['value' => "Owner's civil id"]) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
