<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\RestaurantDeliverySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'By City';
$this->params['breadcrumbs'][] = $this->title;
?>


<?php
foreach ($dataProvider as $key => $city) {
    ?>
    <div>
        <div class="card card-default">
            <div class="card-header">
                <h1 class="card-title"><?= $key ?></h1>

                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
                </div>
            </div>
            <div class="card-body">

                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Area</th>
                            <th>Min Delivery Time</th>
                            <th>Delivery Fee</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($city as $restaurantDeliveryArea) {
                            ?>
                            <tr>
                                <td><?= $restaurantDeliveryArea->area->area_name ?></td>
                                <td><?= $restaurantDeliveryArea->min_delivery_time ?>
                                <td><?= $restaurantDeliveryArea->delivery_fee ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php }
?>