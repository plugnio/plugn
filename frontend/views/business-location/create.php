<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessLocation */

$this->params['restaurant_uuid'] = $restaurantUuid;
$this->title = 'Create Business Location';
$this->params['breadcrumbs'][] = ['label' => 'Business Locations', 'url' => ['index',  'restaurantUuid' => $restaurantUuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-location-create">

    <?= $this->render('_form', [
        'model' => $model,
        'restaurantUuid' => $restaurantUuid
    ]) ?>

</div>
