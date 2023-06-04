<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Payments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'order_uuid',
            [
              'attribute' => 'store_name',
              'value' =>     'restaurant.name'
            ],
            //[
            //  'attribute' => 'customer_name',
            //  'value' =>     'customer.customer_name'
            //],
            'payment_current_status:ntext',
            // 'payment_gateway_invoice_id',
            'payment_gateway_transaction_id',
            //'payment_gateway_payment_id',
            //'payment_gateway_invoice_id',
            'payment_mode',
            //'payment_amount_charged',
            //'payment_net_amount',
            //'payment_gateway_fee',
            //'plugn_fee',
            //'payment_udf1',
            //'payment_udf2',
            //'payment_udf3',
            //'payment_udf4',
            //'payment_udf5',
            'payment_created_at',
            //'payment_updated_at',
            'received_callback:boolean',
            //'response_message',
            'is_sandbox:boolean',
            //'payment_token',
            //'payment_gateway_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
