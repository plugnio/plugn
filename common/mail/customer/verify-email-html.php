<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $customer common\models\Customer */
/* @var $email String */

//$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $customer->customer_auth_key]);

$mobileUrl = 'plugn-dashboard://verify-email/' . urlencode($email) . '/' . $customer->customer_auth_key;

?>
<div class="verify-email">
    <p>Hello <?= Html::encode($customer->customer_name) ?>,</p>

    <p>Follow the link below to verify your email:</p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
</div>

