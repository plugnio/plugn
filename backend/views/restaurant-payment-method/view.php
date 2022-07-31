<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantPaymentMethod */

$this->title = $model->restaurant_uuid;
$this->params['breadcrumbs'][] = ['label' => 'Restaurant Payment Methods', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="restaurant-payment-method-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'restaurant_uuid' => $model->restaurant_uuid, 'payment_method_id' => $model->payment_method_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'restaurant_uuid' => $model->restaurant_uuid, 'payment_method_id' => $model->payment_method_id], [
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
            'payment_method_id',
            'status',
        ],
    ]) ?>

</div>
