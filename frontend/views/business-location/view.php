<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessLocation */

$this->title = $model->business_location_name;
$this->params['restaurant_uuid'] = $restaurantUuid;

$this->params['breadcrumbs'][] = ['label' => 'Business Locations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="business-location-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->business_location_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->business_location_id], [
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
            'business_location_id',
            'restaurant_uuid',
            'business_location_name',
            'business_location_name_ar',
            [
                'label' => 'Support Delivery',
                'value' => function ($data) {
                    return $data->support_delivery ? 'Yes' : 'No';
                },
                'format' => 'raw'
            ],

            [
                'label' => 'Support Pick up',
                'value' => function ($data) {
                    return $data->support_pick_up ? 'Yes' : 'No';
                },
                'format' => 'raw'
            ],

        ],
    ]) ?>

</div>
