<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Order;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;

$this->title = 'Drafts';
$this->params['breadcrumbs'][] = $this->title;
?>

<p>
    <?= Html::a('Create Order', ['create', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-success']) ?>
</p>

<!-- /.input group -->
<div class="card">
    <div class="card-body">


        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
              'filterModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => 'order_uuid',
                    "format" => "raw",
                    "value" => function($model) {
                        return '#' . $model->order_uuid;
                    }
                ],
                [
                    'attribute' => 'customer_name',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if($data->customer_id)
                            return Html::a($data->customer->customer_name, ['customer/view', 'id' => $data->customer_id, 'restaurantUuid' => $data->restaurant_uuid]);
                    },
                    'visible' => function ($data) {
                        return $data->customer_id ? true : false;
                    },
                ],
                [
                    'attribute' => 'order_created_at',
                    "format" => "raw",
                    "value" => function($model) {
                        return Yii::$app->formatter->asRelativeTime($model->order_created_at);
                    },
                ],
                'total_price:currency',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => ' {view} {update} {delete}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;" class="nav-icon fas fa-eye"></span>', ['view', 'id' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid], [
                                        'title' => 'View',
                                        'data-pjax' => '0',
                                            ]
                            );
                        },
                        'update' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;" class="nav-icon fas fa-edit"></span>', ['update', 'id' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid], [
                                        'title' => 'Update',
                                        'data-pjax' => '0',
                                            ]
                            );
                        },
                        'delete' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;color: red;" class="nav-icon fas fa-trash"></span>', ['delete', 'id' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid], [
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
            'layout' => '{summary}<div class="box-body table-responsive no-padding">{items}<div class="card-footer clearfix">{pager}</div>',
            'tableOptions' => ['class' => 'table table-bordered table-hover'],
            'summaryOptions' => ['class' => "card-header"],
        ]);
        ?>


    </div>
</div>
