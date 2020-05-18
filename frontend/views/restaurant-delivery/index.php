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

";


if ($dataProvider) {
    ?> 
    <p>
        <?= Html::a('Edit Delivery Zones', ['update', 'restaurantUuid' => $restaurantUuid], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    foreach ($dataProvider as $city) {
        if ($city->restaurantDeliveryAreas) {
            ?>
            <div>
                <div class="card card-default">
                    <div class="card-header">
                        <h1 class="card-title" style="font-size: 25px; font-weight: 500"><?= $city->city_name ?></h1>


                        <div class="card-tools">
                            <?php
                            echo Html::a(
                                    '<span style="margin-right: 20px;" class="nav-icon fas fa-edit"></span>', ['restaurant-delivery/update-delivery-time-for-city', 'city_id' => $city->city_id, 'restaurantUuid' => $restaurantUuid]
                            );
                            ?>
                            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>

                        </div>
                    </div>
                    <div class="card-body">

                        <div class="box-body table-responsive no-padding">

                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>Area</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
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

                                            <td class="project-actions text-right" style="padding-top: 43px">
                                                <?=
                                                Html::submitButton('<i class="fas fa-folder"> </i> Save', ['class' => 'btn btn-success btn-sm', 'name' => $restaurantDeliveryArea->area->area_id])
                                                ?>


                                                <?=
                                                Html::a('<i class="fas fa-trash"></i> Delete', ['delete', 'area_id' => $restaurantDeliveryArea->area->area_id, 'restaurantUuid' => $restaurantDeliveryArea->restaurant_uuid], ['class' => 'btn btn-danger btn-sm',
                                                    'data' => [
                                                        'method' => 'post',
                                                    ]
                                                ])
                                                ?>


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