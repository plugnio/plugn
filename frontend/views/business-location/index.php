<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\BusinessLocation;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\BusinessLocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Business Locations';
$this->params['restaurant_uuid'] = $store->restaurant_uuid;


$js = "
$(function () {
  $('.summary').insertAfter('.top');
});
";
$this->registerJs($js);

?>

<!-- Data list view starts -->
  <section id="data-list-view" class="data-list-view-header">



    <?= Html::a($businessLocations ? 'Add another business location' : 'Add Business location', ['create', 'storeUuid' => $store->restaurant_uuid], ['class' => 'btn btn-success']); ?>


      <?php

        foreach ($businessLocations as $key => $businessLocation) {

          $numberOfAreasStoreDeliveringTo = $businessLocation->getAreaDeliveryZones()->where(['not', ['area_id' => null]])->count();
          $numberOfCoutnriesStoreDeliveringTo = $store->getShippingCountries()->asArray()->count();


      ?>




      <div class="card mt-2">


        <div class="card-content">
        <div class="card-body">
        <div class="row">
          <div class="col-12col-sm-12 col-lg-6">
            <h3 style="display: contents;">
              <?=
                  Html::a($businessLocation->business_location_name . ', ' . $businessLocation->country->country_name. ' <i class="feather icon-edit"></i>',
                  ['update',  'id' => $businessLocation->business_location_id, 'storeUuid' => $store->restaurant_uuid],
                  ['class' => '', 'style' => 'margin-bottom : 15px;     margin-right: 20px;']);
              ?>

            </h3>
          </div>
          <div class="col-12 col-sm-12 col-lg-6">
            <?=
              Html::a($businessLocation->support_pick_up ? 'Disable pick up for this location' : 'Enable pick up for this location',
              [$businessLocation->support_pick_up ? 'disable-pickup' : 'enable-pickup',  'id' => $businessLocation->business_location_id, 'storeUuid' => $store->restaurant_uuid],
              ['class' => $businessLocation->support_pick_up ?  'btn btn-danger' : 'btn btn-outline-primary', 'style' => 'float:right'])
            ?>

          </div>


          <div class="col-12" style="padding-top: 10px">
            <?=
              Html::a($businessLocation->business_location_tax ? 'Remove ' . $businessLocation->business_location_tax . '%' : 'Configure tax for this location',
              [$businessLocation->business_location_tax ? 'remove-tax' : 'configure-tax',  'id' => $businessLocation->business_location_id, 'storeUuid' => $store->restaurant_uuid],
              ['class' => $businessLocation->business_location_tax ?  'btn btn-danger' : 'btn btn-outline-primary', 'style' => 'float:right'])
            ?>
          </div>



              </div>


            <?php   if($businessLocation->getDeliveryZones()->count()  == 0 ){ ?>

              <div class="card">
                <h4>You currently do not have any places you deliver to</h4>
                <?=  Html::a('Setup Delivery Zones', ['delivery-zone/create', 'storeUuid' => $store->restaurant_uuid, 'businessLocationId' => $businessLocation->business_location_id], ['class' => 'btn btn-primary']); ?>
              </div>

            <?php  } else {

                $numberOfCoutnriesDeliveringToText = '';

                $numberOfCoutnriesDeliveringToText =  $numberOfCoutnriesStoreDeliveringTo . $numberOfCoutnriesStoreDeliveringTo == 1 ? 'This business location delivers to '. $numberOfCoutnriesStoreDeliveringTo .' country' : 'This business location delivers to '. $numberOfCoutnriesStoreDeliveringTo .' countries';

                ?>

                <div class="card">
                  <h4> <?= $numberOfCoutnriesDeliveringToText ?> </h4>
                  <?=  Html::a('Make changes', ['delivery-zone/index', 'storeUuid' => $store->restaurant_uuid, 'businessLocationId' => $businessLocation->business_location_id], ['class' => 'btn btn-primary']) ?>
                </div>

            <?php  }      ?>


        </div>

      </div>
      </div>

    <?php } ?>


  </section>
  <!-- Data list view end -->
