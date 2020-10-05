<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */
?>
<div class="verify-form">
    <h4>Dear <?= $agent_name ?>,</4>


    <p class="lead">
      We would like to remind you that Order #<?= $order->order_uuid ?> has been placed 10 minutes ago for total amount of <?= \Yii::$app->formatter->asCurrency($order->total_price) ?>.
    </p>


    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
      Best Regards,
      Plugn Team

</div>
