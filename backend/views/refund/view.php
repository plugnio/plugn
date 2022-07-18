<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Refund */

$this->title = $model->refund_id;
$this->params['breadcrumbs'][] = ['label' => 'Refunds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<div class="refund-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->refund_id], ['class' => 'btn btn-primary btn-update']) ?>
        <?php
        if ( $model->refund_status != "REFUNDED" && $model->refund_status != "REJECTED") {
            echo Html::a('Refund', ['make-refund', 'id' => $model->refund_id], ['class' => 'btn btn-success btn-update']);
        }

        ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->refund_id], [
            'class' => 'btn btn-danger btn-delete',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'refund_id',
            'payment_uuid',
            'order_uuid',
            [
                'label' => 'Store Name',
                'format' => 'raw',
                'value' => function ($data) {
                    return $data->store->name ;
                }
            ],
            [
                'label' => 'Refund Amount',
                'format' => 'raw',
                'value' => function ($data) {
                    return Yii::$app->formatter->asCurrency($data->refund_amount, $data->currency->code, [
                        \NumberFormatter::MIN_FRACTION_DIGITS => $data->currency->decimal_place,
                        \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                    ]);
                }
            ],
            'reason',
            'refund_status',
            'refund_created_at',
            'refund_updated_at',
            'refund_reference',
            'refund_message'
        ],
    ]) ?>

    <hr/>
    <h1>Payment Detail</h1>

    <?php
    if ($model->payment) {
        echo DetailView::widget([
            'model' => $model->payment,
            'attributes' => [
                'payment_uuid',
                'restaurant_uuid',
                [
                    'label' => 'Store Name',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->restaurant->name;
                    }
                ],
                [
                    'label' => 'Customer Name',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return $data->customer->customer_name;
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
                        return Yii::$app->formatter->asCurrency($data->payment_amount_charged, $data->currency->code, [
                            \NumberFormatter::MIN_FRACTION_DIGITS => $data->currency->decimal_place,
                            \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                        ]);
                    }
                ],
                [
                    'attribute' => 'payment_gateway_fee',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return Yii::$app->formatter->asCurrency($data->payment_gateway_fee, $data->currency->code, [
                            \NumberFormatter::MIN_FRACTION_DIGITS => $data->currency->decimal_place,
                            \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                        ]);
                    }
                ],
                [
                    'attribute' => 'plugn_fee',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return Yii::$app->formatter->asCurrency($data->plugn_fee, $data->currency->code, [
                            \NumberFormatter::MIN_FRACTION_DIGITS => $data->currency->decimal_place,
                            \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                        ]);
                    }
                ],
                [
                    'attribute' => 'partner_fee',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return Yii::$app->formatter->asCurrency($data->partner_fee, $data->currency->code, [
                            \NumberFormatter::MIN_FRACTION_DIGITS => $data->currency->decimal_place,
                            \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                        ]);
                    }
                ],
                [
                    'attribute' => 'payment_net_amount',
                    'format' => 'raw',
                    'value' => function ($data) {
                        return Yii::$app->formatter->asCurrency($data->payment_net_amount, $data->currency->code, [
                            \NumberFormatter::MIN_FRACTION_DIGITS => $data->currency->decimal_place,
                            \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                        ]);
                    }
                ],
                'payment_created_at',
                'payment_updated_at',
                'received_callback',
                'response_message',
                'payment_token',
                'payment_gateway_name',
            ],
        ]);
    }
    ?>
</div>
