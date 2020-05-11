<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Restaurant;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="restaurant-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->restaurant_uuid], ['class' => 'btn btn-primary']) ?>

    </p>
    <div class="card">
        <div class="card-body">
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    [
                        'label' => 'Payment Methods',
                        'value' => function ($data) {
                            $paymentMethods = '';

                            foreach ($data->getPaymentMethods()->all() as $key => $paymentMethod) {

                                if ($key == 0)
                                    $paymentMethods .= '<b>' . $paymentMethod['payment_method_name'] . '<b>';
                                else
                                    $paymentMethods .= ', ' . '<b>' . $paymentMethod['payment_method_name'] . '<b>';
                            }

                            return $paymentMethods;
                        },
                        'format' => 'raw'
                    ],
                    'name',
                    'name_ar',
                    'tagline',
                    'tagline_ar',
                    'status',
                    [
                        'attribute' => 'thumbnail_image',
                        'format' => 'html',
                        'value' => function ($data) {
                            return Html::img($data->getRestaurantThumbnailImageUrl());
                        },
                    ],
                    [
                        'attribute' => 'logo',
                        'format' => 'html',
                        'value' => function ($data) {
                            return Html::img($data->getRestaurantLogoUrl());
                        },
                    ],
                    [
                        'label' => 'Support Delivery',
                        'value' => function ($data) {
                            return $data->support_delivery ? 'Yes' : 'No';
                        },
                        'format' => 'raw'
                    ],
                    [
                        'label' => 'Support Pick up',
                        'value' => function ($data) {
                            return $data->support_pick_up ? 'Yes' : 'No';
                        },
                        'format' => 'raw'
                    ],
                    [
                        'attribute' => 'phone_number',
                        'format' => 'html',
                        'value' => function ($data) {
                            return $data->phone_number;
                        },
                        'visible' => $model->phone_number != null,
                    ],
                    'restaurant_email:email',
                    [
                        'attribute' => 'armada_api_key',
                        'format' => 'html',
                        'value' => function ($data) {
                            return $data->armada_api_key;
                        },
                        'visible' => $model->armada_api_key != null,
                    ],
                    'restaurant_created_at',
                    'restaurant_updated_at',
                ],
                'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
            ])
            ?>

        </div>
    </div>

</div>
