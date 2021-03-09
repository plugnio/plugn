<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\DeliveryZoneSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Delivery Zones for ' . $business_location_model->business_location_name . ' | ' . $business_location_model->country->country_name;
$this->params['breadcrumbs'][] = ['label' => 'Business Locations', 'url' => ['business-location/index', 'storeUuid' => $business_location_model->restaurant_uuid]];
$this->params['breadcrumbs'][] = $this->title;
$this->params['restaurant_uuid'] = $store_model->restaurant_uuid;

?>

<style>


      @media only screen and (max-width:992px) {
        .second-column {
          padding: 0px !important;
          margin-top: 10px !important;
        }
      }

      @media only screen and (min-width:992px) {
        .second-column {
          padding: 0px 10px !important;
          margin-top: 0px !important;
        }
      }



</style>
  <!-- Data list view starts -->
    <section id="data-list-view" class="data-list-view-header">

      <?php

      if(!$business_location_model->getDeliveryZones()->where(['country_id' => $business_location_model->country_id])->exists()){
          echo  Html::a('Add delivery zone to ' . $business_location_model->country->country_name ,
                  ['create', 'storeUuid' => $store_model->restaurant_uuid, 'businessLocationId' => $business_location_model->business_location_id, 'countryId' => $business_location_model->country_id],
                  ['class' => 'btn btn-outline-primary', 'style' => 'margin-bottom : 15px']);

          echo  Html::a('Add delivery zone other country',
                   ['create', 'storeUuid' => $store_model->restaurant_uuid, 'businessLocationId' => $business_location_model->business_location_id],
                   ['class' => 'btn btn-outline-primary', 'style' => 'margin-bottom : 15px; margin-left:10px']);
      } else {
        echo Html::a('Add delivery zone',
          ['create', 'storeUuid' => $store_model->restaurant_uuid, 'businessLocationId' => $business_location_model->business_location_id],
          ['class' => 'btn btn-outline-primary', 'style' => 'margin-bottom : 15px']);
      }

      ?>


            <?php if (Yii::$app->session->getFlash('errorResponse') != null) { ?>

                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fa fa-ban"></i> Warning!</h5>
                    <?= (Yii::$app->session->getFlash('errorResponse')) ?>
                </div>
            <?php }  ?>


      <?php

        foreach ($dataProvider->query->all()  as $deliveryZone) {


      ?>

      <div class="card" style="position:relative">

        <?php
          // Html::a('Delete',
          // ['delete', 'id' => $deliveryZone->delivery_zone_id, 'storeUuid' => $store_model->restaurant_uuid],
          // [
          //   'style' => ' position: absolute; top: 10px; right: 10px; color:#EA5455',
          //   'data' => [
          //       'confirm' => 'Are you sure you want to delete this delivery zone?',
          //       'method' => 'post',
          //   ]
          // ],
          // )
        ?>



        <div class="card-header">

          <div>
            <h5>
              Delivery from <?= $deliveryZone->businessLocation->country->country_name ?> to
            </h5>
            <h3>
              <?= $deliveryZone->country->country_name ?>
            </h3>
          </div>

        </div>

        <div class="card-content">
          <div class="card-body">
            <div class="row" style="padding-left: 10px">

              <div class="col-12 col-sm-12 col-lg-6" style="border-width: 0.5px; border-style: solid; border-color: #e2e8f0; padding: 10px; border-radius: 7px;     position: relative;">

                <?=
                  Html::a('Edit <i class="feather icon-edit"></i>',
                  ['update', 'id' => $deliveryZone->delivery_zone_id, 'storeUuid' => $store_model->restaurant_uuid],
                  [ 'style' => ' position: absolute; top: 10px; right: 10px;'])
                ?>

                <h5>
                  Delivers in
                </h5>
                <p>
                  <?= $deliveryZone->delivery_time . ' ' . $deliveryZone->timeUnit ?>
                </p>

                <h5>
                  Delivery fee
                </h5>
                <p>
                  <?= Yii::$app->formatter->asCurrency($deliveryZone->delivery_fee, $deliveryZone->currency->code, [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 3]) ?>
                </p>


                <h5>
                  Minimum charge on each order:
                </h5>
                <p style=" margin: 0px;">
                  <?= Yii::$app->formatter->asCurrency($deliveryZone->min_charge, $deliveryZone->currency->code, [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 3]) ?>
                </p>

              </div>

            <div class="col-12 col-sm-12 col-lg-6" >

              <div class="row second-column">

                <div class="col-12" style=" margin-bottom:12px; border-width: 0.5px; border-style: solid; border-color: #e2e8f0; padding: 10px; border-radius: 7px;     position: relative;">

                  <?php
                    if($deliveryZone->country->areas){
                        echo  Html::a('Edit <i class="feather icon-edit"></i>',
                                      ['update-areas', 'id' => $deliveryZone->delivery_zone_id, 'storeUuid' => $store_model->restaurant_uuid],
                                      [ 'style' => ' position: absolute; top: 10px; right: 10px;']);
                    }
                  ?>


                  <h5>
                    Delivery allowed to
                  </h5>

                  <p>
                    <?=
                        sizeof($deliveryZone->country->areas) > 0 ?
                        $deliveryZone->getAreaDeliveryZones()->count() . ' out of ' . sizeof($deliveryZone->country->areas) .' areas in ' . $deliveryZone->country->country_name :
                        'All over ' . $deliveryZone->country->country_name
                    ?>
                  </p>
                </div>

                <div class="col-12" style="margin-top:12px; border-width: 1px; border-style: solid; border-color: #e2e8f0; padding: 10px; border-radius: 7px;  position: relative;">


                  <?php
                    if(!$deliveryZone->delivery_zone_tax){
                        echo  Html::a('Override <i class="feather icon-edit"></i>',
                                       ['update-delivery-zone-vat', 'deliveryZoneId' => $deliveryZone->delivery_zone_id, 'storeUuid' => $store_model->restaurant_uuid],
                                      [ 'style' => ' position: absolute; top: 10px; right: 10px;']);
                    } else {
                      echo  Html::a('Cancel override',
                          ['remove-tax-override', 'deliveryZoneId' => $deliveryZone->delivery_zone_id, 'storeUuid' => $store_model->restaurant_uuid],
                                    [ 'style' => ' position: absolute; top: 10px; right: 10px; color: #EA5455']);
                    }
                  ?>


                  <h5>
                    VAT Charged
                  </h5>

                  <p>
                    <?= $deliveryZone->delivery_zone_tax !== null ? $deliveryZone->delivery_zone_tax . '% for this delivery zone' : $deliveryZone->businessLocation->business_location_tax . '% as per business location' ?>
                  </p>
                </div>

              </div>

            </div>
          </div>

          </div>


        </div>



      </div>

      <?php

          }

      ?>


    </section>
    <!-- Data list view end -->
