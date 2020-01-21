<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Option */

$this->title = $model->option_name;
$this->params['breadcrumbs'][] = ['label' => $model->item->item_name, 'url' => ['item/view', 'id' => $model->item->item_uuid]];

$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="option-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->option_id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->option_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'option_id',
            'item_uuid',
            'is_required',
            'max_qty',
            'option_name',
            'option_name_ar',
        ],
    ])
    ?>


    <h2>Extra Options</h2>

    <p>
    <?= Html::a('Create Extra option', ['extra-option/create', 'option_id' => $model->option_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $itemExtraOptionsDataProvider,
        'columns' => [
            'extra_option_name',
            'extra_option_name_ar',
            ['class' => 'yii\grid\ActionColumn', 'controller' => 'option'],
        ],
    ]);
    ?>


</div>
