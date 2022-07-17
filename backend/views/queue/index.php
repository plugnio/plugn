<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Queue;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\QueueSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Queues';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="queue-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Queue', ['create'], ['class' => 'btn btn-success btn-create']) ?>
        <?= Html::a('Reset Filter', ['queue/index'], ['class' => 'btn btn-success btn-create']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions'=>function($model){
            if ($model->queue_status  == Queue::QUEUE_STATUS_PENDING) {
                return ['class' => 'danger'];
            }
            if ($model->queue_status  == Queue::QUEUE_STATUS_HOLD) {
                return ['style' => 'background:orange'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'restaurant_uuid',
            [
              'attribute' => 'store_name',
              'value' =>     'restaurant.name'
            ],
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
                        return 'Hold';
                    }
                },
                'filter' => [Queue::QUEUE_STATUS_PENDING=>'Pending',Queue::QUEUE_STATUS_CREATING=>'Creating',Queue::QUEUE_STATUS_COMPLETE=>'Published',Queue::QUEUE_STATUS_HOLD=>'Hold'],
                'filterInputOptions' => ['class' => 'form-control', 'id' => null, 'prompt' => 'All']
            ],
            'queue_created_at',
            'queue_updated_at',
            //'queue_start_at',
            //'queue_end_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
