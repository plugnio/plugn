<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Queue */

$this->title = $model->queue_id;
$this->params['breadcrumbs'][] = ['label' => 'Queues', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="queue-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->queue_id], ['class' => 'btn btn-primary btn-update']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->queue_id], [
            'class' => 'btn btn-danger btn-delete',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'queue_id',
            'restaurant_uuid',
            'restaurant.name',
            'queue_status',
            'queue_created_at',
            'queue_updated_at',
            'queue_start_at',
            'queue_end_at',
        ],
    ]) ?>

</div>
