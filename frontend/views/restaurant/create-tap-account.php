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

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Create Tap account';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>


<div class="restaurant-view" style="width: 66.666667%;">

    <?php
    $form = ActiveForm::begin([
                'id' => 'dynamic-form',
                'errorSummaryCssClass' => 'alert alert-danger'
    ]);
    ?>

    <div class="card">
        <div class="card-header">
            <h3>Legal Info</h3>
        </div>
        <div class="card-body">

            <?= $form->errorSummary([$model], ['header' => '<h4 class="alert-heading">Please fix the following errors:</h4>']); ?>

            <div class="row">
                <div class="col-12 col-sm-6 col-lg-6">
                    <?= $form->field($model, 'owner_first_name')->textInput(['maxlength' => true])->label('First Name') ?>
                </div>

                <div class="col-12 col-sm-6 col-lg-6">
                  <?= $form->field($model, 'owner_first_name')->textInput(['maxlength' => true])->label('Last Name') ?>
                </div>
            </div>

            <div class="row">

                <div class="col-12 col-sm-6 col-lg-6">
                  <?= $form->field($model, 'owner_email')->textInput(['maxlength' => true, 'type' =>'email'])->label('Email Address') ?>
                </div>

                <div class="col-12 col-sm-6 col-lg-6">
                  <?= $form->field($model, 'owner_number')->textInput(['maxlength' => true])->label('Phone Number') ?>
                </div>
            </div>
            <div class="row">

                <div class="col-12">

                      <?=
                      $form->field($model, 'owner_identification_file')->widget(FileInput::classname(), [
                          'options' => [
                              'accept' => 'image/*',
                              'multiple' => false
                          ]
                      ])->label('Upload National ID (front and back side)');
                      ?>

                </div>

            </div>
          </div>
        </div>

    <div class="card">
        <div class="card-header">
            <h3>Licensed Business</h3>
        </div>

        <div class="card-body">

          <p>Enable it if your store operations is formally licensed.</p>

            <?= $form->errorSummary([$model], ['header' => '<h4 class="alert-heading">Please fix the following errors:</h4>']); ?>

            <div class="row">
                <div class="col-12 col-sm-6 col-lg-6">
                    <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>
                </div>

                <div class="col-12 col-sm-6 col-lg-6">
                  <?= $form->field($model, 'license_number')->textInput(['maxlength' => true]) ?>
                </div>
            </div>


            <div class="row">

                <div class="col-6">

                      <?=
                      $form->field($model, 'restaurant_commercial_license_file')->widget(FileInput::classname(), [
                          'options' => [
                              'accept' => 'image/*',
                              'multiple' => false
                          ]
                      ])->label('License copy');
                      ?>

                </div>
                <div class="col-6">

                      <?=
                      $form->field($model, 'restaurant_authorized_signature_file')->widget(FileInput::classname(), [
                          'options' => [
                              'accept' => 'image/*',
                              'multiple' => false
                          ]
                      ])->label('Authorized Signatory');
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

            <?= $form->errorSummary([$model], ['header' => '<h4 class="alert-heading">Please fix the following errors:</h4>']); ?>


            <div class="row">


              <div class="col-12">

                <?= $form->field($model, 'iban')->textInput(['maxlength' => true]) ?>

              </div>

                <div class="col-12">

                  <?=
                  $form->field($model, 'business_type')->radioList(['ind' => 'Individual', 'corp' => 'Company',], [
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
          </div>
        </div>

        <div class="form-group" style="background: #f4f6f9; padding-bottom: 10px; margin-bottom: 0px; padding-bottom: 15px; background:#f4f6f9 ">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success', 'style' => 'width: 100%;height: 50px;']) ?>
        </div>

    </div>




    <?php ActiveForm::end(); ?>

</div>
