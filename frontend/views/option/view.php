<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Option */
$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = $model->option_name;
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['item/index', 'restaurantUuid' => $restaurantUuid]];
$this->params['breadcrumbs'][] = ['label' => $model->item->item_name, 'url' => ['item/view', 'id' => $model->item->item_uuid, 'restaurantUuid' => $restaurantUuid]];

$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="option-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->option_id, 'restaurantUuid' => $restaurantUuid], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->option_id, 'restaurantUuid' => $restaurantUuid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>

    <div class="card">
        <div class="card-body">
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'min_qty',
                    'max_qty',
                    'option_name',
                    'option_name_ar',
                ],
                'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
            ])
            ?>
        </div>
    </div>

    <h2>Extra Options</h2>

    <p>
        <?= Html::a('Create Extra option', ['extra-option/create', 'option_id' => $model->option_id, 'restaurantUuid' => $restaurantUuid], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="card">
        <?=
        GridView::widget([
            'dataProvider' => $itemExtraOptionsDataProvider,
            'columns' => [
                'extra_option_name',
                'extra_option_name_ar',
                'extra_option_price:currency',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => ' {view} {update} {delete}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;" class="nav-icon fas fa-eye"></span>', ['extra-option/view','id' => $model->extra_option_id, 'restaurantUuid'=> $model->option->item->restaurant_uuid ], [
                                        'title' => 'View',
                                        'data-pjax' => '0',
                                            ]
                            );
                        },
                        'update' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;" class="nav-icon fas fa-edit"></span>', ['extra-option/update','id' => $model->extra_option_id, 'restaurantUuid'=> $model->option->item->restaurant_uuid ], [
                                        'title' => 'Update',
                                        'data-pjax' => '0',
                                            ]
                            );
                        },
                        'delete' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;color: red;" class="nav-icon fas fa-trash"></span>', ['extra-option/delete','id' => $model->extra_option_id, 'restaurantUuid'=> $model->option->item->restaurant_uuid ], [
                                        'title' => 'Delete',
                                        'data' => [
                                            'confirm' => 'Are you absolutely sure ? You will lose all the information about this item with this action.',
                                            'method' => 'post',
                                        ],
                            ]);
                        },
                    ],
                ],],
            'layout' => '{summary}<div class="card-body">{items}{pager}</div>',
            'tableOptions' => ['class' => 'table  table-bordered table-hover'],
            'summaryOptions' => ['class' => "card-header"],
        ]);
        ?>
    </div>



</div>
