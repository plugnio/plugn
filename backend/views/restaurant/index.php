<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RestaurantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Restaurants';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="restaurant-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Restaurant', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],
            [
              'attribute' => 'country_name',
              'value' =>     'country.country_name'
            ],
            // [
            //   'attribute' => 'currency_title',
            //   'value' =>     'currency.title'
            // ],
            'restaurant_uuid',

            'name',
            'restaurant_domain',
            // 'store_branch_name',
            'restaurant_created_at:datetime',
            'platform_fee:percent',
            [
              'attribute' => 'currency_title',
              'value' =>     'currency.title'
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
