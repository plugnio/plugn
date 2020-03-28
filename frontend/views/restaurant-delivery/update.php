<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantDelivery */

$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = 'Edit Delivery Zone';
$this->params['breadcrumbs'][] = ['label' => 'Delivery Zones', 'url' => ['index', 'restaurantUuid' => $restaurantUuid]];
$this->params['breadcrumbs'][] = 'Edit Delivery Zone';
?>
<div class="restaurant-delivery-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>