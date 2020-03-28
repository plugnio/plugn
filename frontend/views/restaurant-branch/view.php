<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantBranch */
$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = $model->branch_name_en;
$this->params['breadcrumbs'][] = ['label' => 'Restaurant Branches', 'url' => ['index', 'restaurantUuid' =>$model->restaurant_uuid ]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="restaurant-branch-view">

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->restaurant_branch_id, 'restaurantUuid' =>$model->restaurant_uuid ], ['class' => 'btn btn-primary']) ?>
        <?=
        Html::a('Delete', ['delete', 'id' => $model->restaurant_branch_id, 'restaurantUuid' =>$model->restaurant_uuid ], [
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
                    'branch_name_en',
                    'branch_name_ar',
                    [
                        'attribute' => 'prep_time',
                        'value' => function ($data) {
                            return Yii::$app->formatter->asDuration($data->prep_time * 60);
                        },
                    ]
                ],
                'options' => ['class' => 'table table-hover text-nowrap table-bordered'],
            ])
            ?>

        </div>
    </div>


</div>
