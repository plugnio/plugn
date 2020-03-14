<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Restaurant;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="restaurant-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => Yii::$app->user->identity->restaurant_uuid], ['class' => 'btn btn-primary']) ?>


        <?php if ($model->restaurant_status != Restaurant::RESTAURANT_STATUS_OPEN) { ?>
            <?=
            Html::a('Open', ['promote-to-open', 'id' => Yii::$app->user->identity->restaurant_uuid], [
                'class' => 'btn btn-success',
                'data' => [
                    'confirm' => 'Are you sure you want to change restaurant status to open?',
                    'method' => 'post',
                ],
            ])
            ?>
        <?php } ?>

        <?php if ($model->restaurant_status != Restaurant::RESTAURANT_STATUS_BUSY) { ?>
            <?=
            Html::a('Busy', ['promote-to-busy', 'id' => Yii::$app->user->identity->restaurant_uuid], [
                'class' => 'btn btn-warning',
                'data' => [
                    'confirm' => 'Are you sure you want to change restaurant status to busy?',
                    'method' => 'post',
                ],
            ])
            ?>
        <?php } ?>

        <?php if ($model->restaurant_status != Restaurant::RESTAURANT_STATUS_CLOSED) { ?>
            <?=
            Html::a('Close', ['promote-to-close', 'id' => Yii::$app->user->identity->restaurant_uuid], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to promote this project to close?',
                    'method' => 'post',
                ],
            ])
            ?>
        <?php } ?>

    </p>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'vendor.vendor_name',
            'name',
            'name_ar',
            'tagline',
            'tagline_ar',
            'status',
            [
                'attribute' => 'thumbnail_image',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::img("https://res.cloudinary.com/plugn/image/upload/restaurants/" . $data->name . "/thumbnail-image/" . $data->thumbnail_image);
                },
            ],
            [
                'attribute' => 'logo',
                'format' => 'html',
                'value' => function ($data) {
                    return Html::img("https://res.cloudinary.com/plugn/image/upload/c_scale,h_105,w_105/restaurants/" . $data->name . "/logo/" . $data->logo);
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
            'min_pickup_time',
            'phone_number',
            'restaurant_created_at',
            'restaurant_updated_at',
        ],
    ])
    ?>

</div>
