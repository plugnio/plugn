<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantDelivery */

$this->title = 'Update Delivery Zone: ';
$this->params['breadcrumbs'][] = ['label' => 'Restaurant Deliveries', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="restaurant-delivery-update">


    <?= $this->render('_form-delivery-zone', [
        'model' => $model,
    ]) ?>

</div>
