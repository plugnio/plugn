<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model frontend\models\Agent */
$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = 'Update Agent: ' . $model->agent_name;
$this->params['breadcrumbs'][] = ['label' => 'Agents', 'url' => ['index', 'restaurantUuid' => $restaurantUuid]];
$this->params['breadcrumbs'][] = ['label' => $model->agent_name, 'url' => ['index','restaurantUuid' => $restaurantUuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="agent-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
