<?php

use yii\helpers\Html;

/* @var $this yii\web\View */

$this->params['restaurant_uuid'] = $model->restaurant->restaurant_uuid;

$this->title = 'Create Order Item';
$this->params['breadcrumbs'][] = ['label' => 'Order #' . $model->order_uuid, 'url' => ['order/view','id' => $model->order_uuid, 'storeUuid' =>$model->restaurant->restaurant_uuid ]];
$this->params['breadcrumbs'][] = ['label' => 'Update', 'url' => ['order/update','id' => $model->order_uuid, 'storeUuid' =>$model->restaurant->restaurant_uuid ]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-item-create">

    <?= $this->render('_form', [
        'model' => $model,
        'restaurantsItems' => $restaurantsItems
    ]) ?>

</div>
