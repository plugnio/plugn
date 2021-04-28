<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\time\TimePicker;
use common\models\Agent;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use common\models\Area;
use common\models\Country;
use common\models\Currency;
use common\models\RestaurantDelivery;
use kartik\file\FileInput;
use common\models\Restaurant;
use kartik\daterange\DateRangePicker;
use borales\extensions\phoneInput\PhoneInput;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Create Tap account';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);



$js = "
$(function () {

  $('#restaurant-identification_issuing_date').attr('autocomplete','off');
  $('#restaurant-identification_issuing_date').attr('style', '  padding-right: 2rem !important; padding-left: 3rem !important; ');


  $('#restaurant-identification_expiry_date').attr('autocomplete','off');
  $('#restaurant-identification_expiry_date').attr('style', '  padding-right: 2rem !important; padding-left: 3rem !important; ');


  $('#restaurant-authorized_signature_issuing_date').attr('autocomplete','off');
  $('#restaurant-authorized_signature_issuing_date').attr('style', '  padding-right: 2rem !important; padding-left: 3rem !important; ');

  $('#restaurant-authorized_signature_expiry_date').attr('autocomplete','off');
  $('#restaurant-authorized_signature_expiry_date').attr('style', '  padding-right: 2rem !important; padding-left: 3rem !important; ');


});

$( window ).on( 'load', function() {
  $('#legal_info').hide();

  if ('$model->business_type' == 'corp'){
    $('#legal_info').show();
  }
  else{
		$('#legal_info').hide();
  }
});


let businessTypeInput = $('#businessTypeInput');
businessTypeInput.change(function(e){
	let selection = businessTypeInput.is(':checked');


	if (e.target.defaultValue == 'corp'){
    $('#legal_info').show();
  }

	else{
		$('#legal_info').hide();
  }

});

$('#submitBtn').click(function() {
  $('#loading').show();
  $('#submitBtn').hide();
});


";
$this->registerJs($js);



$js = <<< JS

// enable fileuploader plugin
$('input[class="document-upload"]').fileuploader({
	limit: 1,
	fileMaxSize: 30,
	extensions: ['image/*'],
	addMore: true,
	thumbnails: {
		onItemShow: function (item) {
      // add sorter button to the item html
      		if (item.choosed )
			item.html.find('.fileuploader-action-remove').before('<button type="button" class="fileuploader-action fileuploader-action-sort" title="Sort"><i class="fileuploader-icon-sort"></i></button>');

			if (item.choosed && !item.html.find('.fileuploader-action-edit').length)
				item.html.find('.fileuploader-action-remove').before('<button type="button" class="fileuploader-action fileuploader-action-popup fileuploader-action-edit" title="Edit"><i class="fileuploader-icon-edit"></i></button>');
		}
	},
	sorter: {
		selectorExclude: null,
		placeholder: null,
		scrollContainer: window,
		onSort: function (list, listEl, parentEl, newInputEl, inputEl) {
			// onSort callback
		}
	},
	editor: {
		cropper: {
			ratio: '1:1',
			minWidth: 100,
			minHeight: 100,
			showGrid: true
		}
	}

});

JS;
$this->registerJs($js);


?>


