<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DeliveryZone */

$this->title = 'Create Delivery Zone';
$this->params['breadcrumbs'][] = ['label' => 'Delivery Zones', 'url' => ['index',  'restaurantUuid' => $restaurantUuid]];
$this->params['breadcrumbs'][] = $this->title;
$this->params['restaurant_uuid'] = $restaurantUuid;

?>
<div class="delivery-zone-create">


    <?= $this->render('_form', [
        'model' => $model,
        'restaurantUuid' => $restaurantUuid
    ]) ?>

</div>
