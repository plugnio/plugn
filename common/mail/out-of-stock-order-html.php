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

    <div style="margin-left: auto; margin-right: auto; display:block;">
<a href="<?= Yii::$app->request->baseUrl . '/order/view?id=' . $order->order_uuid . '&restaurantUuid=' . $order->restaurant_uuid ?>" style="display:inline-block;background:#ffffff;color:#000000;font-family:Proxima Nova, Arial, Arial, Helvetica, sans-serif;font-size:15px;font-weight:bold;line-height:120%;margin:0;text-decoration:none;text-transform:none;padding:10px 25px;mso-padding-alt:0px;border-radius:5px;" target="_blank">View order</a
    </div>

</div>
