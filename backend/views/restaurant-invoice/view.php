<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\RestaurantInvoice;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantInvoice */

$this->title = $model->invoice_uuid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Restaurant Invoices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="restaurant-invoice-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <!--
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->invoice_uuid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->invoice_uuid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>-->

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'invoice_uuid',
            'invoice_number',
            'restaurant.name',
            'payment_uuid',
            'amount',
            'currency_code',
            [

                'attribute' => 'invoice_status',
                'filter' => [
                    RestaurantInvoice::STATUS_LOCKED => 'Pending',
                    RestaurantInvoice::STATUS_UNPAID => 'Draft',
                    RestaurantInvoice::STATUS_PAID => 'Paid',
                ],
                'value' => function($model) {
                    if($model->invoice_status == RestaurantInvoice::STATUS_LOCKED)
                        return 'Pending';
                    else if($model->invoice_status == RestaurantInvoice::STATUS_UNPAID)
                        return 'Draft';
                    else if($model->invoice_status == RestaurantInvoice::STATUS_PAID)
                        return 'Paid';
                    return $model->amount . ' ' . $model->currency_code;
                },
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>
    <br />

    <h3>Invoice Items</h3>

    <?= \yii\grid\GridView::widget([
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => $model->getInvoiceItems(),
        ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'order_uuid',
            'plan_id',
            'addon_uuid',
            "domain_subcription_uuid",
            'comment',
            [
                'attribute' => 'total',
                'value' => function($data) use($model) {
                    return $data->total . ' ' . $model->currency_code;
                }
            ]
        ],
    ]); ?>
    <br />

    <h3>Invoice Payments</h3>

    <?= \yii\grid\GridView::widget([
        'dataProvider' => new \yii\data\ActiveDataProvider([
                'query' => $model->getPayments()
        ]),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'payment_gateway_transaction_id',
            'payment_mode',
            'payment_current_status',
            //'received_callback',
            'payment_created_at'
        ],
    ]); ?>




</div>
