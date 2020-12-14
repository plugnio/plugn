<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantBranch */
$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Create Store Branch';
$this->params['breadcrumbs'][] = ['label' => 'Store Branches', 'url' => ['index', 'storeUuid' =>$model->restaurant_uuid ]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="restaurant-branch-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
