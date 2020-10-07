<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Agent */
$this->params['restaurant_uuid'] = $restaurantUuid;

$this->title = 'Edit Profile';
// $this->params['breadcrumbs'][] = ['label' => 'Agents', 'url' => ['index', 'restaurantUuid' => $restaurantUuid]];
// $this->params['breadcrumbs'][] = ['label' => $model->agent_name, 'url' => ['index','restaurantUuid' => $restaurantUuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="agent-update">

    <?= $this->render('_form', [
        'model' => $model,
        'restaurantUuid' => $restaurantUuid,
    ]) ?>

</div>
