<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\time\TimePicker;
use common\models\Agent;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\Area;
use common\models\RestaurantDelivery;
use kartik\file\FileInput;
use common\models\Restaurant;
use common\models\Currency;
use common\models\Country;
use common\models\Order;
use borales\extensions\phoneInput\PhoneInput;

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

    $form = ActiveForm::begin([
                'id' => 'dynamic-form',
                'errorSummaryCssClass' => 'alert alert-danger'
    ]);

    $countryQuery = Country::find()->asArray()->all();
    $countryArray = ArrayHelper::map($countryQuery, 'country_id', 'country_name');

    $currencyQuery = Currency::find()->asArray()->all();
    $currencyArray = ArrayHelper::map($currencyQuery, 'currency_id', 'title');


    $madeAnySales = Order::find()->where(['restaurant_uuid' => $model->restaurant_uuid ])->exists();

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
              <?= $form->field($model, 'country_id')->dropDownList($countryArray); ?>
            </div>


            <div class="col-12 col-sm-6 col-lg-6">
              <?= $form->field($model, 'currency_id', ['template' =>
                               $madeAnySales  ? "
                               {label} {input} {hint}  {error}
                                    <p>
                                        You've made your first sale, so you need to <a href='mailto:contact@plugn.io'>contact support</a> if you want to change your currency.
                                    </p>"
                              : '{label} {input} {hint}  {error}'
            ])->dropDownList($currencyArray, ['disabled' => $madeAnySales ]); ?>
          </div>

        </div>


        <div class="row">
            <div class="col-12 col-sm-6 col-lg-6">
                <?=
                   $form->field($model, 'phone_number')->widget(PhoneInput::className(), [
                      'jsOptions' => [
                          'preferredCountries' => ['kw', 'sa', 'aed','qa','bh','om'],
                          'initialCountry' => $model->country->iso
                      ]
                  ]);
                ?>
            </div>
            <div class="col-12 col-sm-6 col-lg-6">

                <?= $form->field($model, 'restaurant_email')->input('email') ?>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-sm-6 col-lg-6">
              <?= $form->field($model, 'schedule_order', [
                  'template' => "<div class='custom-control custom-switch custom-control-inline'><span style='margin-right: 10px;padding: 0px; display: block;' class='switch-label'>Schedule Order</span>{input}<label class='custom-control-label' for='scheduleOrder'> </label></div>\n<div class=\"col-lg-8\">{error}</div>",
              ])->checkbox([
                  'checked' => $model->schedule_order == 0 ? false : true,
                  'id' => 'scheduleOrder',
                  'class' => 'custom-control-input'
                      ], false)->label(false)
              ?>
            </div>

            <div class="col-12 col-sm-6 col-lg-6">

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

        <?=
        $form->field($model, 'schedule_interval')->textInput(['maxlength' => true, 'type' => 'number'])->label('Schedule Interval <span style="color: rgba(0,0,0,.45);">(Period in minutes)</span>') ?>





      </div>
      </div>


        <div class="form-group" style="background: #f4f6f9; padding-bottom: 10px; margin-bottom: 0px; padding-bottom: 15px; background:#f4f6f9 ">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
        </div>

        <?php ActiveForm::end(); ?>

</div>
