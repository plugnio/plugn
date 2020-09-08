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


$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Update Store design and layout';
$this->params['breadcrumbs'][] = ['label' => 'Design & layout', 'url' => ['view-design-layout','restaurantUuid' =>$model->restaurant_uuid]];
$this->params['breadcrumbs'][] = 'Update design & layout';

?>

<div class="restaurant-form card">

    <?php

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

                <?php


                if(
$model->restaurant_uuid == 'rest_b07610b9-bcbb-11ea-808a-0673128d0c9c' ||
$model->restaurant_uuid == 'rest_204f0963-e94f-11ea-808a-0673128d0c9c' ||
$model->restaurant_uuid == 'rest_d7f4f8b8-ebc7-11ea-808a-0673128d0c9c' ||
$model->restaurant_uuid == 'rest_c2aff830-ebd4-11ea-808a-0673128d0c9c' ||
$model->restaurant_uuid == 'rest_3afe275f-ebd4-11ea-808a-0673128d0c9c' ||
                  $model->restaurant_uuid == 'rest_00f54a5e-7c35-11ea-997e-4a682ca4b290' || $model->restaurant_uuid == 'rest_73100b93-cf41-11ea-808a-0673128d0c9c'){
                  echo  $form->field($model, 'store_layout')->radioList([
                              Restaurant::STORE_LAYOUT_LIST_FULLWIDTH => 'List',
                              Restaurant::STORE_LAYOUT_GRID_FULLWIDTH  => 'Grid',
                              Restaurant::STORE_LAYOUT_CATEGORY_FULLWIDTH  => 'Category',
                              Restaurant::STORE_LAYOUT_LIST_HALFWIDTH => 'List - Half',
                              Restaurant::STORE_LAYOUT_GRID_HALFWIDTH => 'Grid - Half',
                              Restaurant::STORE_LAYOUT_CATEGORY_HALFWIDTH => 'Category - Half',
                            ], [
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
                } else {
                  echo $form->field($model, 'store_layout')->radioList([
                            Restaurant::STORE_LAYOUT_LIST_FULLWIDTH => 'List',
                            Restaurant::STORE_LAYOUT_GRID_FULLWIDTH  => 'Grid'
                          ], [
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
                }
                ?>
            </div>
        </div>


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
