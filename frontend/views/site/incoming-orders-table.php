

<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\Order;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'rowOptions' => function($model) {
        $url = Url::to(['order/view', 'id' => $model->order_uuid, 'storeUuid' => $model->restaurant_uuid]);

        return [
            'onclick' => "window.location.href='{$url}'"
        ];
    },
    'columns' => [
        //['class' => 'yii\grid\SerialColumn'],
        [
            'label' => 'Order ID',
            'attribute' => 'order_uuid',
            "format" => "raw",
            "value" => function($model) {
                return Html::a('#' . $model->order_uuid, ['order/view', 'id' => $model->order_uuid, 'storeUuid' => $model->restaurant_uuid]);
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
            'attribute' => 'business_location_name'
        ],

        [
            'attribute' => 'customer_name',
            'format' => 'raw',
            'value' => function ($data) {
                if ($data->customer_id)
                    return Html::a($data->customer->customer_name, ['customer/view', 'id' => $data->customer_id, 'storeUuid' => $data->restaurant_uuid]);
            },
            'visible' => function ($data) {
                return $data->customer_id ? true : false;
            },
        ],
        [
            'attribute' => 'customer_phone_number',
            "format" => "raw",
            "value" => function($model) {
              return '<a href="tel:'. $model->customer_phone_number .'"> '. str_replace(' ', '', $model->customer_phone_number) .' </a>';
            }
        ],
        /*[
            'label' => 'When',
            'format' => 'raw',
            'filter' => [
                1 => 'Scheduled',
                0 => 'As soon as possible'
            ],
            'value' => function ($data) {
                return $data->is_order_scheduled ? 'Scheduled' : 'As soon as possible';
            },
        ],*/
        [
            'attribute' => 'payment_method_name',
            'label' => 'Payment',
            "format" => "raw",
            "value" => function($data) {
                return $data->paymentMethod->payment_method_name;
            },
            "visible" => function($data) {
                return $data->payment->payment_current_status;
            },
        ],
        [
            'attribute' => 'total_price',
            "value" => function($data) {
                    return Yii::$app->formatter->asCurrency($data->total_price, $data->currency->code, [
                        \NumberFormatter::MAX_FRACTION_DIGITS => $data->currency->decimal_place
                    ]);
            },
        ],
    ],
    'layout' => '{summary}{items}{pager}',
    'tableOptions' => ['class' => 'table data-list-view', 'id' => 'new-order-table'],
]);
?>
