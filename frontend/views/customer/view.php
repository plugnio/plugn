<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use common\models\Order;

/* @var $this yii\web\View */
/* @var $model common\models\Customer */

$this->title = $model->customer_name;
$this->params['breadcrumbs'][] = ['label' => 'Customers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="customer-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->customer_id], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->customer_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>
    <div class="card">
        <div class="card-body">
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'customer_id',
                    'customer_name',
                    'customer_phone_number',
                    'customer_email:email',
                    'customer_created_at',
                    'customer_updated_at',
                ],
                'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
            ])
            ?>

        </div>
    </div>

    
    <h2>Orders</h2>
    <div class="card">

        <?=
        GridView::widget([
            'dataProvider' => $customersOrdersData,
            'columns' => [
                'area_name',
                'customer_name',
                'customer_phone_number',
                [
                    'attribute' => 'order_status',
                    'format' => "raw",
                    'value' => function($model) {
                        if ($model->order_status == Order::STATUS_SUBMITTED || $model->order_status == Order::STATUS_OUT_FOR_DELIVERY)
                            return '<span class="badge bg-warning" >' . $model->orderStatus . '</span>';
                        else if ($model->order_status == Order::STATUS_BEING_PREPARED)
                            return '<span class="badge bg-primary" >' . $model->orderStatus . '</span>';
                        else if ($model->order_status == Order::STATUS_COMPLETE)
                            return '<span class="badge bg-success" >' . $model->orderStatus . '</span>';
                    }
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => ' {view} {update} {delete}',
                    'controller' => 'order',
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
            'layout' => '{summary}<div class="card-body">{items}{pager}</div>',
            'tableOptions' => ['class' => 'table table-bordered table-hover'],
            'summaryOptions' => ['class' => "card-header"],
        ]);
        ?>


    </div>
