<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\BusinessLocation;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\BusinessLocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Business Locations';
$this->params['restaurant_uuid'] = $storeUuid;


$js = "
$(function () {
  $('.summary').insertAfter('.top');
});
";
$this->registerJs($js);

?>

<!-- Data list view starts -->
  <section id="data-list-view" class="data-list-view-header">



    <?= Html::a($businessLocations ? 'Add another business location' : 'Add Business location', ['create', 'storeUuid' => $storeUuid], ['class' => 'btn btn-success']); ?>


      <?php

        foreach ($businessLocations as $key => $businessLocation) {

          $numberOfAreasStoreDeliveringTo = $businessLocation->getAreaDeliveryZones()->where(['not', ['area_id' => null]])->count();
          $numberOfCoutnriesStoreDeliveringTo = $businessLocation->getAreaDeliveryZones()->select(['country_id'])->distinct()->count();


      ?>




      <div class="card mt-2">


        <div class="card-content">
        <div class="card-body">
        <div class="row">
          <div class="col-12col-sm-12 col-lg-6">
            <h3 style="display: contents;">
              <?=
                  Html::a($businessLocation->business_location_name . ', ' . $businessLocation->country->country_name. ' <i class="feather icon-edit"></i>',
                  ['update',  'id' => $businessLocation->business_location_id, 'storeUuid' => $storeUuid],
                  ['class' => '', 'style' => 'margin-bottom : 15px;     margin-right: 20px;']);
              ?>

            </h3>
          </div>
          <div class="col-12col-sm-12 col-lg-6">
            <?=
              Html::a($businessLocation->support_pick_up ? 'Disable pick up for this location' : 'Enable pick up for this location',
              [$businessLocation->support_pick_up ? 'disable-pickup' : 'enable-pickup',  'id' => $businessLocation->business_location_id, 'storeUuid' => $storeUuid],
              ['class' => $businessLocation->support_pick_up ?  'btn btn-danger' : 'btn btn-outline-primary', 'style' => 'float:right'])
            ?>
          </div>




              </div>


            <?php


              if($businessLocation->getDeliveryZones()->count()  == 0 ){
                echo'<div class="card"><div style="padding: 70px 0; text-align: center;">'
                . '     <h4>You currently do not have any places you deliver to</h4>'
                . Html::a('Setup Delivery Zones', ['delivery-zone/create', 'storeUuid' => $storeUuid, 'businessLocationId' => $businessLocation->business_location_id], ['class' => 'btn btn-primary'])
                . '</div></div>';
              } else {

                $numberOfAreasDeliveringToText = '';
                $numberOfCoutnriesDeliveringToText = '';

                // if($numberOfAreasStoreDeliveringTo)

                // if($numberOfCoutnriesDeliveringToText)
                  $numberOfCoutnriesDeliveringToText =  $numberOfCoutnriesStoreDeliveringTo . $numberOfCoutnriesStoreDeliveringTo == 1 ? 'Delivering to '. $numberOfCoutnriesStoreDeliveringTo .' country' : 'Delivering to '. $numberOfCoutnriesStoreDeliveringTo .' countries';


                  $numberOfAreasDeliveringToText = 'and '. $numberOfAreasStoreDeliveringTo . ' areas';



                echo'<div class="card"><div style="padding: 70px 0; text-align: center;">'
                . '     <h4>'. $numberOfCoutnriesDeliveringToText  . ' ' . $numberOfAreasDeliveringToText .'</h4>'
                .  Html::a('Manage Delivery Zones', ['delivery-zone/index', 'storeUuid' => $storeUuid, 'businessLocationId' => $businessLocation->business_location_id], ['class' => 'btn btn-success'])
                . '</div></div>';
              }

            ?>



        </div>

      </div>
      </div>

    <?php } ?>


  </section>
  <!-- Data list view end -->
