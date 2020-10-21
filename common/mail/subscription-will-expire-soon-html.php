<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */
?>
<div class="verify-form">
    <h1>Dear <?= $agent_name ?>,</h1>

    <h4>Subscription Expiring</h4>

    <p class="lead">
      	The following subscription will expire soon:
          <br/>
          <?= $subscription->plan->name ?> - <?= date('d M', $subscription->subscription_end_at) ?>
          <br/>
          To renew or learn more,
          <br/>
          Sincerely,
                <br/>
            The Plugn Team
    </p>
</div>
