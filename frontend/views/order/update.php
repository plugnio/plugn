<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Update Order: #' . $model->order_uuid;
$this->params['breadcrumbs'][] = ['label' => 'Order #' . $model->order_uuid, 'url' => ['order/view', 'id' => $model->order_uuid,'restaurantUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="order-update">

    <?=
    $this->render('_form', [
        'model' => $model,
        'restaurant_model' => $restaurant_model,
    ])
    ?>


    <h2>Items</h2>

        <p>
            <?= Html::a('Add Item', ['order-item/create', 'id' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid], ['class' => 'btn btn-success', 'style' => 'margin: 10px 10px 10px 0px;']) ?>
        </p>

    <div class="card">


        <?=
        GridView::widget([
            'dataProvider' => $ordersItemDataProvider,
            'columns' => [
                'item_name',
                'item_price:currency',
                'qty',
                [
                    'label' => 'Extra Options',
                    'value' => function ($data) {
                        $extraOptions = '';

                        foreach ($data->orderItemExtraOptions as $key => $extraOption) {

                            if ($key == 0)
                                $extraOptions .= $extraOption['extra_option_name'];
                            else
                                $extraOptions .= ', ' . $extraOption['extra_option_name'];
                        }

                        return $extraOptions;
                    },
                    'format' => 'raw'
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'controller' => 'option',
                    'template' => ' {view} {update} {delete}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;" class="nav-icon fas fa-eye"></span>', ['order-item/view', 'id' => $model->order_item_id, 'restaurantUuid' => $model->restaurant->restaurant_uuid], [
                                        'title' => $url,
                                        'data-pjax' => '0',
                                            ]
                            );
                        },
                        'update' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;" class="nav-icon fas fa-edit"></span>', ['order-item/update', 'id' => $model->order_item_id, 'restaurantUuid' => $model->restaurant->restaurant_uuid], [
                                        'title' => 'Update',
                                        'data-pjax' => '0',
                                            ]
                            );
                        },
                        'delete' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;color: red;" class="nav-icon fas fa-trash"></span>', ['order-item/delete', 'id' => $model->order_item_id, 'restaurantUuid' => $model->restaurant->restaurant_uuid], [
                                        'title' => 'Delete',
                                        'data' => [
                                            'confirm' => 'Are you absolutely sure ? You will lose all the information about this option with this action.',
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
