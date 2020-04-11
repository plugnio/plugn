<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantTheme */

$this->params['restaurant_uuid'] = $model->restaurant_uuid;

$this->title = 'Update Restaurant Theme';
$this->params['breadcrumbs'][] = ['label' => 'Restaurant Themes', 'url' => ['view', 'restaurantUuid' => $model->restaurant_uuid]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="restaurant-theme-update">


    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
