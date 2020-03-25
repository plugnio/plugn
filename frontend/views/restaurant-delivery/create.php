<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantDelivery */

$this->title = 'Create Restaurant Delivery';
$this->params['breadcrumbs'][] = ['label' => 'Restaurant Deliveries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="restaurant-delivery-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>