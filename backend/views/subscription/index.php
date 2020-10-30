<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SubscriptionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Subscriptions';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscription-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Subscription', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
              'attribute' => 'restaurant_name',
              'value' => 'restaurant.name'
            ],
            'restaurant.platform_fee:percent',  
            [
              'attribute' => 'plan_name',
              'value' =>     'plan.name'
            ],
            'status',
            'subscription_start_at',
            'subscription_end_at',

            [
              'class' => 'yii\grid\ActionColumn'
           ],
        ],
    ]); ?>


</div>
