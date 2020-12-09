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



    <?= Html::a('Add Business location', ['create', 'storeUuid' => $storeUuid], ['class' => 'btn btn-success']); ?>


      <?php

        foreach ($businessLocations as $key => $businessLocation) {

          $numberOfAreasStoreDeliveringTo = $businessLocation->getAreaDeliveryZones()->where(['not', ['area_id' => null]])->count();
          $numberOfCoutnriesStoreDeliveringTo = $businessLocation->getAreaDeliveryZones()->select(['country_id'])->distinct()->count();


      ?>




      <div class="card mt-2">


        <div class="card-content">
        <div class="card-body">
          <h1><?= $businessLocation->business_location_name_ar ?></h1>
          <div>

              <?=
                Html::a('<i class="feather icon-edit"></i> Update',
                ['update',  'id' => $businessLocation->business_location_id, 'storeUuid' => $storeUuid],
                ['class' => 'btn btn-outline-primary', 'style' => 'margin-bottom : 15px;     margin-right: 20px;'])
              ?>


              <?=
                Html::a('<i class="feather icon-trash"></i> Delete',
                ['delete' ,'id' => $businessLocation->business_location_id, 'storeUuid' => $storeUuid],
                ['class' => 'btn btn-danger', 'style' => 'margin-bottom : 15px'])
              ?>

        </div>


              <span style="display: block">
                <?= $businessLocation->support_pick_up ? 'Support pickup' : "Doesn't support pickup" ?>
              </span>


            <?php


              if($businessLocation->getDeliveryZones()->count()  == 0 ){
                echo'<div class="card"><div style="padding: 70px 0; text-align: center;">'
                . '     <h4>You currently do not have any places you deliver to</h4>'
                . Html::a('Setup Delivery Zones', ['delivery-zone/create', 'storeUuid' => $storeUuid, 'businessLocationId' => $businessLocation->business_location_id], ['class' => 'btn btn-success'])
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
