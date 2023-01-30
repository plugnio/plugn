<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\VendorCampaign */

$this->title = Yii::t('app', 'Update Vendor Campaign: {name}', [
    'name' => $model->campaign_uuid,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vendor Campaigns'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->campaign_uuid, 'url' => ['view', 'id' => $model->campaign_uuid]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="vendor-campaign-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
