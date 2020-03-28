<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantBranch */
$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Update Restaurant Branch: ' . $model->branch_name_en;
$this->params['breadcrumbs'][] = ['label' => 'Restaurant Branches', 'url' => ['index', 'restaurantUuid' =>$model->restaurant_uuid ]];
$this->params['breadcrumbs'][] = ['label' => $model->branch_name_en, 'url' => ['view', 'id' => $model->restaurant_branch_id, 'restaurantUuid' =>$model->restaurant_uuid ]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="restaurant-branch-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
