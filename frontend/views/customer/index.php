<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = 'Customers';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'options' => [
                'class' => 'pagination pagination-sm m-0 float-right',
            ],
            'linkOptions' => ['class' => 'page-link'],
            'activePageCssClass' => 'page-item active',
            'disabledPageCssClass' => 'page-item  disabled',
            'prevPageCssClass' => 'page-item prev disabled',
            'prevPageLabel' => '<span class=" page-link">«</span>',
            'nextPageCssClass' => 'page-item next disabled',
        ],
        'columns' => [
            'customer_name',
            'customer_phone_number',
            'customer_email:email',
            'customer_created_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => ' {view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a(
                                        '<span style="margin-right: 20px;" class="nav-icon fas fa-eye"></span>', ['view', 'id' => $model->customer_id, 'restaurantUuid' => $model->restaurant_uuid], [
                                    'title' => 'View',
                                    'data-pjax' => '0',
                                        ]
                        );
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(
                                        '<span style="margin-right: 20px;color: red;" class="nav-icon fas fa-trash"></span>', ['delete', 'id' => $model->customer_id, 'restaurantUuid' => $model->restaurant_uuid], [
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
        'layout' => '{summary}<div class="card-body"><div class="box-body table-responsive no-padding">{items}<div class="card-footer clearfix">{pager}</div></div></div>',
        'tableOptions' => ['class' => 'table table-bordered table-hover'],
        'summaryOptions' => ['class' => "card-header"],
    ]);
    ?>


</div>
