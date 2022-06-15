<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Subscription */

$this->title = $model->restaurant->name . "'s Subscription";
$this->params['breadcrumbs'][] = ['label' => 'Subscriptions', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="subscription-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Delete', ['delete', 'id' => $model->subscription_uuid], [
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
            [
                'label' => 'Store name',
                'value' => function ($data) {
                    return $data->restaurant->name;
                },
            ],
            [
                'label' => 'Plan',
                'value' => function ($data) {
                    return $data->plan->name;
                },
            ],
            'status',
            'notified_email',
            'subscription_start_at',
            'subscription_end_at',
        ],
    ]) ?>

</div>
