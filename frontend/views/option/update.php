<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Option */
$this->params['restaurant_uuid'] = $restaurantUuid;
        
$this->title = 'Update Option: ' . $model->option_name;
$this->params['breadcrumbs'][] = ['label' => $model->item->item_name, 'url' => ['item/view', 'id' => $model->item->item_uuid ,'restaurantUuid' => $restaurantUuid]];
$this->params['breadcrumbs'][] = ['label' => $model->option_name, 'url' => ['view', 'id' => $model->option_id, 'restaurantUuid' => $restaurantUuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="option-update">

    <?= $this->render('_form', [
        'model' => $model,
        'restaurantUuid' => $restaurantUuid
    ]) ?>

</div>
