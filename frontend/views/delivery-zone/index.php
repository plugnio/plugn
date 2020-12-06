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


            <?php if (Yii::$app->session->getFlash('errorResponse') != null) { ?>

                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="icon fa fa-ban"></i> Warning!</h5>
                    <?= (Yii::$app->session->getFlash('errorResponse')) ?>
                </div>
            <?php }  ?>





      <?php

        foreach ($store_model->getShippingCountries()->all()  as $country) {

          $deliveryZones = new \yii\data\ActiveDataProvider([
              'query' => $store_model->getDeliveryZonesForSpecificCountry($country->country_id),
              'pagination' => false
          ]);


      ?>





      <div class="card">

        <div class="card-header">

            <h1>
              <?= $country->country_name ?>
            </h1>

              <?= Html::a('<i class="feather icon-plus"></i> Add New', ['create', 'restaurantUuid' => $store_model->restaurant_uuid, 'businessLocationId' => $business_location_model->business_location_id, 'countryId' => $country->country_id], ['class' => 'btn btn-outline-primary']) ?>

        </div>


        <div class="card-body">

          <div class="card-content">


                  <?=
                  GridView::widget([
                      'dataProvider' =>  $deliveryZones,
                      // 'rowOptions' => function($model) {
                      //     $url = Url::to(['update', 'id' => $model->delivery_zone_id, 'restaurantUuid' => $model->restaurant->restaurant_uuid]);
                      //
                      //     return [
                      //         'onclick' => "window.location.href='{$url}'"
                      //     ];
                      // },
                      'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        'delivery_time',
                        'delivery_fee',
                        'min_charge'
                      ],

                      'layout' => '{summary}<div class="card-body"><div class="box-body table-responsive no-padding">{items}{pager}</div></div>',
                      'tableOptions' => ['class' => 'table'],
                  ]);
                  ?>

          </div>


        </div>



      </div>

      <?php

          }

      ?>


    </section>
    <!-- Data list view end -->
