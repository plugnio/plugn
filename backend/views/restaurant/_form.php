<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\time\TimePicker;
use backend\models\Agent;
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

    <?php
    $paymentMethodQuery = PaymentMethod::find()->asArray()->all();
    $paymentMethodArray = ArrayHelper::map($paymentMethodQuery, 'payment_method_id', 'payment_method_name');

    $sotredRestaurantPaymentMethod = [];

    if ($model->restaurant_uuid != null) {


        $sotredRestaurantPaymentMethod = RestaurantPaymentMethod::find()
                ->select('payment_method_id')
                ->asArray()
                ->where(['restaurant_uuid' => $model->restaurant_uuid])
                ->all();

        $sotredRestaurantPaymentMethod = ArrayHelper::getColumn($sotredRestaurantPaymentMethod, 'payment_method_id');
    }


    $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
    ?>

    <?=
    $form->field($model, 'restaurant_payments_method')->widget(Select2::classname(), [
        'data' => $paymentMethodArray,
        'options' => [
            'placeholder' => 'Select payment method ...',
            'multiple' => true,
            'value' => $sotredRestaurantPaymentMethod
        ],
        'pluginOptions' => [
            'tags' => true,
            'tokenSeparators' => [',', ' '],
        ],
    ]);
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tagline')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tagline_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'restaurant_domain')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'app_id')->textInput(['maxlength' => true]) ?>

    <?=
    $form->field($model, 'restaurant_thumbnail_image')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*', 'multiple' => false
        ],
        'pluginOptions' => [
            'showUpload' => false,
            'initialPreview' => $model->getThumbnailImage(),
            'initialPreviewAsData' => true,
            'overwriteInitial' => true,
            'maxFileSize' => 2800
        ]
    ]);
    ?>
    <?=
    $form->field($model, 'restaurant_logo')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*', 'multiple' => false
        ],
        'pluginOptions' => [
            'showUpload' => false,
            'initialPreview' => $model->getLogo(),
            'initialPreviewAsData' => true,
            'overwriteInitial' => true,
            'maxFileSize' => 2800
        ]
    ]);
    ?>


    <?=
    $form->field($model, 'support_delivery')->dropDownList(
            [
        1 => 'Yes',
        0 => 'No',
            ]
            , ['id' => 'supportDeliveryInput']
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

    <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'restaurant_email')->input('email') ?>

    <?= $form->field($model, 'owner_first_name')->textInput() ?>

    <?= $form->field($model, 'owner_last_name')->textInput() ?>

    <?= $form->field($model, 'owner_email')->input('email') ?>

    <?= $form->field($model, 'owner_number')->textInput() ?>

    <?= $form->field($model, 'store_branch_name')->textInput() ?>

    <?= $form->field($model, 'platform_fee')->textInput([ 'maxlength' => true, 'placeholder' => 'Platform fee']) ?>

    <?= $form->field($model, 'custom_css')->textarea(['rows' => '12']) ?>

    <?= $form->field($model, 'developer_id')->textInput() ?>

    <?= $form->field($model, 'merchant_id')->textInput() ?>

    <?= $form->field($model, 'operator_id')->textInput() ?>

    <?= $form->field($model, 'wallet_id')->textInput() ?>

    <?= $form->field($model, 'live_api_key')->textInput() ?>

    <?= $form->field($model, 'test_api_key')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
