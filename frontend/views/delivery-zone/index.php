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

      <?= Html::a('<i class="feather icon-plus"></i> Add New',
        ['create', 'storeUuid' => $store_model->restaurant_uuid, 'businessLocationId' => $business_location_model->business_location_id],
        ['class' => 'btn btn-outline-primary', 'style' => 'margin-bottom : 15px'])
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

            <h1>
              <?= $deliveryZone->country->country_name ?>
            </h1>

                    <div>
                        <?=
                          Html::a('<i class="feather icon-edit"></i> Update',
                          ['update', 'id' => $deliveryZone->delivery_zone_id, 'storeUuid' => $store_model->restaurant_uuid],
                          ['class' => 'btn btn-outline-primary', 'style' => 'margin-bottom : 15px;     margin-right: 20px;'])
                        ?>


                        <?=
                          Html::a('<i class="feather icon-trash"></i> Delete', ['delete', 'id' => $deliveryZone->delivery_zone_id, 'storeUuid' => $store_model->restaurant_uuid], [
                               'class' => 'btn btn-danger',
                               'style' => 'margin-bottom:15px',
                               'data' => [
                                   'confirm' => 'Are you sure you want to delete this zone?',
                                   'method' => 'post',
                               ],
                           ]);
                        ?>
                  </div>

        </div>

        <div class="card-body">

          <div class="card-content">

              <h4>
                Delivering to  <?= $deliveryZone->getAreaDeliveryZones()->count() . '/' . $deliveryZone->country->getAreas()->count() ?>
              </h4>
              <p>
                Delivery Time:
                <?= $deliveryZone->delivery_time ?>
              </p>

              <p>
                Delivery Fee:
                <?= $deliveryZone->delivery_fee ?>
              </p>

              <p>
                Min Charge:
                <?= $deliveryZone->delivery_fee ?>
              </p>

          </div>


        </div>



      </div>

      <?php

          }

      ?>


    </section>
    <!-- Data list view end -->
