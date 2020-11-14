<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\DeliveryZone */

$this->title = 'Update Delivery Zone: ' . $model->delivery_zone_id;
$this->params['breadcrumbs'][] = ['label' => 'Delivery Zones', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->delivery_zone_id, 'url' => ['view', 'id' => $model->delivery_zone_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="delivery-zone-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
