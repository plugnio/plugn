<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PaymentGatewayQueue */

$this->title = 'Update Payment Gateway Queue: ' . $model->payment_gateway_queue_id;
$this->params['breadcrumbs'][] = ['label' => 'Payment Gateway Queues', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->payment_gateway_queue_id, 'url' => ['view', 'id' => $model->payment_gateway_queue_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="payment-gateway-queue-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
