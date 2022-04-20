<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SubscriptionPayment */

$this->title = 'Update Subscription Payment: ' . $model->payment_uuid;
$this->params['breadcrumbs'][] = ['label' => 'Subscription Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->payment_uuid, 'url' => ['view', 'id' => $model->payment_uuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="subscription-payment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
