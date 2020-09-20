

<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;


echo GridView::widget([
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
                return Html::a('#' . $model->order_uuid, ['order/view', 'id' => $model->order_uuid, 'restaurantUuid' => $model->restaurant_uuid]);
            }
        ],
        [
            'attribute' => 'order_created_at',
            "format" => "raw",
            "value" => function($model) {
                return date('d M - h:i A', strtotime($model->order_created_at));
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
        'customer_phone_number',
        [
            'label' => 'When',
            'format' => 'raw',
            'value' => function ($data) {
                return $data->is_order_scheduled ? 'Scheduled' : 'As soon as possible';
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
    ],
    'layout' => '{summary}{items}{pager}',
    'tableOptions' => ['class' => 'table data-list-view', 'id' => 'new-order-table'],
]);
?>
