<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantDelivery */

$this->params['restaurant_uuid'] = $storeUuid;

$this->title = 'Create Store Delivery';
$this->params['breadcrumbs'][] = ['label' => 'Delivery Zone', 'url' => ['index', 'storeUuid' => $storeUuid]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="restaurant-delivery-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
