<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantTheme */

$this->title = 'Store Theme';
$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="restaurant-theme-view">


    <?= Html::a('Update', ['update', 'restaurantUuid' => $model->restaurant_uuid], ['class' => 'btn btn-primary']) ?>
</p>
<div class="card">



    <div class="card-body">
        <?=
        DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'primary',
                    'value' => function ($data) {
                        return '
                                        <div style="float:right; background:' . $data->primary . ';" class="text-center colors-container rounded text-white width-100 height-100 d-flex align-items-center justify-content-center mr-1 ml-50 my-1 shadow">
                                            <span class="align-middle">primary</span>
                                        </div>
                                ';
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'secondary',
                    'value' => function ($data) {
                        return '
                                        <div style="float:right; background:' . $data->secondary . ';" class="text-center colors-container rounded text-white width-100 height-100 d-flex align-items-center justify-content-center mr-1 ml-50 my-1 shadow">
                                            <span class="align-middle">secondary</span>
                                        </div>
                                ';
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'tertiary',
                    'value' => function ($data) {
                        return '
                                <div style="float:right; background:' . $data->tertiary . ';" class="text-center colors-container rounded text-white width-100 height-100 d-flex align-items-center justify-content-center mr-1 ml-50 my-1 shadow">
                                    <span class="align-middle">tertiary</span>
                                </div>
                                ';
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'medium',
                    'value' => function ($data) {
                        return '
                        <div style="float:right; background:' . $data->medium . ';" class="text-center colors-container rounded text-white width-100 height-100 d-flex align-items-center justify-content-center mr-1 ml-50 my-1 shadow">
                            <span class="align-middle">medium</span>
                        </div>
                                ';
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'dark',
                    'value' => function ($data) {
                        return '
                            <div style="float:right; background:' . $data->dark . ';" class="text-center colors-container rounded text-white width-100 height-100 d-flex align-items-center justify-content-center mr-1 ml-50 my-1 shadow">
                                <span class="align-middle">dark</span>
                            </div>
                                ';
                    },
                    'format' => 'html'
                ],
            ],
            'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
        ])
        ?>

    </div>
</div>


</div>
