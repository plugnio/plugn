<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\WorkingDaySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Working Days';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="working-day-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Working Day', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'working_day_id',
            'name',
            'name_ar',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
