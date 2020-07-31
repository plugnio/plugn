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
use common\models\Restaurant;

$js = "
$('#primaryColorInput').change(function(e){
  primaryColor = e.target.value;
  $('#primary-wrapper').css('background-color',primaryColor);
});



  let phoneNumberInput = $('#phoneNumberInput');

  phoneNumberInput.change(function(){

      let selection = $(this).val();
      if(selection.length == 8){
        $('#phoneNumberDisplay').show();
      }else{
          $('#phoneNumberDisplay').hide();
      }
  });

  $(document).on('wheel', 'input[type=number]', function (e) {
      $(this).blur();
  });

  ";




$this->registerJs($js);


/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="restaurant-form card">

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


    $form = ActiveForm::begin([
                'id' => 'dynamic-form',
                'errorSummaryCssClass' => 'alert alert-danger'
    ]);
    ?>


    <div class="card-header">
      <h3>Basic Info </h3>
    </div>
    <div class="card-body">

        <?= $form->errorSummary([$model], ['header' => '<h4 class="alert-heading">Please fix the following errors:</h4>']); ?>

        <div class="row">
            <div class="col-12 col-sm-6 col-lg-6">
                <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-12 col-sm-6 col-lg-6">

                <?= $form->field($model, 'name_ar')->textInput(['maxlength' => true]) ?>
            </div>
        </div>

        <div class="row">

            <div class="col-12 col-sm-6 col-lg-6">

                <?= $form->field($model, 'tagline')->textInput(['maxlength' => true]) ?>
            </div>

            <div class="col-12 col-sm-6 col-lg-6">

                <?= $form->field($model, 'tagline_ar')->textInput(['maxlength' => true]) ?>
            </div>
        </div>


        <div class="row">

            <div class="col-12 col-sm-6 col-lg-6">
                <?=
                $form->field($model, 'restaurant_thumbnail_image', [
                    'template' => "{label}"
                    . "            <div class='input-group'>"
                    . "             <div class='custom-file'>"
                    . "                 {input}"
                    . "                 <label class='custom-file-label' for='exampleInputFile'>Choose file</label>"
                    . "             </div>"
                    . "            </div>"
                ])->fileInput([
                    'multiple' => false,
                    'accept' => 'image/*',
                    'class' => 'custom-file-input',
                ])
                ?>
            </div>

            <div class="col-12 col-sm-6 col-lg-6">

                <?=
                $form->field($model, 'restaurant_logo', [
                    'template' => "{label}"
                    . "            <div class='input-group'>"
                    . "             <div class='custom-file'>"
                    . "                 {input}"
                    . "                 <label class='custom-file-label' for='exampleInputFile'>Choose file</label>"
                    . "             </div>"
                    . "            </div>"
                ])->fileInput([
                    'multiple' => false,
                    'accept' => 'image/*',
                    'class' => 'custom-file-input',
                ])
                ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-sm-6 col-lg-6">
                <?=
                $form->field($model, 'support_delivery')->radioList([1 => 'Yes', 0 => 'No',], [
                    'style' => 'display:grid',
                    'item' => function($index, $label, $name, $checked, $value) {

                        $return = '<label class="vs-radio-con">';
                        /* -----> */ if ($checked)
                            $return .= '<input checked  type="radio" name="' . $name . '"value="' . $value . '" tabindex="3">';
                        /* -----> */
                        else
                            $return .= '<input  type="radio" name="' . $name . '"value="' . $value . '" tabindex="3">';
                        $return .= '<span class="vs-radio"> <span class="vs-radio--border"></span> <span class="vs-radio--circle"></span> </span>';
                        $return .= '<span>' . ucwords($label) . '</span>';
                        $return .= '</label>';

                        return $return;
                    }
                ]);
                ?>
            </div>

            <div class="col-12 col-sm-6 col-lg-6">

                <?=
                $form->field($model, 'support_pick_up')->radioList([1 => 'Yes', 0 => 'No',], [
                    'style' => 'display:grid',
                    'item' => function($index, $label, $name, $checked, $value) {

                        $return = '<label class="vs-radio-con">';
                        /* -----> */ if ($checked)
                            $return .= '<input checked  type="radio" name="' . $name . '"value="' . $value . '" tabindex="3">';
                        /* -----> */
                        else
                            $return .= '<input  type="radio" name="' . $name . '"value="' . $value . '" tabindex="3">';
                        $return .= '<span class="vs-radio"> <span class="vs-radio--border"></span> <span class="vs-radio--circle"></span> </span>';
                        $return .= '<span>' . ucwords($label) . '</span>';
                        $return .= '</label>';

                        return $return;
                    }
                ]);
                ?>

            </div>
        </div>

        <div class="row">
            <div class="col-12 col-sm-6 col-lg-6">
                <?= $form->field($model, 'phone_number')->input('number', ['id' => 'phoneNumberInput']) ?>
            </div>
            <div class="col-12 col-sm-6 col-lg-6">

                <?= $form->field($model, 'restaurant_email')->input('email') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-sm-6 col-lg-6">

                <div id="phoneNumberDisplay" <?= $model->phone_number ? "style = display:block " : "style = display:none " ?> >
                    <?=
                    $form->field($model, 'phone_number_display')->radioList(
                            [2 => '📞', 3 => '+965 12345678', 1 => 'Dont show phone number button'], [
                        'style' => 'display:grid',
                        'item' => function($index, $label, $name, $checked, $value) {

                            $return = '<label class="vs-radio-con">';
                            /* -----> */ if ($checked)
                                $return .= '<input checked  type="radio" name="' . $name . '"value="' . $value . '" tabindex="3">';
                            /* -----> */
                            else
                                $return .= '<input  type="radio" name="' . $name . '"value="' . $value . '" tabindex="3">';
                            $return .= '<span class="vs-radio"> <span class="vs-radio--border"></span> <span class="vs-radio--circle"></span> </span>';
                            $return .= '<span>' . ucwords($label) . '</span>';
                            $return .= '</label>';

                            return $return;
                        }
                            ]
                    );
                    ?>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-lg-6">

                <?=
                $form->field($model, 'store_layout')->radioList([Restaurant::STORE_LAYOUT_LIST => 'List', Restaurant::STORE_LAYOUT_GRID => 'Grid'], [
                    'style' => 'display:grid',
                    'item' => function($index, $label, $name, $checked, $value) {

                        $return = '<label class="vs-radio-con">';
                        /* -----> */ if ($checked)
                            $return .= '<input checked  type="radio" name="' . $name . '"value="' . $value . '" tabindex="3">';
                        /* -----> */
                        else
                            $return .= '<input  type="radio" name="' . $name . '"value="' . $value . '" tabindex="3">';
                        $return .= '<span class="vs-radio"> <span class="vs-radio--border"></span> <span class="vs-radio--circle"></span> </span>';
                        $return .= '<span>' . ucwords($label) . '</span>';
                        $return .= '</label>';

                        return $return;
                    },
                ]);
                ?>
            </div>
        </div>


        <?= $form->field($model, 'instagram_url')->textInput(['maxlength' => true]) ?>

        <?php
        // $form->field($model, 'armada_api_key')->textInput(['maxlength' => true])
        ?>


        <?php
        // echo $form->field($model, 'schedule_order', [
        //     'template' => "<div class='custom-control custom-switch custom-control-inline'><span style='margin-right: 10px;padding: 0px; display: block;' class='switch-label'>Schedule Order</span>{input}<label class='custom-control-label' for='scheduleOrder'> </label></div>\n<div class=\"col-lg-8\">{error}</div>",
        // ])->checkbox([
        //     'checked' => $model->schedule_order == 0 ? false : true,
        //     'id' => 'scheduleOrder',
        //     'class' => 'custom-control-input'
        //         ], false)->label(false)
        ?>

        <?=
        $form->field($model, 'schedule_interval')->textInput(['maxlength' => true, 'type' => 'number'])->label('Schedule Interval <span style="color: rgba(0,0,0,.45);">(Period in minutes)</span>')
        ?>



        <?=
        $form->field($model, 'restaurant_email_notification', [
            'template' => "<div class='custom-control custom-switch custom-control-inline'><span style='margin-right: 10px;padding: 0px; display: block;' class='switch-label'>Email notification</span>{input}<label class='custom-control-label' for='customSwitch1'> </label></div>\n<div class=\"col-lg-8\">{error}</div>",
        ])->checkbox([
            'checked' => $model->restaurant_email_notification == 0 ? false : true,
            'id' => 'customSwitch1',
            'class' => 'custom-control-input'
                ], false)->label(false)
        ?>

      </div>
      </div>

      <div class="card">
        <div class="card-header">
          <h3>Theme Color </h3>
        </div>
                <div class="card-body row">
                    <div class="col-12">

                        <div class="ant-form-item-label"><label class="" title="Primary Color">Primary Color</label></div>

                        <div id="primary-wrapper" style=" cursor: pinter; width:100%;margin-bottom: 21px; position: relative;background:<?= $store_theme_model->primary ?>" class="text-center colors-container rounded text-white  height-40 d-flex align-items-center justify-content-center  my-1 shadow">
                            <?=
                            $form->field($store_theme_model, 'primary')->textInput(
                                    [
                                        'type' => 'color',
                                        'style' => 'position: absolute; width: 100%; height: 100%; opacity: 0; cursor: pointer;  top: 0;   right: 0;',
                                        'id' => 'primaryColorInput'
                            ])->label('');
                            ?>
                        </div>
                    </div>
                </div>



      </div>


        <div class="form-group" style="background: #f4f6f9; padding-bottom: 10px; margin-bottom: 0px; padding-bottom: 15px; background:#f4f6f9 ">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
        </div>

        <?php ActiveForm::end(); ?>

</div>
