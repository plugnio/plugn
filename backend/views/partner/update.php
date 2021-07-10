<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Partner */

$this->title = 'Update Partner: ' . $model->partner_uuid;
$this->params['breadcrumbs'][] = ['label' => 'Partners', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->partner_uuid, 'url' => ['view', 'partner_uuid' => $model->partner_uuid, 'referral_code' => $model->referral_code]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="partner-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
