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


$this->title = 'Update VAT';
$this->params['breadcrumbs'][] = ['label' => 'Delivery Zones', 'url' => ['index',  'storeUuid' => $storeUuid , 'id' => $model->delivery_zone_id]];
$this->params['breadcrumbs'][] = $this->title;
$this->params['restaurant_uuid'] = $storeUuid;

?>


<div class="card">
    <div class="card-body delivery-zone-form">

        <?php

            $form = ActiveForm::begin();
        ?>

        <?= $form->field($model, 'delivery_zone_tax', [
            'template' => "{label}"

            . "<div  class='input-group'>
                <div class='input-group-prepend'>
                  <span class='input-group-text'> % </span>
                </div>
                  {input}
              </div>
            "
            . "{error}{hint}"
        ])->textInput([
        'type' => 'number',
        'step' => '.01',
        'placeholder' => $model->businessLocation->business_location_tax > 0 ? $model->businessLocation->business_location_tax : '' ,
         'value' => $model->delivery_zone_tax ? $model->delivery_zone_tax : '' , 'style' => '    border-top-left-radius: 0px !important;   border-bottom-left-radius: 0px !important;']) ?>


        <!-- <div id="cities"></div> -->

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

      </div>

        <?php ActiveForm::end(); ?>

    </div>
