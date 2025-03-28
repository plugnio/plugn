<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\StoreDomainSubscriptionPayment $model */

$this->title = Yii::t('app', 'Update Store Domain Subscription Payment: {name}', [
    'name' => $model->store_domain_subscription_payment_uuid,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Store Domain Subscription Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->store_domain_subscription_payment_uuid, 'url' => ['view', 'id' => $model->store_domain_subscription_payment_uuid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="store-domain-subscription-payment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
