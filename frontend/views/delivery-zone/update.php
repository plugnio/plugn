<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DeliveryZone */

$this->title = 'Update Delivery Zone: ' . $model->delivery_zone_id;
$this->params['breadcrumbs'][] = ['label' => 'Delivery Zones', 'url' => ['index',  'restaurantUuid' => $restaurantUuid]];
$this->params['breadcrumbs'][] = 'Update';
$this->params['restaurant_uuid'] = $restaurantUuid;

?>
<div class="delivery-zone-update">

    <?= $this->render('_form', [
        'model' => $model,
        'restaurantUuid' => $restaurantUuid
    ]) ?>

</div>
