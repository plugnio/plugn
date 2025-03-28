<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\StoreDomainSubscription $model */

$this->title = Yii::t('app', 'Update Store Domain Subscription: {name}', [
    'name' => $model->subscription_uuid,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Store Domain Subscriptions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->subscription_uuid, 'url' => ['view', 'subscription_uuid' => $model->subscription_uuid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="store-domain-subscription-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
