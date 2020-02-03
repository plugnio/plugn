<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'order_id',
//            'area_id',
            'area_name',
//            'area_name_ar',
//            'unit_type',
            //'block',
            //'street',
            //'avenue',
            //'house_number',
            //'special_directions',
            'customer_name',
            'customer_phone_number',
            //'customer_email:email',
            //'payment_method_id',
            //'payment_method_name',
            //'order_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
