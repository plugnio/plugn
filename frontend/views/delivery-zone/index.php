<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\DeliveryZoneSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Delivery Zones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="delivery-zone-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Delivery Zone', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'delivery_zone_id',
            'business_location_id',
            'business_location_name',
            'business_location_name_ar',
            'support_delivery',
            //'support_pick_up',
            //'delivery_time:datetime',
            //'delivery_fee',
            //'min_charge',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
