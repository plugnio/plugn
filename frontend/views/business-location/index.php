<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\BusinessLocation;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\BusinessLocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Business Locations';
$this->params['restaurant_uuid'] = $restaurantUuid;


$js = "
$(function () {
  $('.summary').insertAfter('.top');
});
";
$this->registerJs($js);

?>

<!-- Data list view starts -->
  <section id="data-list-view" class="data-list-view-header">



    <?= Html::a('Add Business location', ['create', 'restaurantUuid' => $restaurantUuid], ['class' => 'btn btn-success']); ?>


      <?php

        foreach ($businessLocations as $key => $businessLocation) {

          $numberOfAreasStoreDeliveringTo = $businessLocation->getAreaDeliveryZones()->where(['not', ['area_id' => null]])->count();
          $numberOfCoutnriesStoreDeliveringTo = $businessLocation->getAreaDeliveryZones()->where(['area_id' => null])->count();


      ?>
      <div class="card mt-2">


        <div class="card-content">
        <div class="card-body">
          <h1><?= $businessLocation->business_location_name_ar ?></h1>


              <small>Support pickup</small>
              <span style="display: block">
                <?= $businessLocation->support_pick_up ? 'Yes' : 'No' ?>
              </span>


            <?php


              if($businessLocation->getDeliveryZones()->count()  == 0 ){
                echo'<div class="card"><div style="padding: 70px 0; text-align: center;">'
                . '     <h4>You currently do not have any places you deliver to</h4>'
                . Html::a('Setup Delivery Zones', ['delivery-zone/create', 'restaurantUuid' => $restaurantUuid, 'businessLocationId' => $businessLocation->business_location_id], ['class' => 'btn btn-success'])
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
                .  Html::a('Manage Delivery Zones', ['delivery-zone/index', 'restaurantUuid' => $restaurantUuid, 'businessLocationId' => $businessLocation->business_location_id], ['class' => 'btn btn-success'])
                . '</div></div>';
              }

            ?>



        </div>

      </div>
      </div>

    <?php } ?>


  </section>
  <!-- Data list view end -->
