<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantDelivery */

$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = 'Create Restaurant Delivery';
$this->params['breadcrumbs'][] = ['label' => 'Delivery Zone', 'url' => ['index', 'restaurantUuid' => $restaurantUuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="restaurant-delivery-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>