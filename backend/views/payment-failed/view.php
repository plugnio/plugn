<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\PaymentFailed */

$this->title = $model->payment_failed_uuid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payment Faileds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

function isSerialized($data) {
    $unserializedData = @unserialize($data);
    return ($unserializedData !== false || $data === 'b:0;');
}

?>
<div class="payment-failed-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->payment_failed_uuid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->payment_failed_uuid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'payment_failed_uuid',
            'payment_uuid',
            'order_uuid',
            'customer_id',
            [
                'attribute' => 'response',
                "format" => "raw",
                'value' => function ($model) {
                    if (isSerialized($model->response)) {
                        return unserialize($model->response);
                    } else {
                        return $model->response;
                    }
                }
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
