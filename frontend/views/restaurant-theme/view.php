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
                                <span>' . $data->primary . '</span>
                                    <span class="input-group-text" style="float: right; display: flex; border: 1px solid #ced4da; border-radius: .25rem;padding: 0;width:50px">
                                            <span style="width:100px;height:20px;background:' . $data->primary . ';"></span>
                                        </span>
                                ';
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'secondary',
                    'value' => function ($data) {
                        return '
                                <span>' . $data->secondary . '</span>
                                    <span class="input-group-text" style="float: right; display: flex; border: 1px solid #ced4da; border-radius: .25rem;padding: 0;width:50px">
                                            <span style="width:100px;height:20px;background:' . $data->secondary . ';"></span>
                                        </span>
                                ';
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'tertiary',
                    'value' => function ($data) {
                        return '
                                <span>' . $data->tertiary . '</span>
                                    <span class="input-group-text" style="float: right; display: flex; border: 1px solid #ced4da; border-radius: .25rem;padding: 0;width:50px">
                                            <span style="width:100px;height:20px;background:' . $data->tertiary . ';"></span>
                                        </span>
                                ';
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'medium',
                    'value' => function ($data) {
                        return '
                                <span>' . $data->medium . '</span>
                                    <span class="input-group-text" style="float: right; display: flex; border: 1px solid #ced4da; border-radius: .25rem;padding: 0;width:50px">
                                            <span style="width:100px;height:20px;background:' . $data->medium . ';"></span>
                                        </span>
                                ';
                    },
                    'format' => 'html'
                ],
                [
                    'attribute' => 'dark',
                    'value' => function ($data) {
                        return '
                                <span>' . $data->dark . '</span>
                                    <span class="input-group-text" style="float: right; display: flex; border: 1px solid #ced4da; border-radius: .25rem;padding: 0;width:50px">
                                            <span style="width:100px;height:20px;background:' . $data->dark . ';"></span>
                                        </span>
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
