<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\BusinessLocation */

$this->params['restaurant_uuid'] = $restaurantUuid;
$this->title = 'Create Business Location';
$this->params['breadcrumbs'][] = ['label' => 'Business Locations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="business-location-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'restaurantUuid' => $restaurantUuid
    ]) ?>

</div>
