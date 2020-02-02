<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\OrderItemExtraOptionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Order Item Extra Options';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-item-extra-options-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Order Item Extra Options', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'order_item_extra_options_id',
            'order_item_id',
            'extra_option_id',
            'extra_option_name',
            'extra_option_name_ar',
            //'extra_option_price',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
