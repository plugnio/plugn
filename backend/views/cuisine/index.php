<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CuisineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cuisines';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cuisine-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Cuisine', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'cuisine_id',
            'cuisine_name',
            'cuisine_name_ar',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
