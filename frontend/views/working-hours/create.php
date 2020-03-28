<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WorkingHours */
$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Create Working Hours';
$this->params['breadcrumbs'][] = ['label' => 'Working Hours', 'url' => ['index', 'restaurantUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="working-hours-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
