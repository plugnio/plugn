<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Restaurants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="restaurant-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->restaurant_uuid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->restaurant_uuid], [
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
            'restaurant_uuid',
            'vendor_id',
            'name',
            'name_ar',
            'tagline',
            'tagline_ar',
            'status',
            'thumbnail_image',
            'logo',
            'support_delivery',
            'support_pick_up',
            'min_delivery_time',
            'min_pickup_time',
            'operating_from',
            'operating_to',
            'delivery_fee',
            'min_charge',
            'location',
            'location_ar',
            'location_latitude',
            'location_longitude',
            'phone_number',
            'restaurant_created_at',
            'restaurant_updated_at',
        ],
    ]) ?>

</div>
