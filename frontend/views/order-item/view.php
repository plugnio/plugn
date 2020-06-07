<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\OrderItem */
$this->params['restaurant_uuid'] = $model->restaurant->restaurant_uuid;

$this->title = 'Order Item: ' . $model->item_name;
$this->params['breadcrumbs'][] = ['label' => 'Order #' . $model->order_uuid, 'url' => ['order/update','id' => $model->order_uuid, 'restaurantUuid' =>$model->restaurant->restaurant_uuid ]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>


<div class="order-item-view">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->order_item_id, 'restaurantUuid' =>$model->restaurant->restaurant_uuid ], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->order_item_id, 'restaurantUuid' =>$model->restaurant->restaurant_uuid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="card">
        <div class="card-body">
            <?=
                DetailView::widget([
                  'model' => $model,
                  'attributes' => [
                    'order_item_id',
                  [
                      'attribute' => 'order_uuid',
                      "format" => "raw",
                      "value" => function ($model) {
                      return '#' . $model->order_uuid;
                      }
                  ],
                    'item_uuid',
                    'item_name',
                    'item_name_ar',
                    'item_price:currency',
                    'qty',
                    'customer_instruction',
                  ],
                  'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
                ])
            ?>

        </div>
    </div>

     <h2>Extra Options</h2>
    <p>
        <?= Html::a('Add Extra option', ['order-item-extra-option/create', 'id' => $model->order_item_id, 'restaurantUuid' =>$model->restaurant->restaurant_uuid], ['class' => 'btn btn-success','style'=>'    margin: 10px 10px 10px 0px;']) ?>
    </p>
    <div class="card">



        <?=
        GridView::widget([
            'dataProvider' => $orderItemsExtraOpiton,
            'columns' => [
                'extra_option_name',
                'extra_option_name_ar',
                'extra_option_price:currency',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'controller' => 'option',
                    'template' => ' {view} {update} {delete}',
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            return Html::a(
                                '<span style="margin-right: 20px; color: red;" class="nav-icon fas fa-trash"></span>',
                                ['order-item-extra-option/delete', 'id' => $model->order_item_extra_option_id ,'restaurantUuid' =>$model->restaurant->restaurant_uuid],
                                [
                                        'title' => 'Delete',
                                        'data' => [
                                            'confirm' => 'Are you absolutely sure ? You will lose all the information about this option with this action.',
                                            'method' => 'post',
                                        ],
                            ]
                            );
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
