<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantDelivery */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Update Delivery Zone: ';
$this->params['breadcrumbs'][] = ['label' => 'Delivery Zones', 'url' => ['index', 'storeUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="restaurant-delivery-update">


    <?= $this->render('_form-delivery-zone', [
        'model' => $model,
    ]) ?>

</div>
