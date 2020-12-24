<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RefundSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $storeUuid;

$this->title = 'Refunds';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="refund-index">

    <div class="card">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'refund_id',
                'order_uuid',
                'refund_amount',
                'reason',
                'refund_status',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => ' {view} {delete}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;" class="nav-icon fas fa-eye"></span>', ['view', 'id' => $model->refund_id, 'storeUuid' => $model->restaurant_uuid], [
                                        'title' => 'View',
                                        'data-pjax' => '0',
                                            ]
                            );
                        },
                        'update' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;" class="nav-icon fas fa-edit"></span>', ['view', 'id' => $model->refund_id, 'storeUuid' => $model->restaurant_uuid], [
                                        'title' => 'Update',
                                        'data-pjax' => '0',
                                            ]
                            );
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

</div>
