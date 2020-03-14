<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\WorkingHoursSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Working Hours';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="working-hours-index">

    <p>
        <?= Html::a('Create Working Hours', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'working_day.name',
            'operating_from',
            'operating_to',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
