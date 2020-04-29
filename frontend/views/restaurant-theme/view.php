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

    <p>
        <?= Html::a('Update', ['update', 'restaurantUuid' => $model->restaurant_uuid], ['class' => 'btn btn-primary']) ?>
    </p>
    <div class="card">
        <div class="card-body">
            <?=
            DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'primary',
                    'secondary',
                    'tertiary',
                    'medium',
                    'dark',
                ],
                'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
            ])
            ?>

        </div>
    </div>


</div>
