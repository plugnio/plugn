<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\WorkingHoursSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = 'Working Hours';
$this->params['breadcrumbs'][] = $this->title;
?>
    <?php if ($dataProvider->totalCount > 0) { ?> 
        <p>
            <?= Html::a('Create Working Hours', ['create', 'restaurantUuid' => $restaurantUuid], ['class' => 'btn btn-success']) ?>
        </p>
    <?php } ?>   

<div class="card">

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'showOnEmpty' => false,
        'emptyText' => '<div style="padding: 70px 0; text-align: center;">'
        . '  <h4>You currently do not have working hours set.</h4>'
        . Html::a('Create Working Hours', ['create', 'restaurantUuid' => $restaurantUuid], ['class' => 'btn btn-success'])
        . '</div>',
        'columns' => [
            'workingDay.name',
            'operating_from',
            'operating_to',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => ' {update} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(
                                        '<span style="margin-right: 20px;" class="nav-icon fas fa-edit"></span>', ['update', 'working_day_id' => $model->working_day_id, 'restaurantUuid' => $model->restaurant_uuid], [
                                    'title' => $url,
                                    'data-pjax' => '0',
                                        ]
                        );
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(
                                        '<span style="margin-right: 20px;color: red;" class="nav-icon fas fa-trash"></span>', ['delete', 'working_day_id' => $model->working_day_id, 'restaurantUuid' => $model->restaurant_uuid], [
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
        'tableOptions' => ['class' => 'table table-responsive table-bordered table-hover'],
        'summaryOptions' => ['class' => "card-header"],
    ]);
    ?>


</div>

