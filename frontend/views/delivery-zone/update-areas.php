

<?php


use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\View;
use common\models\BusinessLocation;
use common\models\City;
use common\models\Area;
use common\models\AreaDeliveryZone;


$this->params['restaurant_uuid'] = $storeUuid;

?>

<?php

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



    var cardId = '#' + $(this).parent().parent().attr('id');

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




    var cardId = '#' + $(this).parent().parent().attr('id');

    $(cardId).find('input').each(function () {
         if($(this).prop('checked') == true){
             $(this).removeAttr('checked');
         }
    });

  });
";

$this->registerJs($js);

$form = ActiveForm::begin([
     'enableClientValidation' => false
 ]);


foreach ($cities as $cityIndex => $city) {
    // $areaQuery = Area::find()->where(['city_id' => $city->city_id])->asArray()->all();

    $sql = "
    SELECT DISTINCT area_id, area_name FROM area
      WHERE NOT EXISTS (
                    SELECT * FROM area_delivery_zone
                    WHERE area_delivery_zone.is_deleted = 0  AND area_delivery_zone.area_id = area.area_id AND  area_delivery_zone.restaurant_uuid = '" . $storeUuid ."' AND delivery_zone_id != ". $model->delivery_zone_id ."
                   )
       AND city_id = " . $city->city_id ."
        ";

    $areaQuery =  Yii::$app->db->createCommand($sql)->queryAll();

    $areaArray = ArrayHelper::map($areaQuery, 'area_id', 'area_name');



    $selectAll[$city->city_id] = false;



    ?>

    <div class="card mb-75" id="city-<?= $city->city_id ?>">
      <a style="font-size: 15px;" id="<?= $city->city_id ?>" class=<?= sizeof($areaArray) > 0 ? 'collapseBtn' : ''?> data-action=<?= sizeof($areaArray) > 0 ? 'collapse' : '' ?> >

        <div class="card-header p-50" style="border-radius: 0.25rem; border: 1px solid #ecedf1; border-bottom-left-radius: 0px;
             border-bottom-right-radius: 0px;">

            <p class=" pr-50 pl-50" style=" margin:0px;   font-weight: 500;">

                <?= $city->city_name . ' (' .  AreaDeliveryZone::find()->where(['delivery_zone_id' => $model->delivery_zone_id ,'restaurant_uuid' => $storeUuid , 'city_id' => $city->city_id, 'country_id' => $city->country_id])->count() . ')'?>

            </p>

        </div>
      </a>



        <div class="card-content collapse" id="collapse-<?= $city->city_id ?>"   style="border-radius: 0.25rem; border: 1px solid #ecedf1; border-top:none; padding-top:10px">

            <?php

              if(sizeof($areaArray)){
                  echo Html::button(' Select all', ['class' => 'btn selectAll', 'id' => 'selectAll-' . $city->city_id, 'style' => 'color :#7367F0; padding-top: 0px; padding-left: 14px;' ]);
                  echo Html::button(' Clear all', ['class' => 'btn clearAll', 'id' => 'clearAll-' . $city->city_id, 'style' => 'display :none; color :#7367F0; padding-top: 0px; padding-left: 14px;']);
              }

            ?>

            <?php foreach ($areaQuery as $key => $area) { ?>

                <div class="form-group field-deliveryzone-selectedareas">

                    <input type="hidden" name="DeliveryZone[selectedAreas][<?= $cityIndex ?>][<?= $key ?>]" value="">
                    <div id="deliveryzone-selectedareas-1-2" style="display:grid">

                        <label class="checkbox col-md-4" style="font-weight: normal;">

                            <div class="vs-checkbox-con vs-checkbox-primary">

                                <input type="checkbox" name="DeliveryZone[selectedAreas][<?= $cityIndex ?>][<?= $key ?>][]" <?= ($selectedAreas && array_key_exists($area['area_id'], $selectedAreas)) || $selectAll[$city->city_id] ? 'checked' : '' ?>  value="<?= $area['area_id'] ?> ">
                                <span class="vs-checkbox">
                                    <span class="vs-checkbox--check">
                                        <i class="vs-icon feather icon-check"></i>
                                    </span>
                                </span>
                                <?= $area['area_name'] ?>

                            </div>

                        </label>
                    </div>

                    <div class="help-block"></div>
                </div>
            <?php } ?>
        </div>
    </div>



<?php } ?>
<div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
</div>

<?php
  ActiveForm::end();
?>
