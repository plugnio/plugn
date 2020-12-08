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
                        [
                            'attribute' => 'delivery_time',
                            "format" => "raw",
                            "value" => function($model) {
                                if($model->time_unit == 'hrs'){
                                  return $model->delivery_time > 1 ? $model->delivery_time . ' Hours' : $model->delivery_time . ' Hour';
                                }
                                else if($model->time_unit == 'day'){
                                  return $model->delivery_time > 1 ? $model->delivery_time . ' Days' : $model->delivery_time . ' Day';
                                }
                                else if($model->time_unit == 'min'){
                                  return $model->delivery_time > 1 ? $model->delivery_time . ' Minutes' : $model->delivery_time . ' Minute';
                                }
                            }
                        ],
                        [
                            'attribute' => 'delivery_fee',
                            "format" => "raw",
                            "value" => function($model) {
                                  return Yii::$app->formatter->asCurrency($model->delivery_fee, $model->currency->code, [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5]);
                            }
                        ],
                        [
                            'attribute' => 'min_charge',
                            "format" => "raw",
                            "value" => function($model) {
                                  return Yii::$app->formatter->asCurrency($model->min_charge, $model->currency->code, [NumberFormatter::MIN_FRACTION_DIGITS => 3, NumberFormatter::MAX_FRACTION_DIGITS => 5]);
                            }
                        ],
                        [
                            'attribute' => 'delivery_zone_tax',
                            "format" => "raw",
                            "value" => function($model) {
                                return   $model->delivery_zone_tax ? $model->delivery_zone_tax . '%': $model->businessLocation->business_location_tax . '%';
                            }
                        ],
                        [
                            'header' => 'Action',
                            'class' => 'yii\grid\ActionColumn',
                            'template' => ' {view} {update} {delete}',
                            'buttons' => [
                                'update' => function ($url, $model) {
                                    return Html::a(
                                                    '<span style="margin-right: 20px;" class="nav-icon feather icon-edit"></span>', ['delivery-zone/update', 'id' => $model->delivery_zone_id, 'restaurantUuid' => $model->restaurant->restaurant_uuid], [
                                                'title' => 'Update',
                                                'data-pjax' => '0',
                                                    ]
                                    );
                                },
                                'delete' => function ($url, $model) {
                                    return Html::a(
                                                    '<span style="margin-right: 20px;color: red;" class="nav-icon feather icon-trash"></span>', ['delivery-zone/delete', 'id' => $model->delivery_zone_id, 'restaurantUuid' => $model->restaurant->restaurant_uuid], [
                                                'title' => 'Delete',
                                                'data' => [
                                                    'confirm' => 'Are you absolutely sure ? You will lose all the information about this delivery-zone with this action.',
                                                    'method' => 'post',
                                                ],
                                    ]);
                                },
                            ],
                        ],
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
