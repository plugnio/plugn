<?php

use yii\helpers\Html;
use common\models\Order;
/* @var $errorMessage string */
/* @var $this yii\web\View */
/* @var $refund common\models\Refund */
?>

<div class="verify-form">
    <h1>Hello,</h1>


    <p class="lead">
        Refund for Order #<?= $refund->order_uuid ?> was failed with the following error
        <br/>
        <br/>

        <?= $errorMessage ?>
        <br/>
        <br/>

        Please check this issue with the customer.
        <br/>
        <br/>
        <br/>
        Best Regards,
        <br/>
        Plugn Team

    </p>


</div>
