<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Order;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\OrderSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->params['restaurant_uuid'] = $restaurant_model->restaurant_uuid;

$this->title = 'Drafts';
$this->params['breadcrumbs'][] = $this->title;
$js = "
$(function () {
  $('.summary').insertAfter('.top');
});
";
$this->registerJs($js);
?>

<section id="data-list-view" class="data-list-view-header">

    <!-- Data list view starts -->
    <div class="action-btns d-none">
        <div class="btn-dropdown mr-1 mb-1">
            <div class="btn-group dropdown actions-dropodown">
                <?= Html::a('<i class="feather icon-plus"></i> Add New', ['create', 'restaurantUuid' => $restaurant_model->restaurant_uuid], ['class' => 'btn btn-outline-primary']) ?>
            </div>
        </div>
    </div>


    <?php echo $this->render('_search', ['model' => $searchModel, 'restaurant_uuid' => $restaurant_model->restaurant_uuid]); ?>


    <!-- DataTable starts -->
    <div class="table-responsive">

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'rowOptions' => function($model) {
                $url = Url::to(['order/view', 'id' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid]);

                return [
                    'onclick' => "window.location.href='{$url}'"
                ];
            },
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'label' => 'Order ID',
                    "format" => "raw",
                    "value" => function($model) {
                        return '#' . $model->order_uuid;
                    }
                ],
                [
                    'attribute' => 'customer_name',
                    'format' => 'raw',
                    'value' => function ($data) {
                        if ($data->customer_id)
                            return Html::a($data->customer->customer_name, ['customer/view', 'id' => $data->customer_id, 'restaurantUuid' => $data->restaurant_uuid]);
                    },
                    'visible' => function ($data) {
                        return $data->customer_id ? true : false;
                    },
                ],
                [
                    'label' => 'Payment',
                    "format" => "raw",
                    "value" => function($data) {
                        if ($data->payment_uuid)
                            return $data->payment->payment_current_status;
                        else
                            return $data->paymentMethod->payment_method_name;
                    },
                ],
                'total_price:currency',
                'order_created_at:datetime',
                [
                    'header' => 'Action',
                    'class' => 'yii\grid\ActionColumn',
                    'template' => ' {view} {update} {delete}',
                    'buttons' => [
                        'view' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;"><i class="feather icon-eye"></i></span>', ['view', 'id' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid], [
                                        'data-pjax' => '0',
                                            ]
                            );
                        },
                        'delete' => function ($url, $model) {
                            return Html::a(
                                            '<span style="margin-right: 20px;color: red;"><i class="feather icon-trash"></i></span>', ['delete', 'id' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid], [
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
            'layout' => '{summary}{items}{pager}',
            'tableOptions' => ['class' => 'table data-list-view'],
        ]);
        ?>

    </div>
    <!-- DataTable ends -->

</section>
<!-- Data list view end -->
