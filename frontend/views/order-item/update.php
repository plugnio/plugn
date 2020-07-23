<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OrderItem */
$this->params['restaurant_uuid'] = $model->restaurant->restaurant_uuid;

$this->title = 'Update Order Item: ' . $model->item_name;
$this->params['breadcrumbs'][] = ['label' => 'Order #' . $model->order_uuid, 'url' => ['order/update','id' => $model->order_uuid, 'restaurantUuid' => $model->restaurant->restaurant_uuid]];
$this->params['breadcrumbs'][] = ['label' =>  $model->item->item_name, 'url' => ['order-item/view','id' => $model->order_item_id, 'restaurantUuid' => $model->restaurant->restaurant_uuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="order-item-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
