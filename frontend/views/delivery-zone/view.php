<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\DeliveryZone */

$this->title = $model->delivery_zone_id;
$this->params['breadcrumbs'][] = ['label' => 'Delivery Zones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="delivery-zone-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->delivery_zone_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->delivery_zone_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'delivery_zone_id',
            'business_location_id',
            'business_location_name',
            'business_location_name_ar',
            'support_delivery',
            'support_pick_up',
            'delivery_time:datetime',
            'delivery_fee',
            'min_charge',
        ],
    ]) ?>

</div>
