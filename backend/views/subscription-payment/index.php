<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SubscriptionPaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Subscription Payments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="subscription-payment-index">

    <h1><?= Html::encode($this->title) ?></h1>


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
            'payment_current_status:ntext',
            'payment_uuid',
            'subscription_uuid',
            //'payment_mode',
            //'payment_current_status:ntext',
            //'payment_amount_charged',
            //'payment_net_amount',
            //'payment_gateway_fee',
            //'payment_udf1',
            //'payment_udf2',
            //'payment_udf3',
            //'payment_udf4',
            //'payment_udf5',
            //'payment_created_at',
            //'payment_updated_at',
            //'received_callback',
            //'response_message',
            //'payment_token',
            //'partner_fee',
            //'payout_status',
            //'partner_payout_uuid',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
