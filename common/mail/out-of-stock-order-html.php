<?php

use yii\helpers\Html;
use common\models\Order;

/* @var $this yii\web\View */
/* @var $order common\models\Order */
?>

<div class="verify-form">
    <h1>Hello <?= $order->restaurant->name ?>,</h1>


    <p class="lead">
      An issue occurred, in which you received a payment from one of your customers (<?=  $order->customer_name ?>) while the item was out of stock. Please check this issue with the customer to see if they require a refund.
    </p>


</div>
