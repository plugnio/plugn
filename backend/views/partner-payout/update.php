<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PartnerPayout */

$this->title = 'Update Partner Payout: ' . $model->partner_payout_uuid;
$this->params['breadcrumbs'][] = ['label' => 'Partner Payouts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->partner_payout_uuid, 'url' => ['view', 'id' => $model->partner_payout_uuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="partner-payout-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
