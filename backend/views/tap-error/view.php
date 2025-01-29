<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\TapError */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tap Errors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="tap-error-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->tap_error_uuid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->tap_error_uuid], [
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
            'tap_error_uuid',
            'restaurant_uuid',
            [
                'attribute' => 'store_name',
                'value' => function($data) {
                    return $data->restaurant->name;
                }
            ],
            'title',
            'message',
            'text:ntext',
            'issue_logged',
            [
                'attribute' => 'status',
                'value' => function($data) {
                    return \common\models\TapError::getStatusArray()[$data->status];
                }
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
