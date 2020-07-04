<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use common\models\Area;
use yii\helpers\ArrayHelper;
use common\models\RestaurantDelivery;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\RestaurantDeliverySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = 'Delivery Zones';
$this->params['breadcrumbs'][] = $this->title;

$js = "
$(function () {
    //Initialize Select2 Elements
    $('.select2').select2()

  })

  $(document).on('wheel', 'input[type=number]', function (e) {
      $(this).blur();
  });



";


if ($dataProvider) {
    ?>
    <div class="col-12">
    <p>
        <?= Html::a('Edit Delivery Zones', ['update', 'restaurantUuid' => $restaurantUuid], ['class' => 'btn btn-success']) ?>
    </p>
  </div>
    <?php
    foreach ($dataProvider as $city) {
        if ($city->restaurantDeliveryAreas) {
            ?>
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><?= $city->city_name ?></h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                              <li>
                                <?=
                                Html::a(
                                 '<i class="feather icon-edit"></i>', ['restaurant-delivery/update-delivery-time-for-city', 'city_id' => $city->city_id, 'restaurantUuid' => $restaurantUuid],['class' => 'action-edit', 'style' =>'color: rgb(98, 98, 98);']
                                 );
                                 ?>
                              </li>
                              <li>
                                  <a style="font-size: 15px;" data-action="collapse"><i class="feather icon-chevron-down"></i></a>

                              </li>
                            </ul>
                          </div>
                        </div>


                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                        <th>Area</th>
                                        <th>Delivery Time</th>
                                        <th>Delivery fee</th>
                                        <th>Min Charge</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($city->restaurantDeliveryAreas as $restaurantDeliveryArea) {
                                        $form = ActiveForm::begin([
                                                    'enableClientScript' => false,
                                        ]);
                                        ?>
                                        <tr>
                                            <td style="vertical-align: inherit;">
                                                <?= $restaurantDeliveryArea->area->area_name ?></td>
                                            <td>
                                                <?= $form->field($restaurantDeliveryArea, 'delivery_time')->input('number') ?></td>
                                            <td>
                                                <?= $form->field($restaurantDeliveryArea, 'delivery_fee')->input('float') ?></td>
                                            <td>
                                                <?= $form->field($restaurantDeliveryArea, 'min_charge')->input('float') ?></td>

                                            <td class="project-actions text-right">
                                              <div style="margin-right: auto; margin-left: auto; display: flex;">


                                                <?=
                                                Html::submitButton('<i class="feather icon-save"> </i> ', ['style' => 'margin-right: 20px;','class' => 'btn btn-success', 'name' => $restaurantDeliveryArea->area->area_id])
                                                ?>
                                                <?=
                                                Html::a('<i class="fa fa-trash"></i> ', ['delete', 'area_id' => $restaurantDeliveryArea->area->area_id, 'restaurantUuid' => $restaurantDeliveryArea->restaurant_uuid], ['class' => 'btn btn-danger ',
                                                    'data' => [
                                                        'method' => 'post',
                                                    ]
                                                ])
                                                ?>





                                              </div>
                                            </td>

                                        </tr>

                                        <?php
                                        ActiveForm::end();
                                    }
                                    ?>

                                    <?php ?>
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </div>

            <?php
        }
    }
} else {
    echo'<div class="card"><div style="padding: 70px 0; text-align: center;">'
    . '     <h4>You currently do not have any places you deliver to</h4>'
    . Html::a('Setup Delivery Zones', ['create', 'restaurantUuid' => $restaurantUuid], ['class' => 'btn btn-success'])
    . '</div></div>';
}
?>
