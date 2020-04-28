<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Payment */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = $model->payment_uuid;
$this->params['breadcrumbs'][] = ['label' => 'Payments', 'url' => ['index', 'restaurantUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="payment-view">

    <p>
        <?= Html::a('Delete', ['delete', 'id' => $model->payment_uuid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>

        <?= Html::a('Create Refund', ['refund/create', 'restaurantUuid' => $model->restaurant_uuid, 'orderUuid' => $model->order_uuid], ['class' => 'btn btn-success']) ?>

    </p>
    <div class="card">
        <div class="card-body">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'payment_uuid',
            'customer_id',
            'order_uuid',
            'payment_gateway_order_id',
            'payment_gateway_transaction_id',
            'payment_mode',
            'payment_current_status:ntext',
            'payment_amount_charged:currency',
            'payment_net_amount:currency',
            'payment_gateway_fee:currency',
//            'payment_udf1',
//            'payment_udf2',
//            'payment_udf3',
//            'payment_udf4',
//            'payment_udf5',
            'payment_created_at',
            'payment_updated_at',
//            'received_callback',
            'response_message',
        ],
             'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
            ])
            ?>

        </div>
    </div>


</div>
