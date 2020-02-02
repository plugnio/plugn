<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Order Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-item-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Order Item', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'order_item_id',
            'order_id',
            'item_uuid',
            'item_name',
            'item_price',
            //'qty',
            //'instructions',
            //'order_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
