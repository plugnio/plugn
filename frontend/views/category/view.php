<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\Category */

$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = $model->category_name;
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index', 'restaurantUuid' => $restaurantUuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->category_id, 'restaurantUuid' => $restaurantUuid], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->category_id, 'restaurantUuid' => $restaurantUuid], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ])
        ?>
    </p>
    <div class="card">
        <div class="card-body">
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'sort_number',
                    'category_name',
                    'category_name_ar',
                    'sub_category_name',
                    'sub_category_name_ar',
                ],
            'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
            ])
            ?>
        </div>
    </div>

</div>
