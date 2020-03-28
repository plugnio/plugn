<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WorkingHours */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Update Working Hours: ' . $model->workingDay->name;
$this->params['breadcrumbs'][] = ['label' => 'Working Hours', 'url' => ['index',  'restaurantUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="working-hours-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
