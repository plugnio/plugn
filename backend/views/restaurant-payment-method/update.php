<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RestaurantPaymentMethod */

$this->title = 'Update Restaurant Payment Method: ' . $model->restaurant_uuid;
$this->params['breadcrumbs'][] = ['label' => 'Restaurant Payment Methods', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->restaurant_uuid, 'url' => ['view', 'restaurant_uuid' => $model->restaurant_uuid, 'payment_method_id' => $model->payment_method_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="restaurant-payment-method-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
