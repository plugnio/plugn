<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Item */

$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = 'Update Item: ' . $model->item_name;
$this->params['breadcrumbs'][] = ['label' => 'Items', 'url' => ['index', 'restaurantUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = ['label' => $model->item_name, 'url' => ['view', 'id' => $model->item_uuid, 'restaurantUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="item-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
