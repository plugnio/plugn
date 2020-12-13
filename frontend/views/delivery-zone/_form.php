<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\View;
use common\models\BusinessLocation;
use common\models\City;
use common\models\Area;
use common\models\Country;
use common\models\DeliveryZone;

/* @var $this yii\web\View */
/* @var $model common\models\DeliveryZone */
/* @var $form yii\widgets\ActiveForm */

$url = Yii::$app->request->baseUrl . '/delivery-zone/render-cities-checbox-list?storeUuid='. $storeUuid .'&countryId=';
$selectedAreas = $model->getAreaDeliveryZones()->all();
$selectedAreas = ArrayHelper::map($selectedAreas, 'area_id', 'area_id');
$data = json_encode($selectedAreas);

$js = "


  $('.collapseBtn').on('click', function(e){
    currentId = $(this).attr('id');

    $('#collapse-'+ currentId).collapse('toggle');

  });


  $('.selectAll').on('click', function(e){


    currentId = $(this).attr('id');

    var cityId = currentId.replace('selectAll-','');

    $('#clearAll-' + cityId).show();
    $(this).hide();

    var cardId = '#city-' + $(this).parent().attr('id');

    $(cardId).find('input').each(function () {
         if($(this).prop('checked') == false){
             $(this).attr('checked', 'checked');
         }
    });

  });

  $('.clearAll').on('click', function(e){


    currentId = $(this).attr('id');

    var cityId = currentId.replace('clearAll-','');

    $('#selectAll-' + cityId).show();
    $(this).hide();




    var cardId = '#city-' + $(this).parent().attr('id');

    $(cardId).find('input').each(function () {
         if($(this).prop('checked') == true){
             $(this).removeAttr('checked');
         }
    });

  });
";

$this->registerJs($js);

?>


<div class="card">
    <div class="card-body delivery-zone-form">

        <?php
            $areaQuery = $model->getAreas()->all();
            $areaArray[] = ArrayHelper::map($areaQuery, 'area_id', 'area_name');


            $countryQuery = Country::find()->asArray()->all();
            $countryArray = ArrayHelper::map($countryQuery, 'country_id', 'country_name');

            $form = ActiveForm::begin();
        ?>


        <div class="row">
              <div class="col-12">
                <?=
                    $form->field($model, 'country_id')->dropDownList($countryArray, [
                        'prompt' => 'Choose area name...',
                        'class' => 'form-control select2',
                        'multiple' => false,
                        'id' => 'country-id'
                    ])->label('Select country you want deliver to *');
                ?>
              </div>
        </div>

        <div>
        <div class="row">
              <div class="col-6">
                <?= $form->field($model, 'delivery_time')->textInput()->label('Delivery Time *') ?>
              </div>

              <div class="col-6">
                <?= $form->field($model, 'time_unit')->dropDownList(
                        [
                            DeliveryZone::TIME_UNIT_MIN => 'Minutes',
                            DeliveryZone::TIME_UNIT_HRS => 'Hours',
                            DeliveryZone::TIME_UNIT_DAY => 'Days'
                        ],['value' => $model->time_unit ? $model->time_unit : DeliveryZone::TIME_UNIT_DAY ]
                )->label(''); ?>
              </div>
        </div>

        <?= $form->field($model, 'delivery_fee', [
            'template' => "{label}"

            . "<div  class='input-group'>
                <div class='input-group-prepend'>
                  <span class='input-group-text'>". $model->currency->code ."</span>
                </div>
                  {input}
              </div>
            "

            . "{error}{hint}"
        ])->textInput(
          [
            'maxlength' => true,
            'style' => ' border-top-left-radius: 0px !important;   border-bottom-left-radius: 0px !important;',
            'placeholder' => '3'
            ])->label('Delivery Fee *') ?>

        <?= $form->field($model, 'min_charge', [
            'template' => "{label}"

            . "<div  class='input-group'>
                <div class='input-group-prepend'>
                  <span class='input-group-text'>". $model->currency->code ."</span>
                </div>
                  {input}
              </div>
            "

            . "{error}{hint}"
        ])->textInput(
          [
            'maxlength' => true,
            'placeholder' => '3',
            'style' => '    border-top-left-radius: 0px !important;   border-bottom-left-radius: 0px !important;'
          ])->label('Min Charge on each order not including delivery fee *') ?>



        <!-- <div id="cities"></div> -->

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

      </div>

        <?php ActiveForm::end(); ?>

    </div>
