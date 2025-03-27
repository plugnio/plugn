<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\StoreDomainSubscriptionPayment $model */

$this->title = Yii::t('app', 'Create Store Domain Subscription Payment');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Store Domain Subscription Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-domain-subscription-payment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
