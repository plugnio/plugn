<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\RestaurantDeliverySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Restaurant Deliveries';
$this->params['breadcrumbs'][] = $this->title;
?>

<div>
    <div class="card card-default">
        <div class="card-header">
            <h1 class="card-title">Kuwait City</h1>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-remove"></i></button>
            </div>
        </div>
        <div class="card-body">

            <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'area.area_name',
                    'min_delivery_time',
                    'delivery_fee:currency',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'template' => ' {view} {update} {delete}',
                        'buttons' => [
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
                                                'confirm' => 'Are you absolutely sure ? You will lose all the information about this category with this action.',
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
</div>