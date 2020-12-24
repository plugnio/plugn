<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\OrderItemExtraOptions */

$this->params['restaurant_uuid'] = $model->restaurant->restaurant_uuid;

$this->title = 'Create Extra Options';
$this->params['breadcrumbs'][] = ['label' => 'Order #' . $model->order->order_uuid, 'url' => ['order/update','id' => $model->order->order_uuid, 'storeUuid' =>$model->restaurant->restaurant_uuid ]];
$this->params['breadcrumbs'][] = ['label' => $model->orderItem->item_name , 'url' => ['order-item/view' , 'id' => $model->order_item_id, 'storeUuid' =>$model->restaurant->restaurant_uuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-item-extra-options-create">


    <?= $this->render('_form', [
        'model' => $model,
        'extraOptionsQuery' => $extraOptionsQuery
    ]) ?>

</div>
