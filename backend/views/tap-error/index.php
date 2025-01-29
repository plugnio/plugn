<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TapErrorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Tap Errors');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tap-error-index">

    <h1><?= Html::encode($this->title) ?></h1>
<!--
    <p>
        <?= Html::a(Yii::t('app', 'Create Tap Error'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>-->

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'tap_error_uuid',
            [
                'attribute' => 'store_name',
                'value' =>     'restaurant.name'
            ],
            'title',
            'message',
            //'text:ntext',
            'issue_logged',
            [
                    "filter" => \common\models\TapError::getStatusArray(),
                    'attribute' => 'status',
                    'value' => function($data) {
                        return \common\models\TapError::getStatusArray()[$data->status];
                    }
            ],
            //'status',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
