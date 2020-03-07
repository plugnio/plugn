<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Manage Categories';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-title"> <i class="icon-custom-left"></i>
    <p>
        <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
</div>
<div class="card">

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'sort_number',
            'category_name',
            'category_name_ar',
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
        'layout' => '{summary}<div class="card-body">{items}<div class="card-footer clearfix">{pager}</div></div>',
        'tableOptions' => ['class' => 'table table-bordered table-hover'],
        'summaryOptions' => ['class' => "card-header"],
    ]);
    ?>

</div>
