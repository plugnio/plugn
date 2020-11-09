<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $order common\models\Order */
?>
<div class="verify-form">
    <h1>Dear <?= $agent_name ?>,</h1>

    <p class="lead">
      We would like to remind you that Order #<?= $order->order_uuid ?> has been placed 5 minutes ago for total amount of <?= \Yii::$app->formatter->asCurrency($order->total_price, $order->currency->code) ?>
          <br/>
          <br/>
          <br/>
            Best Regards,
                <br/>
            Plugn Team
    </p>
</div>
