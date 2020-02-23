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
        <?= Html::a('Create Option', ['option/create', 'item_uuid' => $model->item_uuid], ['class' => 'btn btn-success']) ?>
    </p>


    <div class="card">
        <?=
        GridView::widget([
            'dataProvider' => $itemOptionsDataProvider,
            'columns' => [
                'option_name',
                'option_name_ar',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'controller' => 'option',
                    'template' => ' {view} {update} {delete}',
                    'buttons' => [
                        'view' => function ($url) {
                            return Html::a(
                                            '<span style="margin-right: 20px;" class="nav-icon fas fa-eye"></span>', $url, [
                                        'title' => 'View',
                                        'data-pjax' => '0',
                                            ]
                            );
                        },
                        'update' => function ($url) {
                            return Html::a(
                                            '<span style="margin-right: 20px;" class="nav-icon fas fa-edit"></span>', $url, [
                                        'title' => 'Update',
                                        'data-pjax' => '0',
                                            ]
                            );
                        },
                        'delete' => function ($url) {
                            return Html::a(
                                            '<span style="margin-right: 20px;" class="nav-icon fas fa-trash"></span>', $url, [
                                        'title' => 'Delete',
                                        'data' => [
                                            'confirm' => 'Are you absolutely sure ? You will lose all the information about this option with this action.',
                                            'method' => 'post',
                                        ],
                            ]);
                        },
                    ],
                ],
            ],
            'layout' => '{summary}<div class="card-body">{items}{pager}</div>',
            'tableOptions' => ['class' => 'table table-bordered table-hover'],
            'summaryOptions' => ['class' => "card-header"],
        ]);
        ?>

    </div>

</div>
