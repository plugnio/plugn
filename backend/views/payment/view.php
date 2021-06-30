<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Payment */

$this->title = $model->payment_uuid;
$this->params['breadcrumbs'][] = ['label' => 'Payments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="payment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->payment_uuid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->payment_uuid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'payment_uuid',
            'restaurant_uuid',
            [
                'label' => 'Store Name',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->restaurant->name ;
                }
            ],
            [
                'label' => 'Customer Name',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->customer->customer_name ;
                }
            ],
            'order_uuid',
            'payment_gateway_order_id',
            'payment_gateway_transaction_id',
            'payment_gateway_payment_id',
            'payment_gateway_invoice_id',
            'payment_mode',
            'payment_current_status:ntext',
            [
                'attribute' => 'payment_amount_charged',
                'format' => 'raw',
                'value' => function ($data) {
                    return Yii::$app->formatter->asCurrency($data->payment_amount_charged, $data->currency->code,[ \NumberFormatter::MIN_FRACTION_DIGITS => 0, \NumberFormatter::MAX_FRACTION_DIGITS => 5 ]);
                }
            ],
            [
                'attribute' => 'payment_gateway_fee',
                'format' => 'raw',
                'value' => function ($data) {
                    return Yii::$app->formatter->asCurrency($data->payment_gateway_fee, $data->currency->code,[ \NumberFormatter::MIN_FRACTION_DIGITS => 0, \NumberFormatter::MAX_FRACTION_DIGITS => 5 ]);
                }
            ],
            [
                'attribute' => 'plugn_fee',
                'format' => 'raw',
                'value' => function ($data) {
                    return Yii::$app->formatter->asCurrency($data->plugn_fee, $data->currency->code,[ \NumberFormatter::MIN_FRACTION_DIGITS => 0, \NumberFormatter::MAX_FRACTION_DIGITS => 5 ]);
                }
            ],
            [
                'attribute' => 'payment_net_amount',
                'format' => 'raw',
                'value' => function ($data) {
                    return Yii::$app->formatter->asCurrency($data->payment_net_amount, $data->currency->code,[ \NumberFormatter::MIN_FRACTION_DIGITS => 0, \NumberFormatter::MAX_FRACTION_DIGITS => 5 ]);
                }
            ],
            'payment_created_at',
            'payment_updated_at',
            'received_callback',
            'response_message',
            'payment_token',
            'payment_gateway_name',
        ],
    ]) ?>

</div>
