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
        ],
    ]) ?>

</div>
