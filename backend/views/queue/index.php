<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\QueueSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Queues';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="queue-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Queue', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'restaurant_uuid',
            [
              'attribute' => 'store_name',
              'value' =>     'restaurant.name'
            ],
            'queue_status',
            'queue_created_at',
            'queue_updated_at',
            //'queue_start_at',
            //'queue_end_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
