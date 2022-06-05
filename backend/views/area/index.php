<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\AreaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Areas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="area-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Area', ['create'], ['class' => 'btn btn-success btn-create']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
              'attribute' => 'country_name',
              'value' =>     'country.country_name'
            ],
            [
              'attribute' => 'city_name',
              'value' =>     'city.city_name'
            ],
            'area_name',
            'area_name_ar',
            //'latitude',
            //'longitude',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
