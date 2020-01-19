<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\time\TimePicker;
use backend\models\Vendor;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;

$js = "
let supportDeliveryInput = $('#supportDeliveryInput');
let supportPickupInput = $('#supportPickupInput');

// On Change of project type input
supportDeliveryInput.change(function(){
    let selection = $(this).val();
    if(selection == 0){ // Dont support delivery
        $('#minDeliveryTime').hide();
    }else{ // Reward based
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
    $vendorQuery = Vendor::find()->asArray()->all();
    $vendorArray = ArrayHelper::map($vendorQuery, 'vendor_id', 'vendor_name');

    $form = ActiveForm::begin();
    ?>

    <?=
        $form->field($model, 'vendor_id')->widget(Select2::classname(), [
            'data' => $vendorArray,
            'options' => ['placeholder' => 'Select a vendor ...'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tagline')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tagline_ar')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'thumbnail_image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'logo')->textInput(['maxlength' => true]) ?>


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
        <?=
        $form->field($model, 'min_delivery_time')->widget(TimePicker::classname(), [
            'options' => ['placeholder' => 'Enter event time ...'],
            'pluginOptions' => [
                'autoclose' => true,
                'defaultTime' => false,
            ]
        ]);
        ?>
    </div>

    <div id='minPickupTime' style='<?= $model->isNewRecord || ($model->support_pick_up == 0) ? "display:none" : "" ?>'>
        <?=
        $form->field($model, 'support_pick_up')->widget(TimePicker::classname(), [
            'options' => ['placeholder' => 'Enter event time ...'],
            'pluginOptions' => [
                'autoclose' => true,
                'defaultTime' => false
            ]
        ]);
        ?>
    </div>


    <?=
    $form->field($model, 'operating_from')->widget(TimePicker::classname(), [
        'options' => ['placeholder' => 'Enter event time ...'],
        'pluginOptions' => [
            'autoclose' => true,
            'defaultTime' => false
        ]
    ]);
    ?>

    <?=
    $form->field($model, 'operating_to')->widget(TimePicker::classname(), [
        'options' => ['placeholder' => 'Enter event time ...'],
        'pluginOptions' => [
            'autoclose' => true,
            'defaultTime' => false

        ]
    ]);
    ?>

    <?= $form->field($model, 'delivery_fee')->input('number', ['maxlength' => true, 'placeholder' => '0.500']) ?>

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
