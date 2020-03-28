<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $order common\models\ORder */

?>
<div class="order-confirmation">
    <p>Order received from <?= Html::encode($order->customer_name) ?>,</p>
</div>
    