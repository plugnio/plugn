<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Refund */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = $model->refund_id;
$this->params['breadcrumbs'][] = ['label' => 'Refunds', 'url' => ['index','storeUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="refund-view">

    <div class="card">
        <div class="card-body">
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'refund_id',
            'restaurant_uuid',
            'order_uuid',
            'refund_amount',
            'reason',
            'refund_message'
            ],
            'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
            ])
            ?>

        </div>
    </div>

</div>
