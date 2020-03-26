<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use common\models\Restaurant;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Restaurants', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="restaurant-view">

    <h1>
        <?= Html::encode($this->title) ?>
        <span class="badge">
            <?= $model->status ?>
        </span>

    </h1>



    <p>
        <?= Html::a('Update', ['update', 'id' => $model->restaurant_uuid], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->restaurant_uuid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>

        <?php if ($model->restaurant_status != Restaurant::RESTAURANT_STATUS_OPEN) { ?>
            <?=
            Html::a('Open', ['promote-to-open', 'id' => $model->restaurant_uuid], [
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
            Html::a('Busy', ['promote-to-busy', 'id' => $model->restaurant_uuid], [
                'class' => 'btn btn-warning',
                'data' => [
                    'confirm' => 'Are you sure you want to change restaurant status to busy?',
                    'method' => 'post',
                ],
            ])
            ?>
        <?php } ?>
        
        <?php if ($model->restaurant_status != Restaurant::RESTAURANT_STATUS_CLOSE) { ?>
            <?=
            Html::a('Close', ['promote-to-close', 'id' => $model->restaurant_uuid], [
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
            'restaurant_uuid',
            'vendor.vendor_name',
            'name',
            'name_ar',
            'tagline',
            'tagline_ar',
            'status',
            'thumbnail_image',
            'logo',
            'support_delivery',
            'support_pick_up',
            'phone_number',
            'restaurant_created_at',
            'restaurant_updated_at',
        ],
    ])
    ?>

</div>
