<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\StoreDomainSubscription $model */

$this->title = $model->subscription_uuid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Store Domain Subscriptions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="store-domain-subscription-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
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
                    return $model->createdBy->admin_name;
                }
            ],
            [
                "label" => "Updated By",
                "value" => function ($model) {
                    return $model->updatedBy->admin_name;
                }
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
