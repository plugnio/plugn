<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\VendorCampaign */

$this->title = Yii::t('app', 'Create Vendor Campaign');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vendor Campaigns'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vendor-campaign-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
