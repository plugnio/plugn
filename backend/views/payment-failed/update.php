<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PaymentFailed */

$this->title = Yii::t('app', 'Update Payment Failed: {name}', [
    'name' => $model->payment_failed_uuid,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payment Faileds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->payment_failed_uuid, 'url' => ['view', 'id' => $model->payment_failed_uuid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="payment-failed-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
