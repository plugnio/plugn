<?php

use yii\db\Expression;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PaymentGatewayQueueSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payment Gateway Queues';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-gateway-queue-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Payment Gateway Queue', ['create'], ['class' => 'btn btn-success btn-create']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
              'attribute' => 'store_name',
              'value' =>     'restaurant.name'
            ],
            [
                'attribute' => 'storeStatus',
                'filter' => [
                    1 => "Active",
                    2 => "In-Active"
                ],
              'value' =>  function($model) {
                   if ($model->restaurant->last_order_at && $model->getItems()->count() > 0) {
                     return "Active";
                   }  else {
                       return "In-Active";
                   }
              }
            ],
            'payment_gateway',
            [
                'attribute' => 'queue_status',
                'filter' => \common\models\PaymentGatewayQueue::arrStatusName(),
                'value' => 'queueStatusName'
            ],
            //'queue_response',
            'queue_created_at',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
