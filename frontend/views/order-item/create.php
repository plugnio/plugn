<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WorkingHours */

$this->params['restaurant_uuid'] = $model->restaurant->restaurant_uuid;

$this->title = 'Create Order Item';
$this->params['breadcrumbs'][] = ['label' => 'Order #' . $model->order_uuid, 'url' => ['order/update','id' => $model->order_uuid, 'restaurantUuid' =>$model->restaurant->restaurant_uuid ]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="working-hours-create">

    <?= $this->render('_form', [
        'model' => $model,
        'restaurantsItems' => $restaurantsItems
    ]) ?>

</div>
