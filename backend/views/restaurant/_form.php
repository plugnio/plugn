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

    <?php
    $agentQuery = Agent::find()->asArray()->all();
    $agentArray = ArrayHelper::map($agentQuery, 'agent_id', 'agent_name');


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
    $form->field($model, 'agent_id')->widget(Select2::classname(), [
        'data' => $agentArray,
        'options' => ['placeholder' => 'Select a agent ...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ])->label('Agent');
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

    <?=
    $form->field($model, 'thumbnail_image')->widget(FileInput::classname(), [
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
    $form->field($model, 'logo')->widget(FileInput::classname(), [
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

    <?= $form->field($model, 'phone_number')->textInput(['maxlength' => true]) ?>



    <?= $form->field($model, 'business_type')->textInput(['value' => 'corp']) ?>
    <?= $form->field($model, 'vendor_sector')->textInput() ?>
    <?= $form->field($model, 'license_number')->textInput() ?>
    <?=
    $form->field($model, 'not_for_profit', [
        'template' => "<label style='display:block;' class='control-label' for='agent-agent_email'>Not for profit</label>\n{input}\n{hint}\n{error}"
    ])->checkbox([
        'label' => '',
        'checked' => $model->not_for_profit == 0 ? false : true,
        'data-bootstrap-switch' => '',
        'data-off-color' => 'danger',
        'data-on-color' => 'success',
    ])
    ?>            

    
    <?= $form->field($model, 'document_issuing_country')->textInput(['value' => "KW"]) ?>

    <?=
    $form->field($model, 'document_issuing_date')->widget(DatePicker::classname(), [
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ])
    ?>
    <?=
    $form->field($model, 'document_expiry_date')->widget(DatePicker::classname(), [
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ])
    ?>

    <?=
    $form->field($model, 'restaurant_document_file')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
            'multiple' => false
        ]
    ]);
    ?>

    <?= $form->field($model, 'document_title')->textInput(['value' => 'Authorized Signature']) ?>

    <?= $form->field($model, 'document_file_purpose')->textInput(['value' => 'customer_signature']) ?>

    <?= $form->field($model, 'iban')->textInput() ?>
    <?= $form->field($model, 'owner_first_name')->textInput() ?>
    <?= $form->field($model, 'owner_last_name')->textInput() ?>
    <?= $form->field($model, 'owner_email')->textInput() ?>
    <?= $form->field($model, 'owner_customer_number')->textInput() ?>

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
