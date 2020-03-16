<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Order */

$this->title = 'Update Order: ' . $model->order_uuid;
$this->params['breadcrumbs'][] = ['label' => 'Orders', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->order_uuid, 'url' => ['view', 'id' => $model->order_uuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="order-update">

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>


    <h2>Items</h2>


    <div class="card">
        
    <p>
        <?= Html::a('Create Order Item', ['order-item/create', 'id' => $model->order_uuid], ['class' => 'btn btn-success','style'=>'    margin: 10px;']) ?>
    </p>
    
        <?=
        GridView::widget([
            'dataProvider' => $ordersItemDataProvider,
            'columns' => [
                'item_name',
                'item_price',
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
                    'controller'=>'order-item',
                    'buttons' => [
                        'view' => function ($url) {
                            return Html::a(
                                            '<span style="margin-right: 20px;" class="nav-icon fas fa-eye"></span>', $url, [
                                        'title' => $url,
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
                                            '<span style="margin-right: 20px;color: red;" class="nav-icon fas fa-trash"></span>', $url, [
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
