<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\User */

?>

<div class="password-updated">
    <p>Hello <?= Html::encode($user->username) ?>,</p>

    <p>Your password has been updated successfully</p>

    <p>Thank you</p>
</div>
