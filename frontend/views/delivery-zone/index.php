<?php

use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\DeliveryZoneSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Delivery Zones for ' . $business_location_model->business_location_name . ' | ' . $business_location_model->country->country_name;
$this->params['breadcrumbs'][] = $this->title;
$this->params['restaurant_uuid'] = $store_model->restaurant_uuid;

?>

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

      <div class="card">

        <div class="card-header">

          <div>
            <h5>
              Delivery from <?= $deliveryZone->businessLocation->country->country_name ?>
            </h5>
            <h3>
              <?= $deliveryZone->country->country_name ?>
            </h3>
          </div>

        </div>

        <div class="card-content">
          <div class="card-body">
            <div class="row">

              <div class="col-12 col-sm-12 col-lg-4" style="border-width: 1px; border-style: solid; border-color: #e2e8f0; padding: 10px; border-radius: 7px;">

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
                  <?= Yii::$app->formatter->asCurrency($deliveryZone->delivery_fee, $deliveryZone->currency->code, [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5]) ?>
                </p>


                <h5>
                  Minimum charge on each orde:
                </h5>
                <p style=" margin: 0px;">
                  <?= Yii::$app->formatter->asCurrency($deliveryZone->min_charge, $deliveryZone->currency->code, [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5]) ?>
                </p>

              </div>


    

            <div class="col-12 col-sm-12 col-lg-6">

              <div class="row">

                <div class="col-12" style="border-width: 1px; border-style: solid; border-color: #e2e8f0; padding: 10px; border-radius: 7px;">
                  <h5>
                    Delivery allowed to
                  </h5>

                  <p>
                    <?= Yii::$app->formatter->asCurrency($deliveryZone->delivery_fee, $deliveryZone->currency->code, [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5]) ?>
                  </p>
                </div>

                <div class="col-12" style="border-width: 1px; border-style: solid; border-color: #e2e8f0; padding: 10px; border-radius: 7px;">
                  <h5>
                    Delivery allowed to
                  </h5>

                  <p>
                    <?= Yii::$app->formatter->asCurrency($deliveryZone->delivery_fee, $deliveryZone->currency->code, [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5]) ?>
                  </p>
                </div>

              </div>

            </div>
          </div>


            <?php if($deliveryZone->delivery_zone_tax){ ?>

              <p>
                Tax override:
                <?= $deliveryZone->delivery_zone_tax . '%' ?>

                <?php
                echo Html::a('remove',
                          ['remove-tax-override', 'deliveryZoneId' => $deliveryZone->delivery_zone_id, 'storeUuid' => $store_model->restaurant_uuid],
                          ['class' => 'btn btn-danger', 'style' => 'margin-bottom : 15px;     margin-right: 20px;']);

                  ?>

              </p>

            <?php } ?>

            <?=
              Html::a('<i class="feather icon-edit"></i> Update',
              ['update', 'id' => $deliveryZone->delivery_zone_id, 'storeUuid' => $store_model->restaurant_uuid],
              ['class' => 'btn btn-outline-primary', 'style' => 'margin-bottom : 15px;     margin-right: 20px;'])
            ?>


              <h4>
              currently delivering to

              <?=

                ( $deliveryZone->getAreaDeliveryZones()->count() == $deliveryZone->country->getAreas()->count() ) || $deliveryZone->country->getAreas()->count() == 0 ?
                 ' all over ' . $deliveryZone->country->country_name  :
                 $deliveryZone->getAreaDeliveryZones()->count() . '/' . $deliveryZone->country->getAreas()->count()

               ?>

              </h4>

              <?php

                if($deliveryZone->country->getAreas()->count()){
                  echo Html::a('Edit',
                                  ['update-areas', 'id' => $deliveryZone->delivery_zone_id, 'storeUuid' => $store_model->restaurant_uuid],
                                  ['class' => 'btn btn-outline-primary', 'style' => 'margin-bottom : 15px;     margin-right: 20px;']);
                }

                if($deliveryZone->delivery_zone_tax == null){
                    echo Html::a($deliveryZone->businessLocation->business_location_tax. '% VAT override',
                              ['update-delivery-zone-vat', 'id' => $deliveryZone->delivery_zone_id, 'storeUuid' => $store_model->restaurant_uuid],
                              ['class' => 'btn btn-outline-primary', 'style' => 'margin-bottom : 15px;     margin-right: 20px;']);
                }

              ?>



          </div>


        </div>



      </div>

      <?php

          }

      ?>


    </section>
    <!-- Data list view end -->
