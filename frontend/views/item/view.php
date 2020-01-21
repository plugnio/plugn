<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Item */

$this->title = $model->item_name;
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="item-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->item_uuid], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->item_uuid], [
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
            'item_uuid',
            'item_name',
            'item_name_ar',
            'item_description',
            'item_description_ar',
            'sort_number',
            'stock_qty',
            'item_image',
            'price',
            'item_created_at',
            'item_updated_at',
        ],
    ])
    ?>

    <h2>Options</h2>

    <p>
        <?= Html::a('Create Option', ['option/create' , 'item_uuid' => $model->item_uuid], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $itemOptionsDataProvider,
        'columns' => [
            'option_name',
            'option_name_ar',
            ['class' => 'yii\grid\ActionColumn', 'controller' => 'option'],
        ],
    ]);
    ?>



</div>
