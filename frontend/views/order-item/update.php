<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OrderItem */
$this->params['restaurant_uuid'] = $model->restaurant->restaurant_uuid;

$this->title = 'Update Order Item: ' . $model->item_name;
$this->params['breadcrumbs'][] = ['label' => 'Order #' . $model->order_uuid, 'url' => ['order/update','id' => $model->order_uuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="order-item-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
