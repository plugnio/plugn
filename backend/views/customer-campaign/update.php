<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\CustomerCampaign */

$this->title = Yii::t('app', 'Update Customer Campaign: {name}', [
    'name' => $model->campaign_uuid,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customer Campaigns'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->campaign_uuid, 'url' => ['view', 'id' => $model->campaign_uuid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="customer-campaign-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
