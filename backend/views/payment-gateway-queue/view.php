<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PaymentGatewayQueue */

$this->title = $model->payment_gateway_queue_id;
$this->params['breadcrumbs'][] = ['label' => 'Payment Gateway Queues', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="payment-gateway-queue-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->payment_gateway_queue_id], ['class' => 'btn btn-primary btn-update']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->payment_gateway_queue_id], [
            'class' => 'btn btn-danger btn-delete',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php if($model->queue_status != \common\models\PaymentGatewayQueue::QUEUE_STATUS_COMPLETE) { ?>
        <?=
        Html::a('Process payment gateway request', ['restaurant/process-gateway-queue', 'id' => $model->restaurant_uuid], [
            'class' => 'btn btn-danger btn-process-queue',
            'data' => [
                'confirm' => 'Are you sure you want to create payment gateway account for this store?',
                'method' => 'post',
            ],
        ])
        ?>
    <?php } ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'payment_gateway_queue_id',
           // 'restaurant_uuid',
            [
                'attribute' => 'restaurant',
                'format' => 'raw',
                'value' => function ($data) {
                    return Html::a($data->restaurant->name, ['restaurant/view', 'id' => $data->restaurant_uuid],
                        ['target'=>'_blank']);
                },
            ],
            'payment_gateway',
            'queueStatusName',
            'queue_response',
            'queue_created_at',
            'queue_updated_at',
            'queue_start_at',
            'queue_end_at',
        ],
    ]) ?>

</div>
