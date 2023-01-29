<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Queue;
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
        <?php
            if ($model->queue_status == 1 || $model->queue_status == 4) {
                echo Html::a('Publish Store',
                    ['publish-store', 'id' => $model->restaurant_uuid],
                    [
                        'class' => 'btn btn-success btn-update',
                        'data' => [
                            'confirm' => 'Are you sure you want to publish this store?',
                            'method' => 'post',
                        ],
                    ]
                );
            }
        ?>
        <?php
        if ($model->queue_status == 1) {
            echo Html::a('Delete', ['delete', 'id' => $model->queue_id], [
                'class' => 'btn btn-danger btn-delete',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this store?',
                    'method' => 'post',
                ],
            ]);

            echo "&nbsp;".Html::a('Put Hold', ['status-hold', 'id' => $model->queue_id], [
                'class' => 'btn btn-danger btn-delete',
                'data' => [
                    'confirm' => 'Are you sure you want to put on hold this store?',
                    'method' => 'post',
                ],
            ]);
        }
        ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'queue_id',
            'restaurant_uuid',
            'restaurant.name',
            [
                'attribute' => 'queue_status',
                'value' =>     function($data) {
                    if ($data->queue_status  == Queue::QUEUE_STATUS_PENDING) {
                        return 'Pending';
                    } else if ($data->queue_status  == Queue::QUEUE_STATUS_CREATING) {
                        return 'Created';
                    } else if ($data->queue_status  == Queue::QUEUE_STATUS_COMPLETE) {
                        return 'Published';
                    } else if ($data->queue_status  == Queue::QUEUE_STATUS_HOLD) {
                        return 'On Hold';
                    }else if ($data->queue_status  == Queue::QUEUE_STATUS_FAILED) {
                        return 'Failed';
                    }
                }
            ],
            'queue_response',
            'queue_created_at',
            'queue_updated_at',
            'queue_start_at',
            'queue_end_at',
        ],
    ]) ?>

</div>
