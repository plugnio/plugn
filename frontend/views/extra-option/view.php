<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\ExtraOption */

$this->title = $model->extra_option_name;
$this->params['breadcrumbs'][] = ['label' => $model->option->item->item_name, 'url' => ['item/view', 'id' => $model->option->item->item_uuid]];
$this->params['breadcrumbs'][] = ['label' => $model->option->option_name, 'url' => ['option/view', 'id' => $model->option->option_id]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="extra-option-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->extra_option_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->extra_option_id], [
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
            'extra_option_id',
            'option_id',
            'extra_option_name',
            'extra_option_name_ar',
            'price',
        ],
    ]) ?>

</div>
