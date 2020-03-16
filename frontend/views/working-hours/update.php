<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\WorkingHours */

$this->title = 'Update Working Hours: ' . $model->workingDay->name;
$this->params['breadcrumbs'][] = ['label' => 'Working Hours', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="working-hours-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
