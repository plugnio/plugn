<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\StoreDomainSubscriptionPayment $model */

$this->title = $model->store_domain_subscription_payment_uuid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Store Domain Subscription Payments'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="store-domain-subscription-payment-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->store_domain_subscription_payment_uuid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->store_domain_subscription_payment_uuid], [
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
            'store_domain_subscription_payment_uuid',
            'subscription_uuid',
            'from:date',
            'to:date',
            'total_amount',
            'cost_amount',
            [
                "label" => "Created By",
                "value" => function ($model) {
                    return $model->createdBy? $model->createdBy->admin_name: null;
                }
            ],
            [
                "label" => "Updated By",
                "value" => function ($model) {
                    return $model->updatedBy?$model->updatedBy->admin_name: null;
                }
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
