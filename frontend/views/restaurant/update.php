<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Restaurant */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Update Restaurant: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['index','restaurantUuid' =>$model->restaurant_uuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="restaurant-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
