<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\PaymentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['restaurant_uuid'] = $storeUuid;

$this->title = 'Payments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="payment-index">


    <div class="card">


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'payment_uuid',
            'customer_id',
            'order_uuid',
//            'payment_gateway_order_id',
//            'payment_gateway_transaction_id',
            //'payment_mode',
            'payment_current_status:ntext', 
            'payment_amount_charged:currency',
//            'payment_net_amount',
//            'payment_gateway_fee',  
            //'payment_udf1',
            //'payment_udf2',
            //'payment_udf3',
            //'payment_udf4',
            //'payment_udf5',
            //'payment_created_at',
            //'payment_updated_at',
            //'received_callback',
//            'response_message',

                    [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => ' {view} {delete}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;" class="nav-icon fas fa-eye"></span>', ['view', 'id' => $model->payment_uuid, 'storeUuid' => $model->restaurant_uuid], [
                                        'title' => 'View',
                                        'data-pjax' => '0',
                                            ]
                            );
                        },
                        'delete' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;color: red;" class="nav-icon fas fa-trash"></span>', ['delete', 'id' => $model->payment_uuid, 'storeUuid' => $model->restaurant_uuid], [
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
            'tableOptions' => ['class' => 'table  table-bordered table-hover'],
            'summaryOptions' => ['class' => "card-header"],
        ]);
        ?>


    </div>

</div>
