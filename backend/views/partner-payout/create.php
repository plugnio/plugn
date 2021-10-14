<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PartnerPayout */

$this->title = 'Create Partner Payout';
$this->params['breadcrumbs'][] = ['label' => 'Partner Payouts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partner-payout-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
