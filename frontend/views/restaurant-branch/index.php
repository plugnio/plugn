<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = 'Store Branches';
$this->params['breadcrumbs'][] = $this->title;
?>



<div class="restaurant-branch-index">

    <?php if ($dataProvider->totalCount > 0) { ?>
        <p>
            <?= Html::a('Create Store Branch', ['create', 'restaurantUuid' => $restaurantUuid], ['class' => 'btn btn-success']) ?>
        </p>
    <?php } ?>

    <div class="card">

            <div class="box-body table-responsive no-padding">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'showOnEmpty' => false,
            'emptyText' => '<div style="padding: 70px 0; text-align: center;">'
            . '  <h4>You currently do not have Branches set.</h4>'
            . Html::a('Create Store Branch', ['create', 'restaurantUuid' => $restaurantUuid], ['class' => 'btn btn-success'])
            . '</div>',
            'columns' => [
                'branch_name_en',
                'branch_name_ar',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => ' {update} {delete}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;" class="nav-icon fas fa-edit"></span>', ['view', 'id' => $model->restaurant_branch_id, 'restaurantUuid' => $model->restaurant_uuid], [
                                        'title' => 'Update',
                                        'data-pjax' => '0',
                                            ]
                            );
                        },
                        'delete' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;color: red;" class="nav-icon fas fa-trash"></span>', ['delete', 'id' => $model->restaurant_branch_id, 'restaurantUuid' => $model->restaurant_uuid], [
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
            'layout' => '{summary}<div class="card-body"><div class="box-body table-responsive no-padding">{items}<div class="card-footer clearfix">{pager}</div></div>',
            'tableOptions' => ['class' => 'table table-bordered  table-hover'],
            'summaryOptions' => ['class' => "card-header"],
        ]);
        ?>


    </div>
    </div>

</div>
