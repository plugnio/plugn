<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\OrderItemExtraOptions */

$this->title = $model->orderItem->item_name;
$this->params['breadcrumbs'][] = ['label' => $model->order->area_name, 'url' => ['order/view', 'id' => $model->order->order_uuid]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="order-item-extra-options-view">
    <p>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->order_item_extra_option_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>
    <div class="card">
        <div class="card-body">
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'order.order_uuid',
                    'orderItem.item_name',
                    'orderItem.item_price',
                    'orderItem.customer_instruction',
                    'extra_option_name',
                    'extra_option_price:currency',
                ],
                'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
            ])
            ?>

        </div>
    </div>
