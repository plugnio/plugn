<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\RestaurantDeliverySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Restaurant Deliveries';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card">
    <div class="card-body table-responsive p-0">
        <table class="table table-hover text-nowrap">
            <thead>
                <tr>
                    <th>City</th>

                </tr>
            </thead>
            <tbody>
                <tr>

                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            'city.city_name',
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

