<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\SubscriptionPayment */

$this->title = 'Create Subscription Payment';
$this->params['breadcrumbs'][] = ['label' => 'Subscription Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscription-payment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
