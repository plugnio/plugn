<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantDomainRequest */

$this->title = $model->request_uuid;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Restaurant Domain Requests'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="restaurant-domain-request-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->request_uuid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->request_uuid], [
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
            'request_uuid',
            'storeName',
            'domain',
            [
                    'attribute' => 'status',
                'value' => function($data) {
                    return \common\models\RestaurantDomainRequest::arrStatus()[$data->status];
                }
            ],
            'created_by',
            'expire_at:date',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
