<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantDelivery */

$this->title = 'Edit Delivery Zone';
$this->params['breadcrumbs'][] = ['label' => 'Delivery Zone', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Edit Delivery Zone';
?>
<div class="restaurant-delivery-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>