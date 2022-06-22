<?php

/* @var $this yii\web\View */
/* @var $agent common\models\User */

//$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $agent->agent_auth_key]);

$verifyLink = Yii::$app->params['dashboardAppUrl'] . '/verify-email/' . urlencode($email) . '/' . $agent->agent_auth_key;

?>
Hello <?= $agent->agent_name ?>,

Follow the link below to verify your email:

<?= $verifyLink ?>
