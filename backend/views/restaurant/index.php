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
        <?php

        // echo Html::a('Create Restaurant', ['create'], ['class' => 'btn btn-success']);
         ?>
    </p>

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            // ['class' => 'yii\grid\SerialColumn'],

            'name',
            'restaurant_domain',
            [
              'attribute' => 'country_name',
              'value' =>     'country.country_name'
            ],
            [
              'label' => 'Currency',
              'attribute' => 'currency_title',
              'value' =>     'currency.title'
            ],
            'platform_fee:percent',
            'warehouse_fee',
            'warehouse_delivery_charges',
            'version',

            'restaurant_created_at:datetime',
            'referral_code',


            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'restaurant',
                'template' => ' {view} {update}'
            ],


            // ['class' => 'yii\grid\ActionColumn','template' => '{view}{update}'],
        ],
    ]); ?>


</div>
