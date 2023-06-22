<?php

/* @var $this yii\web\View */
/* @var $customer common\models\Customer */

//$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $customer->customer_auth_key]);

$verifyLink = Yii::$app->params['newDashboardAppUrl'] . '/verify-email/' . urlencode($email) . '/' . $customer->customer_auth_key;

?>
Hello <?= $customer->customer_name ?>,

Follow the link below to verify your email:

<?= $verifyLink ?>
