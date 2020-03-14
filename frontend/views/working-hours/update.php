<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WorkingHours */

$this->title = 'Update Working Hours: ' . $model->working_day_id;
$this->params['breadcrumbs'][] = ['label' => 'Working Hours', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->working_day_id, 'url' => ['view', 'working_day_id' => $model->working_day_id, 'restaurant_uuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="working-hours-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
