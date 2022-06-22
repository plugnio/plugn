<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $agent common\models\Agent */
/* @var $email String */

//$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify-email', 'token' => $agent->agent_auth_key]);

$verifyLink = Yii::$app->params['newDashboardAppUrl'] . '/verify-email/' . urlencode($email) . '/' . $agent->agent_auth_key;

$mobileUrl = 'plugn-dashboard://verify-email/' . urlencode($email) . '/' . $agent->agent_auth_key;

?>
<div class="verify-email">
    <p>Hello <?= Html::encode($agent->agent_name) ?>,</p>

    <p>Follow the link below to verify your email:</p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>
</div>

