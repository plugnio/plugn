<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WorkingDay */

$this->title = 'Update Working Day: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Working Days', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->working_day_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="working-day-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
