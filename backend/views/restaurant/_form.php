<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\time\TimePicker;
use common\models\Agent;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\Currency;
use common\models\Country;
use common\models\RestaurantDelivery;
use common\models\Partner;
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
use borales\extensions\phoneInput\PhoneInput;


/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="restaurant-form">

    <?php
    $paymentMethodQuery = PaymentMethod::find()->asArray()->all();
    $paymentMethodArray = ArrayHelper::map($paymentMethodQuery, 'payment_method_id', 'payment_method_name');

    $partnerMethodQuery = Partner::find()->asArray()->all();
    $partnerMethodArray = ArrayHelper::map($partnerMethodQuery, 'referral_code', 'username');

    $countryQuery = Country::find()->asArray()->all();
    $countryArray = ArrayHelper::map($countryQuery, 'country_id', 'country_name');

    $currencyQuery = Currency::find()->asArray()->all();
    $currencyArray = ArrayHelper::map($currencyQuery, 'currency_id', 'title');

    $sotredRestaurantPaymentMethod = [];

    if ($model->restaurant_uuid != null) {


        $sotredRestaurantPaymentMethod = RestaurantPaymentMethod::find()
                ->select('payment_method_id')
                ->asArray()
                ->andWhere(['restaurant_uuid' => $model->restaurant_uuid])
                ->all();

        $sotredRestaurantPaymentMethod = ArrayHelper::getColumn($sotredRestaurantPaymentMethod, 'payment_method_id');
    }


    $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]);
    ?>


    <?=
    $form->field($model, 'referral_code')->widget(Select2::classname(), [
        'data' => $partnerMethodArray,
        'options' => [
            'placeholder' => 'Select partner ...',
            'multiple' => false,
            'value' => $model->referral_code
        ],
    ]);
    ?>

    <?=
    $form->field($model, 'currency_id')->widget(Select2::classname(), [
        'data' => $currencyArray,
        'options' => [
            'placeholder' => 'Select currency ...',
            'multiple' => false,
            'value' => $model->currency_id
        ],
    ]);
    ?>


    <?=
      $form->field($model, 'is_tap_enable')->dropDownList(
              [
                  1 => 'Yes',
                  0 => 'No',
              ]
      );
    ?>

    <?=
      $form->field($model, 'is_myfatoorah_enable')->dropDownList(
              [
                  1 => 'Yes',
                  0 => 'No',
              ]
      );
    ?>


    <?=
      $form->field($model, 'country_id')->dropDownList(  $countryArray  );
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

    <?= $form->field($model, 'payment_gateway_queue_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'version')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>

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
        ]
    ]);
    ?>

    <?= $form->field($model, 'thumbnail_image')->textInput(['maxlength' => true]) ?>


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


        <?=
        $form->field($model, 'schedule_order')->dropDownList(
                [
            1 => 'Yes',
            0 => 'No',
                ]
        );
        ?>

        <?=
          $form->field($model, 'business_type')->dropDownList(
                  [
                  'ind' => 'ind',
                  'corp' => 'corp',
                  ]
          );
        ?>

    <?=
       $form->field($model, 'phone_number')->widget(PhoneInput::className(), [
          'jsOptions' => [
              'preferredCountries' => ['kw', 'sa', 'aed','qa','bh','om'],
              'initialCountry' => 'kw'
          ]
      ]);
    ?>

    <?= $form->field($model, 'restaurant_email')->input('email') ?>


    <?= $form->field($model, 'iban')->textInput() ?>
    <?= $form->field($model, 'owner_first_name')->textInput() ?>

    <?= $form->field($model, 'owner_last_name')->textInput() ?>

    <?= $form->field($model, 'owner_email')->input('email') ?>


    <?=
       $form->field($model, 'owner_number')->widget(PhoneInput::className(), [
          'jsOptions' => [
              'preferredCountries' => ['kw', 'sa', 'aed','qa','bh','om'],
          ]
      ]);
    ?>

    <?= $form->field($model, 'store_branch_name')->textInput() ?>

    <?= $form->field($model, 'platform_fee')->textInput([ 'maxlength' => true, 'placeholder' => 'Platform fee']) ?>

    <?= $form->field($model, 'warehouse_delivery_charges')->textInput([ 'maxlength' => true, 'placeholder' => 'Delivery charges']) ?>

    <?= $form->field($model, 'warehouse_fee')->textInput([ 'maxlength' => true, 'placeholder' => 'Warehouse fulfilment fee']) ?>

    <?= $form->field($model, 'armada_api_key')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mashkor_branch_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'google_analytics_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'facebook_pixil_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'snapchat_pixil_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'custom_css')->textarea(['rows' => '12']) ?>

    <?= $form->field($model, 'identification_file_id_front_side')->textInput() ?>

    <?= $form->field($model, 'identification_file_id_back_side')->textInput() ?>

    <?= $form->field($model, 'supplierCode')->textInput() ?>

    <?= $form->field($model, 'business_id')->textInput() ?>

    <?= $form->field($model, 'business_entity_id')->textInput() ?>

    <?= $form->field($model, 'developer_id')->textInput() ?>

    <?= $form->field($model, 'merchant_id')->textInput() ?>

    <?= $form->field($model, 'operator_id')->textInput() ?>

    <?= $form->field($model, 'wallet_id')->textInput() ?>

    <?= $form->field($model, 'live_api_key')->textInput() ?>

    <?= $form->field($model, 'test_api_key')->textInput() ?>

    <?= $form->field($model, 'live_public_key')->textInput() ?>

    <?= $form->field($model, 'test_public_key')->textInput() ?>

    <?= $form->field($model, 'custom_subscription_price')->textInput() ?>

    <?= $form->field($model, 'is_public')->checkbox(['checked' => $model->is_public > 0,  'value' => true]) ?>

    <?= $form->field($model, 'is_sandbox')->checkbox(['checked' => $model->is_sandbox > 0,  'value' => true]) ?>

    <?= $form->field($model, 'accept_order_247')->checkbox(['checked' => $model->accept_order_247 > 0,  'value' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
