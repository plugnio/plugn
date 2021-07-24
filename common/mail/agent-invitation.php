<?php

use yii\helpers\Html;
use common\models\AgentAssignment;
/* @var $this yii\web\View */
/* @var $model common\models\AgentAssignment */
if ($model->role == AgentAssignment::AGENT_ROLE_OWNER) {
    $role = 'Owner';
} else if ($model->role == AgentAssignment::AGENT_ROLE_STAFF) {
    $role = 'Staff';
} else {
    $role = 'Branch Manager';
}
?>
<div class="verify-form">
    <h1>Dear <?=$model->agent->agent_name?>,</h1>


    <h2>You have been invited to join <?=$model->restaurant->name?> as <?=$role?> at <?=$model->restaurant->restaurant_domain?>.</h2><br/>
    <p>Please find below detail to login into system</p>

    <p class="lead">
        Email ID: <?= $model->agent->agent_email ?> <br/>
        Password: <?= $password; ?> <br/>
    </p>
</div>

<br/>
Regards<br/>
<?=$model->restaurant->name?><br/>
<?=$model->restaurant->restaurant_domain?>

