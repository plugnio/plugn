<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Order;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;

$this->title = 'Orders';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
                'attribute' => 'order_created_at',
                "format" => "raw",
                "value" => function($model) {
                    return Yii::$app->formatter->asRelativeTime($model->order_created_at);
                }
            ],
            'customer_name',
            [
                'attribute' => 'order_status',
                "format" => "raw",
                "value" => function($model) {
                    if ($model->order_status == Order::STATUS_PENDING || $model->order_status == Order::STATUS_OUT_FOR_DELIVERY)
                        return '<span class="badge bg-warning" >' . $model->orderStatus . '</span>';
                    else if ($model->order_status == Order::STATUS_BEING_PREPARED)
                        return '<span class="badge bg-primary" >' . $model->orderStatus . '</span>';
                    else if ($model->order_status == Order::STATUS_COMPLETE)
                        return '<span class="badge bg-success" >' . $model->orderStatus . '</span>';
                    else if ($model->order_status == Order::STATUS_CANCELED)
                        return '<span class="badge bg-danger" >' . $model->orderStatus . '</span>';
                    else if ($model->order_status == Order::STATUS_REFUNDED)
                        return '<span class="badge bg-danger" >' . $model->orderStatus . '</span>';
                }
            ],
            [
                'label' => 'Payment',
                "format" => "raw",
                "value" => function($data) {
                if($data->payment_uuid)
                    return $data->payment->payment_current_status;
                else
                    return $data->paymentMethod->payment_method_name;
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
        'layout' => '{summary}<div class="card-body">{items}<div class="card-footer clearfix">{pager}</div></div>',
        'tableOptions' => ['class' => 'table table-bordered table-hover'],
        'summaryOptions' => ['class' => "card-header"],
    ]);
    ?>


</div>
