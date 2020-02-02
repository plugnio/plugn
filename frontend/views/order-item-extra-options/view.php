<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\OrderItemExtraOptions */

$this->title = $model->order_item_extra_options_id;
$this->params['breadcrumbs'][] = ['label' => 'Order Item Extra Options', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="order-item-extra-options-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->order_item_extra_options_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->order_item_extra_options_id], [
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
            'order_item_extra_options_id',
            'order_item_id',
            'extra_option_id',
            'extra_option_name',
            'extra_option_name_ar',
            'extra_option_price',
        ],
    ]) ?>

</div>
