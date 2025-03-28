<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\ActionColumn;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\StoreDomainSubscription $model */
/** @var common\models\StoreDomainSubscriptionPaymentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = $model->subscription_uuid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Store Domain Subscriptions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
?>
<div class="store-domain-subscription-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Generate Invoice'), ['generate-invoice', 'id' => $model->subscription_uuid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'subscription_uuid' => $model->subscription_uuid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'subscription_uuid' => $model->subscription_uuid], [
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
            'subscription_uuid',
            'restaurant_uuid',
            'restaurantName',
            'domain_registrar',
            'domain:url',
            'from:date',
            'to:date',
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

    <h5>Payments</h5>

    <?= \yii\grid\GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'from:date',
            'to:date',
            "total_amount",
            "cost_amount",
            //'created_by',
            //'updated_by',
            'created_at:datetime',
            'updated_at:datetime',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, \common\models\StoreDomainSubscriptionPayment $model, $key, $index, $column) {
                    return Url::toRoute(["store-domain-subscription-payment/" .$action, 'id' => $model->store_domain_subscription_payment_uuid]);
                }
            ],
        ],
    ]); ?>

</div>
