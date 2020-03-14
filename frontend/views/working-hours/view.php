<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\WorkingHours */

$this->title = $model->working_day_id;
$this->params['breadcrumbs'][] = ['label' => 'Working Hours', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="working-hours-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'working_day_id' => $model->working_day_id, 'restaurant_uuid' => $model->restaurant_uuid], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'working_day_id' => $model->working_day_id, 'restaurant_uuid' => $model->restaurant_uuid], [
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
            'working_day_id',
            'restaurant_uuid',
            'operating_from',
            'operating_to',
        ],
    ]) ?>

</div>
