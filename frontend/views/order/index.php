<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Order;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-title"> <i class="icon-custom-left"></i>
    <p>
        <?= Html::a('Create Order', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
</div>
<div class="card">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'area_name',
            'customer_name',
            'customer_phone_number',
            [
                'attribute' => 'order_status',
                "format" => "raw",
                "value" => function($model) {
                    if($model->order_status == Order::STATUS_SUBMITTED || $model->order_status == Order::STATUS_OUT_FOR_DELIVERY)
                    return '<span class="badge bg-warning" >' . $model->orderStatus . '</span>';
                    else if($model->order_status == Order::STATUS_BEING_PREPARED)
                    return '<span class="badge bg-primary" >' . $model->orderStatus . '</span>';
                    else if($model->order_status == Order::STATUS_COMPLETE)
                    return '<span class="badge bg-success" >' . $model->orderStatus . '</span>';
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
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