<div class="row">
<div class="col-12">

    <?php

        $form = ActiveForm::begin([
                    'id' => 'dynamic-form',
                    'errorSummaryCssClass' => 'alert alert-danger'
        ]);
    ?>
    <?= $form->errorSummary([$model], ['header' => '<h4 class="alert-heading">Please fix the following errors:</h4>']); ?>

    <div class="card">
        <div class="card-header">
            <h3>Legal Info</h3>
        </div>
        <div class="card-body">


            <div class="row">
                <div class="col-12 col-sm-6 col-lg-6">
                    <?= $form->field($model, 'owner_first_name')->textInput(['maxlength' => true])->label('First Name *') ?>
                </div>

                <div class="col-12 col-sm-6 col-lg-6">
                    <?= $form->field($model, 'owner_last_name')->textInput(['maxlength' => true])->label('Last Name *') ?>
                </div>
            </div>

            <div class="row">

                <div class="col-12 col-sm-6 col-lg-6">
                    <?= $form->field($model, 'owner_email')->textInput(['maxlength' => true, 'type' => 'email'])->label('Email Address *') ?>
                </div>

                <div class="col-12 col-sm-6 col-lg-6">
                    <?=
                        $form->field($model, 'owner_number')->widget(PhoneInput::className(), [
                             'jsOptions' => [
                                 'preferredCountries' => ['kw', 'sa', 'aed','qa','bh','om'],
                             ]
                         ])->label('Phone Number *');
                   ?>

                </div>
            </div>

            <div class="row">
              <div class="col-12">
                  <?= $form->field($model, 'company_name')->textInput(['maxlength' => true, 'placeholder' => 'official business name if license available'])->label('Business name *') ?>
              </div>
            </div>

            <div class="row">
                <div class="col-12  col-lg-6">

                    <?=
                    $form->field($model, 'owner_identification_file_front_side')->fileinput(['name' => 'identification_file_front_side', 'class' => 'document-upload'])->label('Upload National ID front side  *');
                    ?>

                </div>

                <div class="col-12  col-lg-6 ">

                    <?=
                    $form->field($model, 'owner_identification_file_back_side')->fileinput(['name' => 'identification_file_back_side', 'class' => 'document-upload'])->label('Upload National ID back side  *');
                    ?>

                </div>



            </div>
            <div class="row">


                <div class="col-12 col-sm-6 col-lg-6">

                                      <?=
                                      $form->field($model, 'business_type')->radioList(['ind' => 'Individual', 'corp' => 'Company',], [
                                          'style' => 'display:grid',
                                          'id' => 'businessTypeInput',
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
                                      ])->label('Account type *');
                                      ?>

                </div>

                <div class="col-12 col-sm-6 col-lg-6">

                  <?=
                  $form->field($model, 'vendor_sector')->radioList(['Shopping & Retail' => 'Shopping & Retail', 'F&B' => 'F&B', 'Other' => 'Other'], [
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
                  ])->label('Vendor Sector *');
                  ?>

                </div>

            </div>
        </div>
    </div>

    <div class="card" id="legal_info">
        <div class="card-header">
            <h3>Licensed Business</h3>
        </div>

        <div class="card-body">

            <p>Enable it if your store operations is formally licensed.</p>

            <div class="row">
                <div class="col-12 col-sm-6 col-lg-6">
                    <?= $form->field($model, 'license_number')->textInput(['maxlength' => true]) ?>
                </div>
            </div>

            <div class="row">



                <div class="col-12">

                    <?=
                    $form->field($model, 'restaurant_commercial_license_file')->fileinput(['name' => 'commercial_license', 'class' => 'document-upload'])->label('License copy *');
                    ?>

                </div>


                <div class="col-12">

                    <?=
                    $form->field($model, 'restaurant_authorized_signature_file')->fileinput(['name' => 'authorized_signature', 'class' => 'document-upload'])->label('Authorized signatory *');
                    ?>

                </div>

            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>Bank Info</h3>
        </div>

        <div class="card-body">

            <p>Enter the information of your company bank account if you have a commercial license, if not, enter the information of your personal bank account.</p>


            <div class="row">


                <div class="col-12">

                    <?= $form->field($model, 'iban')->textInput(['maxlength' => true])->label('IBAN *') ?>

                </div>



            </div>
        </div>
    </div>



    <span>
      By signing up you agree with Tap's <a href="https://www.tap.company/kw/en/terms-conditions" target="_blank">Terms And Conditions</a>
    </span>

    <div class="form-group" style="background: #f4f6f9; padding-bottom: 10px; margin-bottom: 0px;  margin-top: 20px; padding-bottom: 15px; background:#f4f6f9 ">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'id' =>'submitBtn', 'style' => 'width: 100%;height: 50px;']) ?>


				<button class="btn btn-success" type="button" id="loading" disabled style="width: 100%;height: 50px; display:none">
					<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
					<span class="ml-25 align-middle">Loading...</span>
				</button>



    </div>

</div>




<?php ActiveForm::end(); ?>

</div>
</div>
