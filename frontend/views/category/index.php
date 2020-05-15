<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;

$this->title = 'Manage Categories';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-title"> <i class="icon-custom-left"></i>
    <p>
        <?= Html::a('Create Category', ['create', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-success']) ?>
    </p>
</div>
<div class="card">

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'sort_number',
            'title',
            'title_ar',
            'subtitle',
            'subtitle_ar',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => ' {view} {update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(
                                        '<span style="margin-right: 20px;" class="nav-icon fas fa-edit"></span>', ['update', 'id' => $model->category_id, 'restaurantUuid' => $model->restaurant_uuid], [
                                    'title' => $url,
                                    'data-pjax' => '0',
                                        ]
                        );
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(
                                        '<span style="margin-right: 20px;color: red;" class="nav-icon fas fa-trash"></span>', ['delete', 'id' => $model->category_id, 'restaurantUuid' => $model->restaurant_uuid], [
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
        'layout' => '{summary}<div class="card-body">{items}<div class="card-footer clearfix">{pager}</div></div>',
        'tableOptions' => ['class' => 'table table-bordered table-hover'],
        'summaryOptions' => ['class' => "card-header"],
    ]);
    ?>

</div>
