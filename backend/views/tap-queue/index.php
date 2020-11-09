<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tap Queues';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tap-queue-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Tap Queue', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'tap_queue_id',
            'restaurant_uuid',
            'queue_status',
            'queue_created_at',
            'queue_updated_at',
            //'queue_start_at',
            //'queue_end_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
