<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Restaurant Branches';
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="restaurant-branch-index">


    <p>
        <?= Html::a('Create Restaurant Branch', ['create'], ['class' => 'btn btn-success']) ?>
    </p>



    <div class="card">


        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'branch_name_en',
                'branch_name_ar',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => ' {update} {delete}',
                    'buttons' => [
                        'update' => function ($url) {
                            return Html::a(
                                            '<span style="margin-right: 20px;" class="nav-icon fas fa-edit"></span>', $url, [
                                        'title' => $url,
                                        'data-pjax' => '0',
                                            ]
                            );
                        },
                        'delete' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;color: red;" class="nav-icon fas fa-trash"></span>',$url, [
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
