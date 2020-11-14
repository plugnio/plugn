<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessLocation */

$this->title = 'Update Business Location: ' . $model->business_location_name;
$this->params['restaurant_uuid'] = $restaurantUuid;

$this->params['breadcrumbs'][] = ['label' => 'Business Locations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->business_location_name, 'url' => ['view', 'id' => $model->business_location_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="business-location-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'restaurantUuid' => $restaurantUuid
    ]) ?>

</div>
